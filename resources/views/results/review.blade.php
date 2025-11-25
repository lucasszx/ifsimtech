<x-app-layout>
  <div class="page-result">

    <h1 class="page-title">Revisão do Simulado</h1>

    <p class="muted" style="margin-bottom:1.5rem;">
      Você está revisando todas as questões respondidas.
    </p>

    <div class="questions-review">

      @foreach($answers as $ans)
        @php
          $q = $ans->question;
          $selected = $ans->selected_label;
        @endphp

        <div class="review-card">

          <div class="review-header">
            <span class="subject">{{ $q->subject->name ?? '—' }}</span>

            @if($q->topics->count())
              <span class="topic">
                {{ $q->topics->pluck('name')->join(', ') }}
              </span>
            @endif
          </div>

          {{-- enunciado --}}
          <p class="statement">{!! nl2br(e($q->statement)) !!}</p>

          {{-- imagem --}}
          @if($q->image_path)
            <img src="{{ asset('storage/'.$q->image_path) }}" class="review-img">
          @endif

          {{-- alternativas --}}
          <div class="review-options">
            @foreach($q->options->sortBy('label') as $opt)

              @php
                $isCorrect  = $opt->is_correct;
                $isSelected = $opt->label === $selected;

                if ($isCorrect) {
                    // Sempre verde — mesmo quando o aluno acertou
                    $class = 'correct';
                } elseif ($isSelected) {
                    // Alternativa errada marcada → vermelha
                    $class = 'selected';
                } else {
                    $class = '';
                }
              @endphp

              <div class="review-option {{ $class }}">
                <strong>{{ $opt->label }})</strong> {{ $opt->text }}
              </div>

            @endforeach
          </div>

        </div>
      @endforeach

    </div>

  </div>
</x-app-layout>


<style>
.page-title {
  font-size: 2rem;
  font-weight: 800;
  color: #065f46;
  margin-bottom: 1rem;
}

.questions-review {
  display: grid;
  gap: 1.2rem;
}

.review-card {
  background: white;
  border: 1px solid #d1fae5;
  border-radius: 1rem;
  padding: 1.2rem 1.4rem;
}

.review-header {
  display: flex;
  gap: .5rem;
  margin-bottom: .5rem;
  font-weight: 700;
  color: #065f46;
}

.review-img {
  max-width: 100%;
  border-radius: .75rem;
  margin: .6rem 0;
  border: 1px solid #d1fae5;
}

.review-option {
  padding: .6rem .8rem;
  border-radius: .65rem;
  margin-bottom: .4rem;
  border: 1px solid #d1fae5;
  background: #f8fffc;
}

.review-option.correct {
  border: 2px solid #059669 !important;
  background: #d1fae5 !important;
  color: #065f46 !important;
  font-weight: 700;
}

.review-option.selected {
  border: 2px solid #dc2626 !important;
  background: #fee2e2 !important;
  color: #b91c1c !important;
  font-weight: 700;
}
</style>
