<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\UserTopicStat;
use App\Models\Topic;
use App\Models\AttemptAnswer;

class ResultController extends Controller
{
    public function show(Attempt $attempt)
    {
        $attempt->load(['answers.question.subject','answers.question.topics','exam.questions']);

        // ---- já estava no seu código ----
        $bySubject = $attempt->answers->groupBy(fn($a)=>$a->question->subject->name)
            ->map(fn($g)=>[
                'total'=>$g->count(),
                'hits'=>$g->where('is_correct',true)->count(),
                'rate'=> round(100 * $g->where('is_correct',true)->count() / max(1,$g->count())),
            ]);

        // IDs de tópicos que apareceram neste simulado
        $topicIdsInAttempt = $attempt->answers
            ->flatMap(fn($a) => $a->question->topics->pluck('id'))
            ->unique()
            ->values()
            ->all();

        $userId = $attempt->user_id;

        $stats = UserTopicStat::with('topic.subject')
            ->where('user_id', $userId)
            ->whereIn('topic_id', $topicIdsInAttempt)
            ->get();

        $byTopic = [];

        // varre todas as respostas do attempt atual
        foreach ($attempt->answers as $answer) {
            $question = $answer->question;

            foreach ($question->topics as $topic) {

                if (!isset($byTopic[$topic->id])) {
                    $byTopic[$topic->id] = [
                        'id'      => $topic->id,
                        'name'    => $topic->name,
                        'subject' => $topic->subject->name ?? 'Sem matéria',
                        'total'   => 0,
                        'hits'    => 0,
                    ];
                }

                $byTopic[$topic->id]['total']++;
                if ($answer->is_correct) {
                    $byTopic[$topic->id]['hits']++;
                }
            }
        }

        // calcula taxa
        foreach ($byTopic as &$t) {
            $t['rate'] = round(100 * $t['hits'] / max(1, $t['total']));
        }

        $byTopic = collect($byTopic)->sortBy('rate');


            $byTopic = $byTopic->sortBy('rate');

            $suggestions = $byTopic
                ->filter(fn($v) => $v['rate'] < 70)
                ->take(3)
                ->keys()
                ->values();

        return view('results.show', compact('attempt','bySubject','byTopic','suggestions'));
    }

    public function history()
    {
        $attempts = Attempt::with('exam')->where('user_id',auth()->id())
            ->orderByDesc('created_at')->paginate(10);

        return view('results.history', compact('attempts'));
    }

    public function destroy(\App\Models\Attempt $attempt)
    {
        // Só permite excluir se for do usuário logado
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Deleta respostas associadas
        $attempt->answers()->delete();

        // Deleta o próprio registro
        $attempt->delete();

        return redirect()
            ->route('results.history')
            ->with('status', 'Simulado removido com sucesso.');
    }

    public function topicDetails(Topic $topic)
    {
        $userId = auth()->id();

        $stat = UserTopicStat::where('user_id', $userId)
            ->where('topic_id', $topic->id)
            ->first();

        $wrongAnswers = AttemptAnswer::with([
                'question.subject',
                'question.topics',
                'question.options',
                'attempt.exam',
            ])
            ->where('is_correct', false)
            ->whereHas('attempt', fn($q) => $q->where('user_id', $userId))
            ->whereHas('question.topics', fn($q) => $q->where('topics.id', $topic->id))
            ->orderByDesc('created_at')
            ->get();

        return view('results.topic-details', compact('topic', 'wrongAnswers', 'stat'));
    }

    public function topicErrorsInAttempt(Attempt $attempt, Topic $topic)
    {
        // Carrega respostas + questões + opções + tópicos
        $attempt->load('answers.question.options', 'answers.question.topics');

        // Filtra apenas respostas erradas deste simulado e deste tópico
        $errors = $attempt->answers
            ->filter(function ($answer) use ($topic) {
                return !$answer->is_correct &&
                    $answer->question->topics->contains('id', $topic->id);
            })
            ->map(function ($answer) {

                $question = $answer->question;

                return [
                    'id'        => $question->id,
                    'statement' => 
                        $question->statement 
                        ?? $question->text
                        ?? $question->description
                        ?? $question->body
                        ?? $question->question
                        ?? $question->question_text
                        ?? '',
                    'image'     => $question->image_path,
                    'selected'  => $answer->selected_label,
                    'options'   => $question->options->map(function ($opt) {
                        return [
                            'label'      => $opt->label,
                            'text'       => $opt->text,      // <-- ajuste se seu campo de opção for outro!
                            'is_correct' => (bool) $opt->is_correct,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($errors);
    }

}
