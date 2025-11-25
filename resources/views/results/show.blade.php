<x-app-layout>
  <div class="page-result">
    <!-- Header -->
    <div class="result-header">
      <h1>Resultado</h1>
      <p class="muted">
        Nota: <strong>{{ $attempt->score }}/{{ $attempt->exam->questions_count }}</strong>
        • Tempo: <strong>{{ gmdate('i:s', $attempt->time_seconds) }}</strong>
      </p>

      <div class="result-actions">
        <a href="{{ route('exams.create') }}" class="btn btn-primary">Novo simulado</a>

        <form method="POST" action="{{ route('exams.store') }}" class="inline-form">
          @csrf
          <input type="hidden" name="title" value="Refazer {{ $attempt->exam->title }}">
          <input type="hidden" name="questions_count" value="{{ $attempt->exam->questions_count }}">

          @foreach(($attempt->exam->filters['subjects'] ?? []) as $sid)
            <input type="hidden" name="subjects[]" value="{{ $sid }}">
          @endforeach

          <button class="btn btn-outline">Refazer com mesmos filtros</button>
        </form>

        <a href="{{ route('results.history') }}" class="btn btn-outline">Histórico</a>
      </div>
    </div>

    <!-- Métricas / Tabelas -->
    <div class="result-grid">
      <div class="card">
        <h2 class="card-title">Desempenho por matéria</h2>
        <div class="table-responsive">
          <table class="table table-emerald">
          <thead>
              <tr>
                  <th>Tópicos</th>
                  <th>Total</th>
                  <th>Acertos</th>
                  <th>%</th>
                  <th>Progresso</th>
              </tr>
          </thead>
            <tbody>
            @foreach($bySubject as $name => $data)
              <tr class="subject-filter" data-subject="{{ $name }}">
                <td>{{ $name }}</td>
                <td>{{ $data['total'] }}</td>
                <td>{{ $data['hits'] }}</td>
                <td style="white-space: nowrap;">{{ $data['rate'] }}%</td>
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

      <div class="card">
        <h2 class="card-title">Tópicos prioritários para revisar</h2>

        @if(empty($byTopic))
          <p class="muted">Ainda não há tópicos suficientes para análise detalhada.</p>
        @else
          <div class="topic-filters">
            <button class="filter-btn active" data-filter="Todos">Todos</button>
            <button class="filter-btn" data-filter="Crítico">Crítico</button>
            <button class="filter-btn" data-filter="Atenção">Atenção</button>
            <button class="filter-btn" data-filter="OK">OK</button>
          </div>
          <div class="card-topics-scroll">
            <ul class="topic-list">
              @foreach($byTopic as $topic)
                  @php
                      $rate = $topic['rate'];
                      if ($rate < 40) {
                          $badgeLabel = 'Crítico';
                          $badgeClass = 'topic-badge-danger';
                          $tip = 'Foque em revisar a teoria e refazer várias questões desse conteúdo.';
                      } elseif ($rate < 70) {
                          $badgeLabel = 'Atenção';
                          $badgeClass = 'topic-badge-warn';
                          $tip = 'Vale revisar os pontos em que errou e fazer alguns exercícios extras.';
                      } else {
                          $badgeLabel = 'OK';
                          $badgeClass = 'topic-badge-ok';
                          $tip = 'Você está indo bem aqui, apenas mantenha a prática.';
                      }
                  @endphp

                  <li class="topic-item"
                      data-level="{{ $badgeLabel }}"
                      data-subject="{{ $topic['subject'] }}"
                      data-topic-id="{{ $topic['id'] }}"
                      data-topic-name="{{ $topic['name'] }}"
                  >
                      <div class="topic-header">
                          <span class="topic-name">{{ $topic['name'] }}</span>


                      <div class="topic-actions">
                        {{-- Botão: ver erros deste simulado (modal) --}}
                        <button type="button"
                                class="topic-see-now"
                                data-topic-id="{{ $topic['id'] }}">
                          Ver neste simulado
                        </button>

                        {{-- Link: ver histórico completo de erros --}}
                        <a href="{{ route('results.topic', $topic['id']) }}"
                          class="topic-history-btn"
                          title="Ver histórico completo">
                          ➜
                        </a>
                      </div>
                    </div>

                    <div class="topic-meta">
                      <span class="topic-score">
                        {{ $data['hits'] }} / {{ $data['total'] }} acertos ({{ $rate }}%)
                      </span>
                    </div>

                    <div class="progress">
                      <div class="bar" style="width: {{ $rate }}%"></div>
                    </div>

                    <p class="topic-tip">{{ $tip }}</p>
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

    </div>
    
    <!-- Sugestões -->
    <div class="card">
        <h2 class="card-title">Sugestões de estudo baseados no seu histórico</h2>

        @if($suggestions_global->isEmpty())
            <p class="muted">Seu desempenho geral está sólido! Continue revisando os pontos fracos do simulado.</p>
        @else
            <ul class="suggestions">
                @foreach($suggestions_global as $item)

                    @php
                        // normaliza texto
                        $lvl = strtolower(trim($item['level']));

                        // remove acentos (versão mais completa)
                        $lvl = strtr($lvl, [
                            'á'=>'a','à'=>'a','ã'=>'a','â'=>'a',
                            'é'=>'e','ê'=>'e',
                            'í'=>'i',
                            'ó'=>'o','ô'=>'o','õ'=>'o',
                            'ú'=>'u',
                            'ç'=>'c'
                        ]);

                        // define classe
                        if ($lvl === 'critico') {
                            $normalized = 'critico';
                        } elseif ($lvl === 'atencao') {
                            $normalized = 'atencao';
                        } else {
                            $normalized = 'ok';
                        }

                        $chipClass = "chip chip-{$normalized}";
                    @endphp

                    <li>
                        <span class="{{ $chipClass }}">
                            {{ $item['level'] }}
                        </span>

                        <div>
                            <strong>{{ $item['topic'] }} — {{ $item['rate'] }}%</strong><br>
                            <span>{{ $item['message'] }}</span>
                        </div>
                    </li>

                @endforeach
            </ul>
        @endif
    </div>
  </div>
 
  <!-- Modal de questões erradas neste simulado -->
  <div id="topic-modal" class="modal hidden">
    <div class="modal-content">
      <span class="modal-close">&times;</span>

      <h2 id="modal-title">Tópico</h2>

      <div id="modal-body" class="modal-body">
        Carregando questões...
      </div>
    </div>
  </div>

