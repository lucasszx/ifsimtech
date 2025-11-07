<x-app-layout>
  <div class="page page-wide">
    <h1 class="page-title">Questões cadastradas</h1>

    @if($questions->isEmpty())
      <div class="card empty-state">
        <p class="muted">Nenhuma questão ainda.</p>
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary btn-sm">Cadastrar questão</a>
      </div>
    @endif

    <div class="grid-cards">
      @foreach($questions as $q)
        <div class="card q-card">
          <div class="q-meta">
            {{ $q->subject->name ?? 'Sem matéria' }} • {{ $q->topic->name ?? 'Sem tópico' }} • {{ $q->year ?? '—' }}
          </div>

          @if($q->image_path)
            <img src="{{ asset('storage/'.$q->image_path) }}" alt="Imagem da questão" class="q-image">
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

            <form action="{{ route('admin.questions.destroy', $q) }}"
                  method="POST"
                  onsubmit="return confirm('Excluir esta questão? Essa ação não pode ser desfeita.');"
                  class="inline-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>

    <div class="pagination-wrap">
      {{ $questions->links() }}
    </div>
  </div>
</x-app-layout>
