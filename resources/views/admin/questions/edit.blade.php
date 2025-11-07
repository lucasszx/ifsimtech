<x-app-layout>
  <div class="page page-narrow">
    <h1 class="page-title">Editar questão #{{ $question->id }}</h1>

    <form method="POST"
          action="{{ route('admin.questions.update', $question) }}"
          enctype="multipart/form-data"
          class="card form">
      @csrf @method('PUT')

      <div class="grid-2">
        <div class="form-group">
          <label class="form-label">Matéria</label>
          <select name="subject_id" class="select" required>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}" @selected($question->subject_id==$s->id)>{{ $s->name }}</option>
            @endforeach
          </select>
          @error('subject_id') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Conteúdo</label>
          <select name="topic_id" class="select">
            <option value="">Sem tópico</option>
            @foreach($topics as $t)
              <option value="{{ $t->id }}" @selected($question->topic_id==$t->id)>{{ $t->name }} — {{ $t->subject->name }}</option>
            @endforeach
          </select>
          @error('topic_id') <p class="error-msg">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="grid-3">
        <div class="form-group">
          <label class="form-label">Ano</label>
          <input type="number" name="year" value="{{ old('year', $question->year) }}" class="input">
          @error('year') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Origem</label>
          <input type="text" name="source" value="{{ old('source', $question->source) }}" class="input">
        </div>
      </div>

      @if($question->image_path)
        <div class="form-group">
          <label class="form-label">Imagem atual</label>
          <img src="{{ asset('storage/'.$question->image_path) }}" alt="Imagem atual" class="img-preview">
        </div>
      @endif

      <div class="form-group">
        <label class="form-label">Trocar imagem (opcional)</label>
        <input type="file" name="image" accept="image/*" class="file">
        @error('image') <p class="error-msg">{{ $message }}</p> @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Enunciado (opcional)</label>
        <textarea name="statement" rows="3" class="textarea">{{ old('statement', $question->statement) }}</textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Alternativas</label>
        @php $opts = $question->options->keyBy('label'); @endphp
        <div class="alt-grid">
          @foreach (['A','B','C','D','E'] as $opt)
            <div class="alt-item">
              <span class="alt-label">{{ $opt }}.</span>
              <input type="text" name="{{ $opt }}" value="{{ old($opt, optional($opts[$opt])->text) }}" class="input flex1" required>
            </div>
          @endforeach
        </div>
        @foreach (['A','B','C','D','E'] as $opt)
          @error($opt) <p class="error-msg">{{ $message }}</p> @enderror
        @endforeach
      </div>

      <div class="form-group">
        <label class="form-label">Alternativa correta</label>
        @php $correct = optional($question->options->firstWhere('is_correct', true))->label ?? 'A'; @endphp
        <select name="correct_label" class="select w-40" required>
          @foreach (['A','B','C','D','E'] as $opt)
            <option value="{{ $opt }}" @selected(($correct) === $opt)>{{ $opt }}</option>
          @endforeach
        </select>
        @error('correct_label') <p class="error-msg">{{ $message }}</p> @enderror
      </div>

      <div class="form-actions">
        <button class="btn btn-primary">Salvar</button>
        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline">Voltar</a>
      </div>
    </form>
  </div>
</x-app-layout>
