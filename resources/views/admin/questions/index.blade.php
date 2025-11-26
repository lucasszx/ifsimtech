<x-app-layout>
  <div class="admin-page premium-admin">

    {{-- HEADER --}}
    <header class="admin-header">
      <div class="header-left">
        <h1>Questões cadastradas</h1>
        <p>Gerencie, edite ou exclua questões já inseridas no sistema.</p>
      </div>

      <div class="header-right">
        {{-- Botão para gerenciar tópicos --}}
        <a href="{{ route('admin.topics.index') }}" class="btn-hero-outline btn-admin">
          Gerenciar tópicos
        </a>

        @if(!$questions->isEmpty())
          <a href="{{ route('admin.questions.create') }}" class="btn-hero">
            + Nova questão
          </a>
        @endif
      </div>
    </header>

    {{-- EMPTY STATE --}}
    @if($questions->isEmpty())
      <div class="empty-wrap">
        <div class="empty-card">
          <img src="/assets/empty-questions.svg" class="empty-img" alt="">
          <h3>Nenhuma questão cadastrada</h3>
          <p>Comece cadastrando sua primeira questão no sistema.</p>

          <a href="{{ route('admin.questions.create') }}" class="btn-start">
            Cadastrar questão
          </a>

          {{-- Acesso rápido aos tópicos mesmo no estado vazio --}}
          <a href="{{ route('admin.topics.index') }}" class="btn-ghost-lg" style="margin-top: 12px;">
            Gerenciar tópicos
          </a>
        </div>
      </div>
    @else

      {{-- GRID DE QUESTÕES --}}
      <div class="questions-grid">
        @foreach($questions as $q)
          <div class="q-card premium-q-card">

            {{-- META --}}
            <div class="q-meta">
              <span class="meta-badge">{{ $q->subject->name ?? 'Sem matéria' }}</span>
              <span class="meta-dot">•</span>
              <span class="meta-topics">
                {{ $q->topics->pluck('name')->implode(', ') ?: 'Sem tópico' }}
              </span>
              <span class="meta-dot">•</span>
              <span class="meta-year">{{ $q->year ?? '—' }}</span>
            </div>

            {{-- IMAGEM --}}
            @if($q->image_path)
              <img src="{{ asset('storage/' . $q->image_path) }}"
                   class="q-image"
                   alt="Imagem da questão">
            @endif

            {{-- ENUNCIADO --}}
            @if($q->statement)
              <p class="q-statement">
                {{ Str::limit($q->statement, 180) }}
              </p>
            @endif

            {{-- ALTERNATIVAS --}}
            <ul class="q-options">
              @foreach($q->options->sortBy('label') as $opt)
                <li class="q-option {{ $opt->is_correct ? 'is-correct' : '' }}">
                  <strong>{{ $opt->label }})</strong> {{ $opt->text }}
                </li>
              @endforeach
            </ul>

            {{-- AÇÕES --}}
            <div class="q-actions">
              <a href="{{ route('admin.questions.edit', $q) }}" class="action-link edit">Editar</a>

              <button type="button"
                      class="action-link delete"
                      onclick="confirmDelete({{ $q->id }})">
                Excluir
              </button>

              <form id="delete-form-{{ $q->id }}"
                    method="POST"
                    action="{{ route('admin.questions.destroy', $q) }}"
                    style="display:none;">
                @csrf
                @method('DELETE')
              </form>
            </div>

          </div>
        @endforeach
      </div>

      {{-- PAGINAÇÃO --}}
      <div class="pagination-wrap">
        {{ $questions->links() }}
      </div>
    @endif

  </div>
</x-app-layout>

<script>
  function confirmDelete(id) {
    Swal.fire({
      title: "Confirmar exclusão",
      text: "Deseja realmente excluir esta questão?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Excluir",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#10b981",
      cancelButtonColor: "#6b7280",
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${id}`).submit();
      }
    });
  }
</script>
