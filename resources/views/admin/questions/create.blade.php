<x-app-layout>
  <div class="admin-page premium-create">

    {{-- HEADER / HERO --}}
    <header class="create-hero">
      <div>
        <h1>Cadastrar nova questão</h1>
        <p>Envie a imagem, defina o enunciado, alternativas e selecione o conteúdo.</p>
      </div>

      <a href="{{ route('admin.questions.index') }}" class="btn-hero-outline">
        Voltar ao gerenciador
      </a>
    </header>

    {{-- CARD PRINCIPAL --}}
    <form method="POST"
          action="{{ route('admin.questions.store') }}"
          enctype="multipart/form-data"
          class="create-card">
      @csrf

      {{-- GRID SUPERIOR --}}
      <div class="grid-fields">

        {{-- MATÉRIA --}}
        <div class="form-group">
          <label class="form-label">Matéria</label>
          <select name="subject_id" id="subject_id" class="select" required>
            <option value="">Selecione</option>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- TÓPICOS --}}
        <div class="form-group">
          <label class="form-label">Tópicos</label>
          <select name="topics[]" id="topics" class="select" multiple>
            @foreach ($topics as $topic)
              <option value="{{ $topic->id }}" data-subject-id="{{ $topic->subject_id }}">
                {{ $topic->name }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- ANO --}}
        <div class="form-group">
          <label class="form-label">Ano</label>
          <input type="number" name="year" class="input" placeholder="2023">
        </div>

        {{-- ORIGEM --}}
        <div class="form-group">
          <label class="form-label">Origem</label>
          <input type="text" name="source" class="input" placeholder="IFSul">
        </div>
      </div>

      {{-- IMAGEM + ENUNCIADO --}}
      <div class="grid-img-enun">

        {{-- UPLOAD IMAGEM --}}
        <div class="form-group">
          <label class="form-label">Imagem da questão</label>
          <input type="file" name="image" accept="image/*" class="file">
          <p class="field-hint">Formatos aceitos: JPG/PNG • até 4MB</p>
        </div>

        {{-- ENUNCIADO --}}
        <div class="form-group">
          <label class="form-label">Enunciado (opcional)</label>
          <textarea name="statement" class="textarea" rows="5"></textarea>
        </div>
      </div>


      {{-- ALTERNATIVAS --}}
      <div class="form-group">
        <label class="form-label">Alternativas</label>

        <div class="alt-grid-premium">
          @foreach(['A','B','C','D','E'] as $opt)
            <div class="alt-card">
              <span class="alt-letter">{{ $opt }}</span>
              <input type="text" name="{{ $opt }}" class="input flex1" required>
            </div>
          @endforeach

          {{-- CORRETA --}}
          <div class="alt-card correct">
            <span class="alt-letter">✔</span>
            <select name="correct_label" class="select flex1" required>
              @foreach(['A','B','C','D','E'] as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- BOTÕES --}}
      <div class="form-actions">
        <button class="btn-primary-lg">Salvar questão</button>
        <a href="{{ route('exams.create') }}" class="btn-ghost-lg">Criar simulado</a>
      </div>

    </form>
  </div>
</x-app-layout>
