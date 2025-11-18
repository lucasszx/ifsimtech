<x-app-layout>
  <div class="page-attempt max-w-3xl mx-auto">

    {{-- alertas --}}
    @if(session('warning'))
      <div class="alert-warn">
        {{ session('warning') }}
      </div>
    @endif

    {{-- header: progresso + tempo --}}
    <div class="attempt-header">
      <div class="chip">
        Q{{ $i }}/{{ $total }}
        <span class="sep">•</span>
        Respondidas: {{ $answeredCount }}/{{ $total }}
      </div>

      <div class="timer-wrap">
        <span class="timer-label">Tempo</span>
        <span class="timer-value" id="timer">00:00</span>
      </div>
    </div>

    {{-- questão atual --}}
    <div class="q-card">
      <div class="q-meta">
        {{ $question->subject->name }} @if($question->topic) • {{ $question->topic->name }} @endif
      </div>

      @if($question->image_path)
        <img src="{{ asset('storage/'.$question->image_path) }}" alt="Questão" class="q-image">
      @endif

      @if($question->statement)
        <p class="q-statement">{!! nl2br(e($question->statement)) !!}</p>
      @endif

      <form id="answerForm" method="POST" action="{{ route('attempts.answer', $attempt) }}" class="options">
        @csrf
        <input type="hidden" name="question_id" value="{{ $question->id }}">
        <input type="hidden" name="redirect_i" id="redirect_i" value="{{ $i }}">

        @foreach($question->options->sortBy('label') as $opt)
          <label class="option">
            <input type="radio" name="selected_label" value="{{ $opt->label }}"
                   @checked(optional($answer)->selected_label === $opt->label)>
            <span class="option-text">
              <strong class="option-label">{{ $opt->label }}.</strong> {{ $opt->text }}
            </span>
          </label>
        @endforeach
      </form>
    </div>

    {{-- navegação entre questões --}}
    @php $prev = max(1, $i - 1); $next = min($total, $i + 1); @endphp
    <div class="pager">
      <button class="btn btn-outline" @disabled($i===1) onclick="goTo({{ $prev }})">⟵ Anterior</button>
      <button class="btn btn-outline" @disabled($i===$total) onclick="goTo({{ $next }})">Próxima ⟶</button>
    </div>

    {{-- ações finais --}}
    <div class="final-actions">
      <form id="saveForm" method="POST" action="{{ route('attempts.save', $attempt) }}">
        @csrf
        <input type="hidden" name="time_seconds" id="time_seconds_save" value="0">
        <button class="btn btn-soft">Salvar e sair</button>
      </form>

      <form id="finishForm" method="POST" action="{{ route('attempts.submit', $attempt) }}">
        @csrf
        <input type="hidden" name="time_seconds" id="time_seconds" value="0">
        <button class="btn btn-primary">Finalizar</button>
      </form>
    </div>
  </div>

  {{-- teu script original permanece igual --}}
  <script>
    // ===== Config =====
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
    if (!paused && !startAt) { startAt = Date.now().toString(); localStorage.setItem(KEY_START, startAt); }

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
    render(); const iv = setInterval(render, 1000);

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
    
    document.getElementById('finishForm').addEventListener('submit', () => {
      clearInterval(iv);
      localStorage.removeItem(KEY_START);
      localStorage.removeItem(KEY_ELAPSED);
      localStorage.removeItem(KEY_PAUSED);
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
  </script>
</x-app-layout>
