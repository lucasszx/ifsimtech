<x-app-layout>
  <div class="page-result">
    <!-- Header -->
    <div class="result-header">
      <h1>Resultado</h1>
      <p class="muted">
        Nota: <strong>{{ $attempt->score }}</strong> / {{ $attempt->exam->questions_count }}
        • Tempo: {{ gmdate('i:s', $attempt->time_seconds) }}
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
              <tr><th>Matéria</th><th>Total</th><th>Acertos</th><th>%</th></tr>
            </thead>
            <tbody>
            @foreach($bySubject as $name => $data)
              <tr>
                <td>{{ $name }}</td>
                <td>{{ $data['total'] }}</td>
                <td>{{ $data['hits'] }}</td>
                <td>{{ $data['rate'] }}%</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <h2 class="card-title">Tópicos (do pior para o melhor)</h2>
        <div class="table-responsive">
          <table class="table table-emerald">
            <thead>
              <tr><th>Tópico</th><th>Total</th><th>Acertos</th><th>%</th></tr>
            </thead>
            <tbody>
            @foreach($byTopic as $name => $data)
              <tr>
                <td>{{ $name }}</td>
                <td>{{ $data['total'] }}</td>
                <td>{{ $data['hits'] }}</td>
                <td>{{ $data['rate'] }}%</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Sugestões -->
    <div class="card">
      <h2 class="card-title">Sugestões de estudo</h2>
      @if($suggestions->isEmpty())
        <p class="muted">Bom desempenho geral! Refaça questões que errou para consolidar.</p>
      @else
        <ul class="suggestions">
          @foreach($suggestions as $tip)
            <li>
              <span class="chip">Revisar</span>
              <strong>{{ $tip }}</strong>
              <span class="muted">— monte um simulado focado e aumente a dificuldade gradualmente.</span>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
</x-app-layout>
