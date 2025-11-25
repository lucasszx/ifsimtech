<x-app-layout>
  <div class="page-simulado premium-simulado">
    
    {{-- HERO / HEADER --}}
    <header class="simulado-hero">
      <div class="simulado-hero-text">
        <span class="badge-hero">
          @if(isset($presetTopic))
            Simulado focado
          @else
            Simulado personalizado
          @endif
        </span>

        <h1>Novo Simulado</h1>

        @if(isset($presetTopic))
          <p>
            Você está criando um simulado <strong>focado</strong> no tópico 
            <strong>{{ $presetTopic->name }}</strong>. Ideal para reforçar pontos fracos.
          </p>
        @else
          <p>
            Monte seu simulado escolhendo a quantidade de questões e as matérias que deseja praticar.
          </p>
        @endif
      </div>

      <div class="simulado-hero-side">
        <div class="hero-stat">
          <span class="hero-stat-label">Recomendado</span>
          <span class="hero-stat-value">10–20</span>
          <span class="hero-stat-helper">questões por sessão</span>
        </div>

        @if(isset($presetTopic))
          <div class="hero-topic">
            <span class="hero-topic-label">Tópico focado</span>
            <span class="hero-topic-name">{{ $presetTopic->name }}</span>
            <span class="hero-topic-count">
              {{ count($questionIds) }} questões disponíveis
            </span>
          </div>
        @endif
      </div>
    </header>


    {{-- LAYOUT PRINCIPAL: FORM + RESUMO --}}
    <main class="simulado-layout">
      <section class="simulado-card">
        <form method="POST" action="{{ route('exams.store') }}" class="simulado-form">
          @csrf

          @if(isset($presetTopic))
            <input type="hidden" name="topic_id" value="{{ $presetTopic->id }}">
          @endif

          {{-- Título --}}
          <div class="form-group">
            <label for="title">Título do simulado <span class="label-optional">(opcional)</span></label>
            <input id="title"
                   name="title"
                   type="text"
                   placeholder="Simulado IFSul – Revisão geral"
                   class="input">
            <p class="field-hint">
              Dica: use um nome que ajude a lembrar o foco deste estudo.
            </p>
          </div>

          {{-- Número de questões --}}
          <div class="form-group">
            <label for="questions_count">Número de questões</label>
            <input id="questions_count"
                   name="questions_count"
                   type="number"
                   min="5"
                   max="80"
                   value="10"
                   required
                   class="input">
            <p class="field-hint">
              Escolha entre <strong>5 e 80</strong> questões. Recomendamos 10 a 20 para um estudo rápido.
            </p>
          </div>

          {{-- Matérias (apenas se não for focado em tópico) --}}
          @unless(isset($presetTopic))
            <div class="form-group">
              <label>Matérias</label>
              <p class="field-hint">
                Selecione pelo menos uma matéria para montar seu simulado.
              </p>

              <div class="checkbox-grid">
                @foreach($subjects as $s)
                  <label class="checkbox">
                    <input type="checkbox"
                           name="subjects[]"
                           value="{{ $s->id }}">
                    <span>{{ $s->name }}</span>
                  </label>
                @endforeach
              </div>
            </div>
          @endunless

          {{-- Ações --}}
          <div class="form-actions">
            <button class="btn btn-primary">
              @if(isset($presetTopic))
                Gerar simulado focado
              @else
                Gerar simulado
              @endif
            </button>

            <a href="{{ route('results.history') }}" class="btn btn-ghost">
              Ver histórico
            </a>
          </div>
        </form>
      </section>

      {{-- COLUNA LATERAL: RESUMO E DICAS --}}
      <aside class="simulado-sidebar">
        <div class="sidebar-card">
          <h2>Resumo do simulado</h2>
          <ul class="sidebar-list">
            <li>
              <span class="sidebar-dot"></span>
              <span>
                Defina um <strong>número de questões</strong> que caiba no seu tempo disponível.
              </span>
            </li>
            <li>
              <span class="sidebar-dot"></span>
              <span>
                Priorize matérias onde você teve <strong>desempenho mais baixo</strong> nos últimos simulados.
              </span>
            </li>
            <li>
              <span class="sidebar-dot"></span>
              <span>
                Depois de gerar, você poderá acompanhar seu <strong>desempenho por tópico</strong>.
              </span>
            </li>
          </ul>
        </div>

        <div class="sidebar-card secondary">
          <h3>Dica rápida</h3>
          @if(isset($presetTopic))
            <p>
              Use este simulado focado para revisar erros recentes no tópico
              <strong>{{ $presetTopic->name }}</strong>. Tente atingir pelo menos <strong>70% de acerto</strong>.
            </p>
          @else
            <p>
              Combine uma matéria em que você vai bem com outra em que tem dificuldade. Isso ajuda a manter o ritmo sem sobrecarregar.
            </p>
          @endif
        </div>
      </aside>
    </main>

  </div>
</x-app-layout>