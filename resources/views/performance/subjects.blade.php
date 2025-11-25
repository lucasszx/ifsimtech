<x-app-layout>
  <div class="page-result">
    <div class="result-header">
      <h1>Desempenho por Disciplina</h1>

      <p class="muted">Baseado em todos os simulados já feitos.</p>
    </div>

    <div class="card">
      <h2 class="card-title">Resumo Geral</h2>

      <table class="table">
        <thead>
          <tr>
            <th>Disciplina</th>
            <th>Total</th>
            <th>Acertos</th>
            <th>Desempenho</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          @foreach($statsBySubject as $s)
            <tr>
              <td>{{ $s->name }}</td>
              <td>{{ $s->total }}</td>
              <td>{{ $s->hits }}</td>
              <td>{{ $s->rate }}%</td>

              <td style="width:200px;">
                <div class="progress">
                  @php
                    $color = $s->rate >= 70 ? 'ok' : ($s->rate >= 40 ? 'warn' : 'crit');
                  @endphp

                  <div class="bar bar-{{ $color }}" style="width: {{ $s->rate }}%"></div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</x-app-layout>
