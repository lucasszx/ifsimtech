<x-app-layout>
  <div class="page-history premium-history">

    {{-- HERO / HEADER --}}
    <header class="history-hero">
      <div class="hero-text">
        <h1>Histórico de Simulados</h1>
        <p>Acompanhe seus simulados realizados, continue onde parou e revise os resultados.</p>
      </div>

      <div class="hero-actions">
        <a href="{{ route('exams.create') }}" class="btn-hero">
          Criar novo simulado
        </a>
      </div>
    </header>

    {{-- EMPTY STATE --}}
    @if($attempts->isEmpty())
      <div class="empty-state-wrap">
        <div class="empty-card">
          <img src="/assets/empty-history.svg" alt="Nenhum simulado" class="empty-img">

          <h3>Nenhum simulado encontrado</h3>
          <p>
            Quando você criar simulados, eles aparecerão aqui para consulta.
          </p>

          <a href="{{ route('exams.create') }}" class="btn-start">
            Criar primeiro simulado
          </a>
        </div>
      </div>
    @else

      {{-- LAYOUT PRINCIPAL --}}
      <div class="history-layout">

        {{-- TABELA PRINCIPAL --}}
        <section class="history-card">
          <table class="history-table">
            <thead>
              <tr>
                <th>Data</th>
                <th>Título</th>
                <th>Status</th>
                <th>Progresso</th>
                <th>Nota</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              @foreach($attempts as $a)
                @php
                  $total = $a->exam->questions_count;
                  $answered = $a->answers_count ?? $a->answers()->count();
                  $progress = $total ? intval(($answered / $total) * 100) : 0;
                @endphp

                <tr>
                  <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                  <td class="title-col">{{ $a->exam->title }}</td>

                  <td>
                    @if($a->status === 'submitted')
                      <span class="status done">Finalizado</span>
                    @else
                      <span class="status pending">Em andamento</span>
                    @endif
                  </td>

                  <td>
                    <div class="progress-line">
                      <div class="progress-bar" style="width: {{ $progress }}%"></div>
                    </div>
                    <span class="progress-text">{{ $answered }}/{{ $total }}</span>
                  </td>

                  <td>
                    @if($a->status === 'submitted')
                      <span class="score">{{ $a->score }}/{{ $total }}</span>
                    @else
                      <span class="score pending-score">—</span>
                    @endif
                  </td>

                  <td class="actions-col">

                    @if($a->status === 'submitted')
                      <a href="{{ route('results.show', $a) }}" class="table-link">Ver</a>
                    @else
                      <a href="{{ route('attempts.play', [$a, 'i' => null]) }}" class="table-link">Continuar</a>
                    @endif

                    <button type="button"
                            class="table-link danger"
                            onclick="confirmDeleteResult({{ $a->id }})">
                      Excluir
                    </button>

                    <form id="delete-result-{{ $a->id }}"
                          action="{{ route('results.destroy', $a) }}"
                          method="POST"
                          style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                  </td>
                </tr>

              @endforeach
            </tbody>
          </table>
        </section>

        {{-- SIDEBAR — DICAS DE USO (opcional, premium) --}}
        <aside class="history-sidebar">
          <div class="sidebar-card">
            <h2>Resumo geral</h2>
            <p>Tenha uma visão rápida dos seus estudos e acompanhe seu progresso.</p>
          </div>

          <div class="sidebar-card secondary">
            <h3>Dica</h3>
            <p>
              Revise simulados finalizados e observe tópicos onde sua taxa de acerto está abaixo de <strong>70%</strong>.
            </p>
          </div>
        </aside>

      </div>

      {{-- PAGINAÇÃO --}}
      <div class="pagination-wrap">
        {{ $attempts->links('vendor.pagination.arrows-only') }}
      </div>

    @endif

  </div>

</x-app-layout>


<script>
  function confirmDeleteResult(id) {
      Swal.fire({
          title: "Confirmar exclusão",
          text: "Deseja realmente excluir este simulado?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Excluir",
          cancelButtonText: "Cancelar",
          confirmButtonColor: "#10b981",
          cancelButtonColor: "#6b7280",
      }).then((result) => {
          if (result.isConfirmed) {
              document.getElementById('delete-result-' + id).submit();
          }
      });
  }
</script>
