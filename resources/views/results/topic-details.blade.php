<x-app-layout>
    <div class="page-result">

        <div class="result-header" style="margin-bottom: 1.5rem;">
            <h1>Tópico: {{ $topic->name }}</h1>

            @if($stat)
                <p class="muted">
                    Desempenho histórico:
                    <strong>{{ $stat->correct_attempts }}/{{ $stat->total_attempts }}</strong>
                    ({{ $stat->rate }}%)
                </p>
            @else
                <p class="muted">Ainda não há estatísticas suficientes para este tópico.</p>
            @endif
            <div class="result-actions">
                <a href="{{ url()->previous() ?: route('results.history') }}"
                class="btn btn-outline">
                    Voltar
                </a>

                @if($topic->subject)
                    <form method="POST" action="{{ route('exams.store') }}">
                        @csrf

                        <input type="hidden" name="title" value="Simulado focado em {{ $topic->name }}">
                        <input type="hidden" name="questions_count" value="10">

                        <input type="hidden" name="subjects[]" value="{{ $topic->subject->id }}">
                        <input type="hidden" name="topics[]" value="{{ $topic->id }}">

                        <button class="btn btn-primary">
                            Gerar simulado focado
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Todas as questões erradas deste tópico</h2>

            @if($wrongAnswers->isEmpty())
                <p class="muted">Você ainda não errou questões deste tópico.</p>
            @else

                <div class="suggestions">
                    @foreach($wrongAnswers as $answer)

                        @php
                            $question = $answer->question;
                            $correct = $question->options->firstWhere('is_correct', true);

                            $enunciado =
                                $question->statement
                                ?? $question->text
                                ?? $question->description
                                ?? $question->body
                                ?? $question->question
                                ?? $question->question_text
                                ?? 'Enunciado não informado.';
                        @endphp

                        <div class="question-card">

                            <div class="muted" style="font-size: .85rem; margin-bottom: .4rem;">
                                Simulado: <strong>{{ $answer->attempt->exam->title }}</strong>
                                • Data: {{ $answer->created_at->format('d/m/Y H:i') }}
                                • Matéria: {{ $question->subject->name }}
                            </div>

                            @if($question->image_path)
                                <div class="question-image">
                                    <img src="/storage/{{ $question->image_path }}">
                                </div>
                            @endif

                            <div class="options-block" style="margin-top: .8rem;">
                                @foreach($question->options as $opt)

                                    @php
                                        $classes = "";

                                        if ($opt->label == $answer->selected_label) {
                                            $classes .= " option-selected";
                                        }

                                        if ($opt->is_correct) {
                                            $classes .= " option-correct";
                                        }
                                    @endphp

                                    <div class="option-line {{ $classes }}">
                                        <strong>{{ $opt->label }})</strong> {{ $opt->text }}
                                    </div>

                                @endforeach
                            </div>

                        </div>

                    @endforeach
                </div>

            @endif
        </div>

    </div>
</x-app-layout>