</x-app-layout>

<script>
  document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {

          // remove active dos outros
          document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');

          const filter = btn.getAttribute('data-filter');

          document.querySelectorAll('.topic-item').forEach(item => {
              const level = item.getAttribute('data-level');

              if (filter === "Todos" || filter === level) {
                  item.style.display = "flex";
              } else {
                  item.style.display = "none";
              }
          });
      });
  });
</script>

<script>
  const topicItems = document.querySelectorAll('.topic-item');

  // === MODAL ===
  const modal      = document.getElementById('topic-modal');
  const modalTitle = document.getElementById('modal-title');
  const modalBody  = document.getElementById('modal-body');
  const modalClose = document.querySelector('.modal-close');

  if (modalClose) {
      modalClose.addEventListener('click', () => modal.classList.add('hidden'));
  }

  // Clique no botão "Ver neste simulado" => abre modal com erros deste attempt
  document.querySelectorAll('.topic-see-now').forEach(btn => {
      btn.addEventListener('click', (event) => {
          event.stopPropagation(); // evita conflitos com outros cliques

          const topicId   = btn.getAttribute('data-topic-id');
          const li        = btn.closest('.topic-item');
          const topicName = li ? li.getAttribute('data-topic-name') : '';
          const attemptId = {{ $attempt->id }};

          modalTitle.textContent = `Questões erradas neste simulado: ${topicName}`;
          modalBody.innerHTML = "Carregando...";

          modal.classList.remove('hidden');

          fetch(`/results/${attemptId}/topic/${topicId}/errors`)
            .then(r => r.json())
            .then(list => {
                if (list.length === 0) {
                    modalBody.innerHTML = `<p class="muted">Nenhum erro neste tópico neste simulado.</p>`;
                    return;
                }
                modalBody.innerHTML = list.map(q => {

                    // monta as alternativas
                    let optionsHtml = q.options.map(opt => {
                        let extra = "";

                        if (opt.is_correct) {
                            extra = "option-correct";  // alternativa correta (verde)
                        }
                        if (opt.label === q.selected) {
                            // se quiser que a errada marcada fique vermelha
                            extra = extra ? extra + " option-selected" : "option-selected";
                        }

                        return `
                          <div class="option-line ${extra}">
                            <strong>${opt.label})</strong> ${opt.text}
                          </div>
                        `;
                    }).join('');

                    return `
                      <div class="question-card">
                        <p><strong>Enunciado:</strong></p>
                        <p>${q.statement ?? ''}</p>

                        ${q.image ? `
                          <div class="question-image">
                            <img src="/storage/${q.image}" alt="Imagem da questão">
                          </div>
                        ` : ''}
                        <div class="options-block">
                          ${optionsHtml}
                        </div>
                      </div>
                    `;
                }).join('');
            })
            .catch(() => {
                modalBody.innerHTML = `<p class="muted">Erro ao carregar as questões. Tente novamente.</p>`;
            });
      });
  });

  // === Filtro por matéria (tabela) ===
  document.querySelectorAll('.subject-filter').forEach(row => {
      row.addEventListener('click', () => {
          const subject = row.getAttribute('data-subject');

          // Resetar filtro de criticidade para "Todos"
          document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
          document.querySelector('[data-filter="Todos"]').classList.add('active');

          // Aplicar filtro por matéria
          topicItems.forEach(item => {
              const itemSubject = item.getAttribute('data-subject');

              if (itemSubject === subject) {
                  item.style.display = "flex";
              } else {
                  item.style.display = "none";
              }
          });
      });
  });

  // Botão "Todos" volta tudo ao normal
  document.querySelector('[data-filter="Todos"]').addEventListener('click', () => {
      topicItems.forEach(item => item.style.display = "flex");
  });
</script>