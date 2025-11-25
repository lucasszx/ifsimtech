<x-app-layout>
  <div class="page-simulado">
    <div class="simulado-header">
      <h1>Novo Simulado</h1>

      @if(isset($presetTopic))
        <p>Gerando simulado focado no tópico selecionado.</p>
      @else
        <p>Monte seu simulado personalizado escolhendo matérias e quantidade de questões.</p>
      @endif
    </div>


    {{-- ALERTA DE SIMULADO FOCADO --}}
    @if(isset($presetTopic))
      <div class="alert-info-focus">
        <strong>Simulado focado no tópico:</strong>  
        <span class="topic-highlight">{{ $presetTopic->name }}</span>
        <br>
        <small>Total de questões disponíveis: {{ count($questionIds) }}</small>

        <input type="hidden" name="topic_id" value="{{ $presetTopic->id }}">
      </div>
    @endif


    <div class="simulado-card">
      <form method="POST" action="{{ route('exams.store') }}" class="simulado-form">
        @csrf

        {{-- Título --}}
        <div class="form-group">
          <label for="title">Título (opcional)</label>
          <input id="title" name="title" type="text"
                 placeholder="Simulado IFSul"
                 class="input">
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
        </div>


        {{-- Se for simulado focado → esconder matérias --}}
        @if(!isset($presetTopic))
          <div class="form-group">
            <label>Matérias</label>
            <div class="checkbox-grid">
              @foreach($subjects as $s)
                <label class="checkbox">
                  <input type="checkbox"
                         name="subjects[]"
                         value="{{ $s->id }}"
                         @if($loop->first) required @endif>
                  <span>{{ $s->name }}</span>
                </label>
              @endforeach
            </div>
          </div>
        @endif


        {{-- Botões --}}
        <div class="form-actions">
          <button class="btn btn-primary">
            @if(isset($presetTopic))
              Gerar simulado focado
            @else
              Gerar
            @endif
          </button>

          <a href="{{ route('results.history') }}" class="btn btn-outline">Histórico</a>
        </div>

      </form>
    </div>
  </div>
</x-app-layout>
