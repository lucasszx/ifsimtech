<x-app-layout>
  <div class="admin-page premium-edit">

    {{-- HEADER / HERO --}}
    <header class="edit-hero">
      <div>
        <h1>Editar questão #{{ $question->id }}</h1>
        <p>Modifique enunciado, alternativas, imagem, matéria e tópicos.</p>
      </div>

      <a href="{{ route('admin.questions.index') }}" class="btn-hero-outline">
        Voltar
      </a>
    </header>

    {{-- CARD PRINCIPAL --}}
    <form method="POST" action="{{ route('admin.questions.update', $question) }}" 
          enctype="multipart/form-data"
          class="edit-card">
      @csrf @method('PUT')

      {{-- GRID SUPERIOR --}}
      <div class="grid-fields">

        <div class="form-group">
          <label class="form-label">Matéria</label>
          <select id="subject_id" name="subject_id" class="select">
            @foreach($subjects as $s)
              <option value="{{ $s->id }}" @selected($question->subject_id == $s->id)>
                {{ $s->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tópicos</label>
          <select id="topics" name="topics[]" multiple class="select">
            @foreach($topics as $topic)
              <option value="{{ $topic->id }}"
                      data-subject-id="{{ $topic->subject_id }}"
                      @if($question->topics->contains($topic->id)) selected @endif>
                {{ $topic->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Ano</label>
          <input type="number" name="year" class="input" value="{{ $question->year }}">
        </div>

        <div class="form-group">
          <label class="form-label">Origem</label>
          <input type="text" name="source" class="input" value="{{ $question->source }}">
        </div>
      </div>


      {{-- GRID IMAGEM + ENUNCIADO --}}
      <div class="grid-img-enun">

        {{-- IMAGEM ATUAL --}}
        <div class="form-group">
          <label class="form-label">Imagem atual</label>
          @if($question->image_path)
            <img src="{{ asset('storage/' . $question->image_path) }}" class="img-preview">
          @else
            <p class="muted">Nenhuma imagem enviada.</p>
          @endif
        </div>

        {{-- UPLOAD + ENUNCIADO --}}
        <div class="form-column">
          <div class="form-group">
            <label class="form-label">Trocar imagem (opcional)</label>
            <input type="file" name="image" class="file" accept="image/*">
          </div>

          <div class="form-group">
            <label class="form-label">Enunciado</label>
            <textarea name="statement" class="textarea enunciado" rows="5">{{ $question->statement }}</textarea>
          </div>
        </div>
      </div>


      {{-- ALTERNATIVAS --}}
      <div class="form-group">
        <label class="form-label">Alternativas</label>

        @php $opts = $question->options->keyBy('label'); @endphp

        <div class="alt-grid-premium">
          @foreach(['A','B','C','D','E'] as $opt)
            <div class="alt-card">
              <span class="alt-letter">{{ $opt }}</span>
              <input type="text" class="input" name="{{ $opt }}" 
                     value="{{ $opts[$opt]->text ?? '' }}">
            </div>
          @endforeach

          {{-- Alternativa correta --}}
          <div class="alt-card correct">
            <span class="alt-letter">✔</span>
            <select name="correct_label" class="select flex1">
              @foreach(['A','B','C','D','E'] as $opt)
                <option value="{{ $opt }}"
                  @selected(optional($question->options->firstWhere('is_correct', true))->label == $opt)>
                  {{ $opt }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- AÇÕES --}}
      <div class="form-actions">
        <button class="btn-primary-lg">Salvar alterações</button>

        <a href="{{ route('admin.questions.index') }}" class="btn-ghost-lg">
          Cancelar
        </a>
      </div>

    </form>
  </div>
</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const $subject = $('#subject_id');
    const $topics  = $('#topics');

    // Guarda todos os tópicos originais (precisa CLONAR)
    const allOptions = $topics.find('option').clone();

    // Inicia Select2
    $topics.select2({
        placeholder: "Selecione os tópicos",
        width: '100%'
    });

    function filtrarTopicos() {
        const subjectId = String($subject.val());

        // Salva IDs que já estavam selecionados
        const selecionados = $topics.val() || [];

        // Limpa a lista
        $topics.empty();

        // Adiciona apenas os tópicos da matéria selecionada
        allOptions.each(function () {
            const topicSubject = String($(this).data('subject-id'));

            if (topicSubject === subjectId) {
                // Mantém selecionados que já pertencem a esta matéria
                if (selecionados.includes($(this).val())) {
                    $(this).prop('selected', true);
                }
                $topics.append($(this).clone());
            }
        });

        // Atualiza select2
        $topics.trigger('change.select2');
    }

    // Filtra quando muda matéria
    $subject.on('change', filtrarTopicos);

    // Filtra já ao carregar a página
    filtrarTopicos();
});
</script>
