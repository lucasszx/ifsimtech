<x-app-layout>
  <div class="page-simulado">
    <div class="simulado-header">
      <h1>Novo Simulado</h1>
      <p>Monte seu simulado personalizado escolhendo matérias, quantidade de questões e dificuldade.</p>
    </div>

    <div class="simulado-card">
      <form method="POST" action="{{ route('exams.store') }}" class="simulado-form">
        @csrf

        <!-- Título -->
        <div class="form-group">
          <label for="title">Título (opcional)</label>
          <input id="title" name="title" type="text" placeholder="Simulado IFSul" class="input">
        </div>

        <!-- Número de questões -->
        <div class="form-group">
          <label for="questions_count">Número de questões</label>
          <input id="questions_count" name="questions_count" type="number" min="5" max="80" value="10" required class="input">
          @error('questions_count')
            <p class="error-msg">{{ $message }}</p>
          @enderror
        </div>

        <!-- Matérias -->
        <div class="form-group">
          <label>Matérias</label>
          <div class="checkbox-grid">
            @foreach($subjects as $s)
              <label class="checkbox">
                <input type="checkbox" name="subjects[]" value="{{ $s->id }}" @if($loop->first) required @endif>
                <span>{{ $s->name }}</span>
              </label>
            @endforeach
          </div>
          @error('subjects')
            <p class="error-msg">{{ $message }}</p>
          @enderror
        </div>

        <!-- Ações -->
        <div class="form-actions">
          <button class="btn btn-primary">Gerar</button>
          <a href="{{ route('results.history') }}" class="btn btn-outline">Histórico</a>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
