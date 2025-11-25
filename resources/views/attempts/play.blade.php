<x-app-layout>
  <div class="play-wrapper">

    {{-- Alertas --}}
    @if(session('warning'))
      <div class="alert-warn-premium">
        {{ session('warning') }}
      </div>
    @endif

    {{-- HEADER: progresso + tempo --}}
    <div class="play-header">

      <div class="header-left">
        <div class="progress-chip">
          <span>Questão {{ $i }}/{{ $total }}</span>
          <span class="dot">•</span>
          <span>
            Respondidas
            <strong id="answeredCount">{{ $answeredCount }}</strong>/{{ $total }}
          </span>
        </div>
      </div>

      <div class="header-right">
        <div class="timer-badge">
          <span class="label">Tempo</span>
          <span class="value" id="timer">00:00</span>
        </div>
      </div>
    </div>

    {{-- CARD DA QUESTÃO --}}
    <div class="play-card">

      {{-- meta: matéria + tópico --}}
      <div class="meta-line">
        <span class="subject">{{ $question->subject->name }}</span>

        @if($question->topic)
          <span class="dot">•</span>
          <span class="topic">{{ $question->topic->name }}</span>
        @endif
      </div>

      {{-- imagem --}}
      @if($question->image_path)
        <img src="{{ asset('storage/' . $question->image_path) }}" class="question-img" alt="Imagem da questão">
      @endif

      {{-- enunciado --}}
      @if($question->statement)
        <p class="statement-text">{!! nl2br(e($question->statement)) !!}</p>
      @endif

      {{-- alternativas --}}
      <form id="answerForm"
            method="POST"
            action="{{ route('attempts.answer', $attempt) }}"
            class="options-premium">

        @csrf
        <input type="hidden" name="question_id" value="{{ $question->id }}">
        <input type="hidden" name="redirect_i" id="redirect_i" value="{{ $i }}">

        @foreach($question->options->sortBy('label') as $opt)
          <label class="option-item">
            <input type="radio"
                   name="selected_label"
                   value="{{ $opt->label }}"
                   @checked(optional($answer)->selected_label === $opt->label)>

            <span class="option-body">
              <span class="opt-label">{{ $opt->label }}.</span>
              {{ $opt->text }}
            </span>
          </label>
        @endforeach

      </form>

    </div>

    {{-- Navegação --}}
    @php
      $prev = max(1, $i - 1);
      $next = min($total, $i + 1);
    @endphp

    <div class="play-nav">
      <button class="btn-outline-premium"
              @disabled($i === 1)
              onclick="goTo({{ $prev }})">
        ⟵ Anterior
      </button>

      <button class="btn-outline-premium"
              @disabled($i === $total)
              onclick="goTo({{ $next }})">
        Próxima ⟶
      </button>
    </div>

    {{-- Ações finais --}}
    <div class="play-actions">

      {{-- Salvar --}}
      <form id="saveForm" method="POST" action="{{ route('attempts.save', $attempt) }}">
        @csrf
        <input type="hidden" name="time_seconds" id="time_seconds_save">
        <button type="submit" class="btn-soft-premium">Salvar e sair</button>
      </form>

      {{-- Finalizar --}}
      <form id="finishForm" method="POST" action="{{ route('attempts.submit', $attempt) }}">
        @csrf
        <input type="hidden" name="time_seconds" id="time_seconds">
        <button type="submit" class="btn-primary-premium">Finalizar</button>
      </form>

    </div>

  </div>

  {{-- SCRIPT ORIGINAL — INALTERADO --}}
  <script>
      const attemptId = {{ $attempt->id }};
      const serverElapsed = {{ (int) ($attempt->time_seconds ?? 0) }};
      const KEY_BASE = `attempt:${attemptId}`;
      const KEY_START = `${KEY_BASE}:startAt`;
      const KEY_ELAPSED = `${KEY_BASE}:elapsedSecs`;
      const KEY_PAUSED = `${KEY_BASE}:paused`;
      const timerEl = document.getElementById('timer');
      const timeFieldFin  = document.getElementById('time_seconds');
      const timeFieldSave = document.getElementById('time_seconds_save');

      let elapsed = parseInt(localStorage.getItem(KEY_ELAPSED) || '0', 10);
      if (serverElapsed > elapsed) elapsed = serverElapsed;
      let paused  = localStorage.getItem(KEY_PAUSED) === '1';
      let startAt = localStorage.getItem(KEY_START);
      if (!paused && !startAt) {
          startAt = Date.now().toString();
          localStorage.setItem(KEY_START, startAt);
      }

      function computeElapsedNow(){
          const base = elapsed;
          const start = parseInt(localStorage.getItem(KEY_START) || '0', 10);
          const isPaused = localStorage.getItem(KEY_PAUSED) === '1';
          if (isPaused || !start) return base;
          const delta = Math.floor((Date.now() - start)/1000);
          return base + Math.max(0, delta);
      }

      function render(){
          const total = computeElapsedNow();
          const m = String(Math.floor(total/60)).padStart(2,'0');
          const s = String(total%60).padStart(2,'0');
          timerEl.textContent = `${m}:${s}`;
          timeFieldFin.value = total;
          timeFieldSave.value = total;
      }

      render();
      const iv = setInterval(render, 1000);

      function goTo(idx){
          const form = document.getElementById('answerForm');
          document.getElementById('redirect_i').value = idx;
          const checked = form.querySelector('input[name="selected_label"]:checked');

          if (checked) form.submit();
          else {
              const url = new URL(window.location.href);
              url.searchParams.set('i', idx);
              window.location.href = url.toString();
          }
      }

      document.getElementById('finishForm').addEventListener('submit', async (e) => {
          e.preventDefault();

          const answerForm = document.getElementById('answerForm');
          const checked = answerForm.querySelector('input[name="selected_label"]:checked');

          if (checked) {
              const formData = new FormData(answerForm);

              try {
                  await fetch(answerForm.action, {
                      method: "POST",
                      body: formData,
                      headers: { "X-Requested-With": "XMLHttpRequest" }
                  });
              } catch {
                  alert("Erro ao salvar última resposta.");
                  return;
              }
          }

          clearInterval(iv);
          localStorage.removeItem(KEY_START);
          localStorage.removeItem(KEY_ELAPSED);
          localStorage.removeItem(KEY_PAUSED);

          e.target.submit();
      });

      document.getElementById('saveForm').addEventListener('submit', () => {
          const total = computeElapsedNow();
          localStorage.setItem(KEY_ELAPSED, String(total));
          localStorage.setItem(KEY_PAUSED, '1');
          localStorage.removeItem(KEY_START);
      });

      if (paused) {
          localStorage.setItem(KEY_START, Date.now().toString());
          localStorage.setItem(KEY_PAUSED, '0');
      }

      document.querySelectorAll('input[name="selected_label"]').forEach(radio => {
          radio.addEventListener('change', () => {
              const countEl = document.getElementById('answeredCount');
              if (countEl) countEl.textContent = String(parseInt(countEl.textContent) + 1);
          });
      });
  </script>

</x-app-layout>