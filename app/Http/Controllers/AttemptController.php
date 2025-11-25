<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Attempt, AttemptAnswer, QuestionOption};
use App\Services\UserTopicStatsService;

class AttemptController extends Controller
{
    public function play(Attempt $attempt, Request $req)
    {
        $attempt->load('answers');
        $exam = $attempt->exam()->with(['questions.options','questions.subject','questions.topics'])->first();
        $ordered = $exam->questions->sortBy('pivot.order')->values();
        $total   = $ordered->count();

        // índice atual (1-based), se não vier, vai para a primeira NÃO respondida
        $i = (int)$req->query('i', 0);
        if ($i < 1 || $i > $total) {
            $answeredIds = $attempt->answers->pluck('question_id')->all();
            $firstUnansweredIndex = 1;
            foreach ($ordered as $idx => $q) {
                if (!in_array($q->id, $answeredIds, true)) { $firstUnansweredIndex = $idx+1; break; }
                if ($idx === $total-1) { $firstUnansweredIndex = 1; } // todas respondidas => volta pra 1
            }
            $i = $firstUnansweredIndex;
        }

        $question = $ordered[$i - 1];
        $answer   = $attempt->answers->firstWhere('question_id', $question->id);
        $answeredCount = $attempt->answers->count();

        return view('attempts.play', compact('attempt','exam','question','answer','i','total','answeredCount'));
    }

    public function answer(Request $req, Attempt $attempt)
    {
        $data = $req->validate([
            'question_id'    => 'required|exists:questions,id',
            'selected_label' => 'required|in:A,B,C,D,E',
            'redirect_i'     => 'nullable|integer|min:1',
        ]);

        $isCorrect = (bool) QuestionOption::where('question_id',$data['question_id'])
            ->where('label',$data['selected_label'])
            ->value('is_correct');

        AttemptAnswer::updateOrCreate(
            ['attempt_id'=>$attempt->id, 'question_id'=>$data['question_id']],
            ['selected_label'=>$data['selected_label'], 'is_correct'=>$isCorrect]
        );

        $goto = $data['redirect_i'] ?? 1;
        return redirect()->route('attempts.play', [$attempt, 'i' => $goto]);
    }

    // Salvar & sair (mantém in_progress)
    public function save(Request $req, Attempt $attempt)
    {
        $attempt->update([
            // opcional: persistir tempo parcial
            'time_seconds' => $req->integer('time_seconds', $attempt->time_seconds ?? 0),
            'status'       => 'in_progress',
        ]);

        return redirect()->route('results.history')->with('status','Simulado salvo. Você pode continuar depois.');
    }

    // Finalizar: se incompleto, permanece in_progress; se completo, submitted
    public function submit(Request $req, Attempt $attempt, UserTopicStatsService $statsService){
        $attempt->load('answers', 'exam.questions');

        $total    = $attempt->exam->questions->count();
        $answered = $attempt->answers->count();
        $score    = $attempt->answers->where('is_correct', true)->count();

        $attempt->update([
            'score'        => $score,
            'time_seconds' => $req->integer('time_seconds', 0),
            'status'       => $answered >= $total ? 'submitted' : 'in_progress',
        ]);

        // Só atualiza estatísticas se finalizou COMPLETO
        if ($answered >= $total) {

            // Atualiza histórico por tópico
            $statsService->updateFromAttempt($attempt);

            // Redireciona para tela de resultados
            return redirect()->route('results.show', $attempt);
        }

        // ---- Caso incompleto, manter comportamento atual ----
        return redirect()
            ->route('attempts.play', [
                $attempt,
                'i' => $this->nextUnansweredIndex($attempt)
            ])
            ->with('warning', 'Você ainda não respondeu todas as questões. O simulado foi salvo para continuar depois.');
    }

    private function nextUnansweredIndex(Attempt $attempt): int
    {
        $ordered = $attempt->exam->questions->sortBy('pivot.order')->values();
        $answeredIds = $attempt->answers->pluck('question_id')->all();
        foreach ($ordered as $idx => $q) {
            if (!in_array($q->id, $answeredIds, true)) return $idx+1;
        }
        return 1;
    }

    private function updateTopicStats($userId, $topicId, $topicName, $isCorrect)
    {
        DB::table('attempt_question_stats')
            ->updateOrInsert(
                ['user_id' => $userId, 'topic_id' => $topicId],
                [
                    'topic_name' => $topicName,
                    'total_attempts'  => DB::raw('total_attempts + 1'),
                    'correct_attempts' => DB::raw('correct_attempts + ' . ($isCorrect ? 1 : 0)),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
    }

}
