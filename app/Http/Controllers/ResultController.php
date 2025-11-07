<?php

namespace App\Http\Controllers;

use App\Models\Attempt;

class ResultController extends Controller
{
    public function show(Attempt $attempt)
    {
        $attempt->load(['answers.question.subject','answers.question.topic','exam.questions']);

        $bySubject = $attempt->answers->groupBy(fn($a)=>$a->question->subject->name)
            ->map(fn($g)=>[
                'total'=>$g->count(),
                'hits'=>$g->where('is_correct',true)->count(),
                'rate'=> round(100 * $g->where('is_correct',true)->count() / max(1,$g->count())),
            ]);

        $byTopic = $attempt->answers->groupBy(fn($a)=>optional($a->question->topic)->name ?? 'Sem tópico')
            ->map(fn($g)=>[
                'total'=>$g->count(),
                'hits'=>$g->where('is_correct',true)->count(),
                'rate'=> round(100 * $g->where('is_correct',true)->count() / max(1,$g->count())),
            ])->sortBy('rate');

        $suggestions = $byTopic->filter(fn($v)=>$v['rate'] < 70)->take(3)->keys()->values();

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

}
