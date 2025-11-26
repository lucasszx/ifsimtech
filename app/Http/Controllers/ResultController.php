<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\UserTopicStat;
use App\Models\Topic;
use App\Models\AttemptAnswer;
use App\Models\StudyGoal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller{
    
    public function show(Attempt $attempt){
        $attempt->load([
            'answers.question.subject',
            'answers.question.topics',
            'exam.questions'
        ]);

        $userId = $attempt->user_id;

        /* ============================================================
        1. DESEMPENHO POR MATÉRIA (somente deste simulado)
        ============================================================ */
        $bySubject = $attempt->answers
            ->groupBy(fn ($a) => $a->question->subject->name)
            ->map(fn ($g, $name) => [
                'subject' => $name,
                'total'   => $g->count(),
                'hits'    => $g->where('is_correct', true)->count(),
                'rate'    => round(100 * $g->where('is_correct', true)->count() / max(1, $g->count())),
            ])
            ->values()
            ->toArray();


        /* ============================================================
        2. DESEMPENHO POR TÓPICO (somente deste simulado)
        ============================================================ */
        $byTopic = [];

        foreach ($attempt->answers as $answer) {
            foreach ($answer->question->topics as $topic) {

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

        foreach ($byTopic as &$t) {
            $t['rate'] = round(100 * $t['hits'] / max(1, $t['total']));
        }

        $byTopic = collect($byTopic)
            ->sortBy('rate')
            ->values()
            ->toArray();


        /* ============================================================
        3. DESEMPENHO GERAL POR MATÉRIA (TODOS SIMULADOS)
        ============================================================ */
        $global_subjects = DB::table('attempt_answers')
            ->join('attempts', 'attempt_answers.attempt_id', '=', 'attempts.id')
            ->join('questions', 'attempt_answers.question_id', '=', 'questions.id')
            ->join('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->where('attempts.user_id', $userId)
            ->select(
                'subjects.id',
                'subjects.name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(is_correct) as hits'),
                DB::raw('ROUND(SUM(is_correct) / COUNT(*) * 100, 1) as rate')
            )
            ->groupBy('subjects.id', 'subjects.name')
            ->orderBy('rate')
            ->get();

        /* ============================================================
        4. SUGESTÕES INTELIGENTES (base histórico)
        ============================================================ */
        $userStats = UserTopicStat::with('topic.subject')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($stat) {

                $rate = $stat->total_attempts > 0
                    ? round(($stat->correct_attempts / $stat->total_attempts) * 100)
                    : 0;

                $level = $rate < 40 ? 'Crítico' : ($rate < 70 ? 'Atenção' : 'OK');

                return [
                    'id'      => $stat->topic_id,
                    'name'    => $stat->topic->name,
                    'subject' => $stat->topic->subject->name ?? 'Geral',
                    'rate'    => $rate,
                    'level'   => $level,
                ];
            });

        $critical = $userStats->where('level', 'Crítico')->sortBy('rate')->take(3);
        $warn     = $userStats->where('level', 'Atenção')->sortBy('rate')->take(3);
        $ok       = $userStats->where('level', 'OK')->sortByDesc('rate')->take(2);

        $suggestionBank = [
            'Crítico' => [
                "Refaça pelo menos 10 questões desse tópico até superar 70%.",
                "Revise a teoria e tente explicar o conteúdo com suas próprias palavras.",
                "Monte um mini-simulado com 5 questões focadas apenas nesse tema.",
            ],
            'Atenção' => [
                "Refaça as questões que errou e identifique o padrão dos erros.",
                "Misture esse tópico com outro forte em um novo simulado.",
                "Resolva 3 exercícios extras de aplicação prática.",
            ],
            'OK' => [
                "Mantenha revisões leves para não perder o ritmo.",
                "Resolva 2 questões rápidas por semana para manter o domínio.",
            ],
        ];

        $suggestions_global = collect();

        $addSuggestion = function ($topic) use (&$suggestions_global, $suggestionBank) {
            $msg = $suggestionBank[$topic['level']][array_rand($suggestionBank[$topic['level']])];
            $suggestions_global->push([
                'id'      => $topic['id'],        // <<<<<<<<<<<<<< ADICIONAR
                'topic'   => $topic['name'],
                'level'   => $topic['level'],
                'rate'    => $topic['rate'],
                'message' => $msg,
            ]);
        };

        foreach ($critical as $t) { $addSuggestion($t); }
        foreach ($warn     as $t) { $addSuggestion($t); }
        foreach ($ok       as $t) { $addSuggestion($t); }


        /* ============================================================
        5. METAS DE ESTUDO AUTOMÁTICAS
        ============================================================ */
        $studyGoals = collect();

        foreach ($byTopic as $t) {

            if ($t['rate'] < 40) {
                $title = "Reforçar tópico crítico: {$t['name']}";
                $desc  = "Refaça pelo menos 10 questões de \"{$t['name']}\" até superar 70% de acerto.";
            } elseif ($t['rate'] < 70) {
                $title = "Revisar tópico em atenção: {$t['name']}";
                $desc  = "Revise a teoria e refaça questões que errou no tópico \"{$t['name']}\".";
            } else {
                continue;
            }

            $goal = StudyGoal::firstOrCreate(
                [
                    'user_id'    => $userId,
                    'topic_id'   => $t['id'],
                    'attempt_id' => $attempt->id,
                ],
                [
                    'title'       => $title,
                    'description' => $desc,
                    'status'      => 'pending',
                    'due_date'    => Carbon::now()->addDays(7),
                ]
            );

            $studyGoals->push($goal);
        }


        /* ============================================================
        6. RETORNO PARA VIEW
        ============================================================ */
        return view('results.show', [
            'attempt'            => $attempt,
            'bySubject'          => $bySubject,
            'byTopic'            => $byTopic,
            'global_subjects'    => $global_subjects,  // <<< NOVO
            'suggestions_global' => $suggestions_global,
            'studyGoals'         => $studyGoals,
        ]);
    }

    public function history(){
        $attempts = Attempt::with('exam')->where('user_id',auth()->id())
            ->orderByDesc('created_at')->paginate(10);

        return view('results.history', compact('attempts'));
    }

    public function destroy(\App\Models\Attempt $attempt){
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $userId = $attempt->user_id;

        // ===============================================
        // 1. REVERTER ESTATÍSTICAS DE TÓPICOS (UserTopicStat)
        // ===============================================
        foreach ($attempt->answers as $answer) {
            foreach ($answer->question->topics as $topic) {

                $stat = \App\Models\UserTopicStat::where('user_id', $userId)
                    ->where('topic_id', $topic->id)
                    ->first();

                if ($stat) {
                    // Remove 1 tentativa
                    $stat->total_attempts = max(0, $stat->total_attempts - 1);

                    // Remove acerto caso tenha acertado
                    if ($answer->is_correct) {
                        $stat->correct_attempts = max(0, $stat->correct_attempts - 1);
                    }

                    // Se zerou tudo, pode limpar o registro
                    if ($stat->total_attempts == 0 && $stat->correct_attempts == 0) {
                        $stat->delete();
                    } else {
                        $stat->save();
                    }
                }
            }
        }

        // ===============================================
        // 2. Deletar respostas
        // ===============================================
        $attempt->answers()->delete();

        // ===============================================
        // 3. Deletar o simulado
        // ===============================================
        $attempt->delete();

        return redirect()
            ->route('results.history')
            ->with('status', 'Simulado removido com sucesso.');
    }

    public function topicDetails(Topic $topic){
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

    public function topicErrorsInAttempt(Attempt $attempt, Topic $topic){
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

    public function review(Attempt $attempt){
        $attempt->load([
            'answers.question.options',
            'answers.question.subject',
            'answers.question.topics'
        ]);

        return view('results.review', [
            'attempt' => $attempt,
            'answers' => $attempt->answers
        ]);
    }

}
