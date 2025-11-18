<x-app-layout>
  <div class="page page-wide">

    {{-- Cabeçalho — só mostra o botão se houver questões --}}
    <div class="flex items-center justify-between mt-4 mb-4">
      <h1 class="page-title">Questões cadastradas</h1>

      @if(!$questions->isEmpty())
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary btn-sm">
          + Nova questão
        </a>
      @endif
    </div>

    {{-- Estado vazio --}}
    @if($questions->isEmpty())
      <div class="card empty-state">
        <p class="muted">Nenhuma questão ainda.</p>
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary btn-sm">
          Cadastrar questão
        </a>
      </div>
    @endif

    {{-- Lista de questões --}}
    <div class="grid-cards">
      @foreach($questions as $q)
        <div class="card q-card">
          <div class="q-meta">
            {{ $q->subject->name ?? 'Sem matéria' }} •
            {{ $q->topics->pluck('name')->implode(', ') ?: 'Sem tópico' }} •
            {{ $q->year ?? '—' }}
          </div>

          @if($q->image_path)
            <img src="{{ asset('storage/' . $q->image_path) }}" class="q-image" alt="Imagem da questão">
          @endif

          @if($q->statement)
            <p class="q-statement">{{ Str::limit($q->statement, 160) }}</p>
          @endif

          <ul class="q-options">
            @foreach($q->options->sortBy('label') as $opt)
              <li class="q-option {{ $opt->is_correct ? 'is-correct' : '' }}">
                <strong>{{ $opt->label }})</strong> {{ $opt->text }}
              </li>
            @endforeach
          </ul>

          <div class="row-actions">
            <a href="{{ route('admin.questions.edit', $q) }}" class="btn btn-outline btn-sm">Editar</a>

            <!-- Botão de ação -->
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $q->id }})">
              Excluir
            </button>

            <!-- Formulário oculto que será enviado -->
            <form id="delete-form-{{ $q->id }}" method="POST" action="{{ route('admin.questions.destroy', $q) }}"
              style="display:none;">
              @csrf
              @method('DELETE')
            </form>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Paginação --}}
    <div class="pagination-wrap">
      {{ $questions->links() }}
    </div>

  </div>
</x-app-layout>

<script>
  function confirmDelete(id) {
    Swal.fire({
      title: "Confirmar exclusão",
      text: "Tem certeza disso?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim",
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