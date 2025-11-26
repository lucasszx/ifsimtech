<x-app-layout>
  <div class="page-result">

    <!-- ======================================================
         HEADER
    ======================================================= -->
    <div class="result-header">
      <h1>Resultado</h1>

      <p class="muted">
        Nota: <strong>{{ $attempt->score }}/{{ $attempt->exam->questions_count }}</strong>
        • Tempo: <strong>{{ gmdate('i:s', $attempt->time_seconds) }}</strong>
      </p>

      <div class="result-actions">
        <a href="{{ route('exams.create') }}" class="btn btn-primary">
          Novo simulado
        </a>

        <form method="POST" action="{{ route('exams.store') }}" class="inline-form">
          @csrf
          <input type="hidden" name="title" value="Refazer {{ $attempt->exam->title }}">
          <input type="hidden" name="questions_count" value="{{ $attempt->exam->questions_count }}">

          @foreach(($attempt->exam->filters['subjects'] ?? []) as $sid)
            <input type="hidden" name="subjects[]" value="{{ $sid }}">
          @endforeach

          <button class="btn btn-outline">Refazer com mesmos filtros</button>
        </form>

        <a href="{{ route('results.review', $attempt->id) }}" class="btn btn-outline">
            Revisar simulado
        </a>

      </div>
    </div>


    <!-- ======================================================
         GRID: MATÉRIA + TÓPICOS (LADO A LADO NO DESKTOP)
    ======================================================= -->
    <div class="result-grid">

      <!-- ===========================
           CARD: TABELA POR MATÉRIA
      ============================ -->
      <div class="card">
        <h2 class="card-title">Desempenho por matéria</h2>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Matéria</th>
                <th>Total</th>
                <th>Acertos</th>
                <th>%</th>
                <th>Progresso</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bySubject as $data)
                <tr class="subject-filter" data-subject="{{ $data['subject'] }}">
                  <td>{{ $data['subject'] }}</td>
                  <td>{{ $data['total'] }}</td>
                  <td>{{ $data['hits'] }}</td>
                  <td>{{ $data['rate'] }}%</td>
                  <td>
                    <div class="progress">
                      <div class="bar" style="width: {{ $data['rate'] }}%"></div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- ===========================
           CARD: TÓPICOS PRIORITÁRIOS
      ============================ -->
      <div class="card">
          <h2 class="card-title">Tópicos presentes no simulado</h2>

          @if(empty($byTopic))
              <p class="muted">Ainda não há tópicos suficientes para análise.</p>
          @else
              <div class="card-topics-scroll">
                  <ul class="topic-list">
                      @foreach($byTopic as $topic)
                          <li class="topic-item"
                              data-topic-id="{{ $topic['id'] }}"
                              data-subject="{{ $topic['subject'] }}"
                          >

                              <div class="topic-header">
                                  <span class="topic-name">{{ $topic['name'] }}</span>

                                  <div class="topic-actions">
                                      <button type="button"
                                              class="topic-see-now"
                                              data-topic-id="{{ $topic['id'] }}">
                                          Ver neste simulado
                                      </button>
                                  </div>
                              </div>

                              <span class="topic-score">
                                  {{ $topic['hits'] }}/{{ $topic['total'] }}
                                  ({{ $topic['rate'] }}%)
                              </span>

                              <div class="progress">
                                  <div class="bar" style="width: {{ $topic['rate'] }}%"></div>
                              </div>

                          </li>
                      @endforeach
                  </ul>
              </div>
          @endif
      </div>

    </div> <!-- /result-grid -->


    <!-- ======================================================
         SUGESTÕES PERSONALIZADAS (EMBAIXO)
    ======================================================= -->
    <div class="card">
      <h2 class="card-title">Sugestões de estudo de acordo com seu desempenho geral</h2>

      @if($suggestions_global->isEmpty())
        <p class="muted">Nenhuma sugestão gerada.</p>

      @else
        <ul class="suggestions">
          @foreach($suggestions_global as $item)

            @php
              $rate  = $item['rate'];
              $level = $item['level'];
              $class = $rate < 40 ? 'critico' : ($rate < 70 ? 'atencao' : 'ok');
            @endphp

            <li>
              <span class="chip chip-{{ $class }}">{{ $level }}</span>

              <div style="width:100%;">
                <strong>{{ $item['topic'] }} — {{ $rate }}%</strong><br>
                <span>{{ $item['message'] }}</span>

                <div class="progress" style="margin-top:.6rem;">
                  <div class="bar 
                      @if($rate<40) bar-crit 
                      @elseif($rate<70) bar-warn 
                      @else bar-ok 
                      @endif"
                    style="width: {{ $rate }}%;">
                  </div>
                </div>

                <div style="margin-top:.75rem; display:flex; gap:.6rem;">
                  <a href="{{ route('results.topic', $item['id']) }}" 
                    class="topic-history-btn">
                    Histórico
                  </a>
                </div>

              </div>
            </li>

          @endforeach
        </ul>
      @endif

      <a href="{{ route('study-goals.index') }}"
         class="btn btn-outline"
         style="margin-top:1rem;">
        Ver minhas metas
      </a>
    </div>


    <!-- ======================================================
         MODAL
    ======================================================= -->
    <div id="topic-modal" class="modal hidden">
      <div class="modal-content">
        <span class="modal-close">&times;</span>
        <br>
        <h2 id="modal-title">Tópico</h2>
        <br>
        <div id="modal-body" class="modal-body"></div>
      </div>
    </div>

    <!-- ======================================================
         DESEMPENHO GERAL POR MATÉRIA
    ======================================================= -->
    <div class="card">
      <h2 class="card-title">Desempenho geral por matéria</h2>

      @if($global_subjects->isEmpty())
        <p class="muted">Ainda não há dados suficientes.</p>

      @else
        <ul class="suggestions">
          @foreach($global_subjects as $sub)
            @php
              $rate = $sub->rate;

              // define faixa do nível
              $level = $rate < 40 ? 'Crítico' : ($rate < 70 ? 'Atenção' : 'OK');

              $class = $rate < 40
                ? 'critico'
                : ($rate < 70 ? 'atencao' : 'ok');
            @endphp

            <li>
              <span class="chip chip-{{ $class }}">{{ $level }}</span>

              <div style="width:100%;">
                <strong>{{ $sub->name }} — {{ $rate }}%</strong><br>
                <span>
                  Total: {{ $sub->total }} questões • Acertos: {{ $sub->hits }}
                </span>

                <div class="progress" style="margin-top:.6rem;">
                  <div class="bar @if($rate<40) bar-crit @elseif($rate<70) bar-warn @else bar-ok @endif"
                      style="width: {{ $rate }}%;">
                  </div>
                </div>
              </div>
            </li>

          @endforeach
        </ul>
      @endif
    </div>


  </div>
