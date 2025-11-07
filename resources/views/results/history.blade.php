<x-app-layout>
  <div class="page-history">
    <div class="history-header">
      <h1>Histórico</h1>
    </div>

    @if($attempts->isEmpty())
      <div class="history-card empty-state">
        <p>Você ainda não tem simulados. <a href="{{ route('exams.create') }}">Criar agora</a></p>
      </div>
    @else
      <div class="history-card">
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
                <td>{{ $a->exam->title }}</td>

                <td>
                  @if($a->status === 'submitted')
                    <span class="status done">Finalizado</span>
                  @else
                    <span class="status pending">Em andamento</span>
                  @endif
                </td>

                <td>{{ $answered }}/{{ $total }} ({{ $progress }}%)</td>

                <td>
                  @if($a->status === 'submitted')
                    {{ $a->score }}/{{ $total }}
                  @else
                    —
                  @endif
                </td>

                <td class="text-right">
                  {{-- Ver ou Continuar --}}
                  @if($a->status === 'submitted')
                    <a href="{{ route('results.show', $a) }}" class="table-link me-2">Ver</a>
                  @else
                    <a href="{{ route('attempts.play', [$a, 'i' => null]) }}" class="table-link me-2">Continuar</a>
                  @endif

                  {{-- Excluir --}}
                  <form action="{{ route('results.destroy', $a) }}"
                        method="POST"
                        class="inline-block"
                        onsubmit="return confirm('Tem certeza que deseja excluir este simulado?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="table-link">Excluir</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="pagination-wrap">
        {{ $attempts->links() }}
      </div>
    @endif
  </div>
</x-app-layout>
