<?php

namespace App\Http\Controllers;

use App\Models\{Subject, Question, Exam, Attempt};
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function create()
    {
        return view('exams.create', [
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'title' => 'nullable|string',
            'questions_count' => 'required|integer|min:1|max:80',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'integer|exists:subjects,id',
        ]);

        $exam = Exam::create([
            'user_id' => auth()->id(),
            'title' => $data['title'] ?? 'Simulado',
            'questions_count' => $data['questions_count'],
            'filters' => [
                'subjects' => $data['subjects'],
            ],
        ]);

        $q = Question::whereIn('subject_id', $data['subjects']);

        $available = $q->count();
        if ($available === 0) {
            return back()
            ->withInput()
            ->withErrors(['subjects' => 'Não há questões para os filtros selecionados.']);
        }

        $take = min($available, (int)$data['questions_count']);
        $questions = $q->inRandomOrder()->limit($take)->get();

        // anexa e atualiza o total real
        foreach ($questions as $i => $question) {
            $exam->questions()->attach($question->id, ['order' => $i + 1]);
        }
        $exam->update(['questions_count' => $questions->count()]);

        $attempt = Attempt::create([
            'exam_id' => $exam->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('attempts.play', $attempt);
    }
}
