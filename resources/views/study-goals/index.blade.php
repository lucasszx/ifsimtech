<x-app-layout>
  <div class="page-result">

    <div class="result-header">
      <h1>Minhas metas de estudo</h1>
      <p class="muted">Geradas automaticamente conforme seu desempenho.</p>
    </div>

    <div class="card">
      @if($goals->isEmpty())
        <p class="muted">Nenhuma meta gerada ainda.</p>

      @else
        <ul class="suggestions">
          @foreach($goals as $goal)
            <li>

              {{-- etiqueta --}}
              <span class="chip {{ $goal->status === 'done' ? 'chip-ok' : 'chip-atencao' }}">
                {{ $goal->status === 'done' ? 'Concluída' : 'Pendente' }}
              </span>

              <div>
                <strong>{{ $goal->title }}</strong><br>
                <small>{{ $goal->description }}</small><br>

                @if($goal->due_date)
                  <small>Prazo: {{ \Carbon\Carbon::parse($goal->due_date)->format('d/m/Y') }}</small><br>
                @endif

                @if($goal->status !== 'done')
                  <form action="{{ route('study-goals.complete', $goal) }}"
                        method="POST"
                        style="margin-top:.4rem;">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-primary btn-sm">Marcar como concluída</button>
                  </form>
                @endif
              </div>

            </li>
          @endforeach
        </ul>
      @endif
    </div>

  </div>
</x-app-layout>