</x-app-layout>

{{-- =========================================================
     JS DA TELA DE RESULTADOS
========================================================= --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {

    /* ---------------------------
      FILTRO POR NÍVEL (chips)
    --------------------------- */
    const filterButtons = document.querySelectorAll('.filter-btn');
    const topicItems    = document.querySelectorAll('.topic-item');

    filterButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        // marca botão ativo
        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.dataset.filter;

        topicItems.forEach(item => {
          const level = item.dataset.level;
          item.style.display =
            (filter === 'Todos' || filter === level) ? 'flex' : 'none';
        });
      });
    });


    /* ---------------------------
      FILTRO POR MATÉRIA (clique na tabela)
    --------------------------- */
    const subjectRows = document.querySelectorAll('.subject-filter');

    subjectRows.forEach(row => {
      row.addEventListener('click', () => {
        const subject = row.dataset.subject;

        // volta filtro para "Todos"
        const allBtn = document.querySelector('.filter-btn[data-filter="Todos"]');
        if (allBtn) {
          filterButtons.forEach(b => b.classList.remove('active'));
          allBtn.classList.add('active');
        }

        topicItems.forEach(item => {
          item.style.display =
            !subject || item.dataset.subject === subject ? 'flex' : 'none';
        });

        // scroll suave até o card de tópicos
        const topicsCard = document.querySelector('.card h2.card-title:nth-of-type(2)');
      });
    });


    /* ---------------------------
      MODAL — VER QUESTÕES DO TÓPICO
    --------------------------- */
    const modal      = document.getElementById('topic-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody  = document.getElementById('modal-body');
    const modalClose = document.querySelector('.modal-close');

    function closeModal() {
      modal.classList.add('hidden');
      modalBody.innerHTML = '';
    }

    if (modalClose) {
      modalClose.addEventListener('click', closeModal);
    }

    // fecha no ESC
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        closeModal();
      }
    });

    // abre modal ao clicar em "Ver neste simulado"
    document.querySelectorAll('.topic-see-now').forEach(btn => {
      btn.addEventListener('click', () => {
        const topicId = btn.dataset.topicId;

        modal.classList.remove('hidden');
        modalTitle.textContent = 'Carregando...';
        modalBody.innerHTML    = '<p class="muted">Buscando questões erradas...</p>';

        fetch(`/results/{{ $attempt->id }}/topic/${topicId}/errors`)
          .then(res => res.json())
          .then(list => {
            if (!list.length) {
              modalTitle.textContent = 'Sem erros neste tópico';
              modalBody.innerHTML = '<p class="muted">Você não errou questões deste tópico neste simulado.</p>';
              return;
            }

            modalTitle.textContent = 'Questões erradas neste simulado';

            modalBody.innerHTML = list.map(q => `
              <div class="question-card">
                <p><strong>Enunciado:</strong></p>
                <p>${q.statement ?? ''}</p>

                ${q.image ? `
                  <div class="question-image" style="margin:.75rem 0;">
                    <img src="/storage/${q.image}" style="max-width:100%;border-radius:.5rem;">
                  </div>
                ` : ''}

                <div class="options-block" style="margin-top:.5rem;">
                  ${q.options.map(opt => `
                    <div class="option-line
                      ${opt.is_correct ? 'option-correct' : ''}
                      ${opt.label === q.selected ? 'option-selected' : ''}">
                      <strong>${opt.label})</strong> ${opt.text}
                    </div>
                  `).join('')}
                </div>
              </div>
            `).join('');
          })
          .catch(() => {
            modalTitle.textContent = 'Erro ao carregar';
            modalBody.innerHTML = '<p class="muted">Não foi possível carregar as questões agora.</p>';
          });
      });
    });

  });
</script>