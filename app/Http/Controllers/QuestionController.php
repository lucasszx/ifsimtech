<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Subject, Topic, Question, QuestionOption};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function create()
    {
        return view('admin.questions.create', [
            'subjects' => Subject::orderBy('name')->get(),
            'topics'   => Topic::with('subject')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'topic_id'      => 'nullable|exists:topics,id',
            'year'          => 'nullable|integer|min:1990|max:2100',
            'source'        => 'nullable|string|max:100',
            // um ou outro:
            'statement'     => 'nullable|string|required_without:image',
            'image'         => 'nullable|image|max:4096|required_without:statement',

            'A'             => 'required|string',
            'B'             => 'required|string',
            'C'             => 'required|string',
            'D'             => 'required|string',
            'E'             => 'required|string',
            'correct_label' => 'required|in:A,B,C,D,E',
        ]);

        $q = new Question();
        $q->subject_id = $data['subject_id'];
        $q->topic_id   = $data['topic_id'] ?? null;
        $q->statement  = $data['statement'] ?? null;
        $q->source     = $data['source'] ?? null;
        $q->year       = $data['year'] ?? null;

        if ($req->hasFile('image')) {
            $year = $data['year'] ?? now()->year;
            $path = $req->file('image')->store("questions/{$year}", 'public');
            $q->image_path = $path;
        }

        $q->save();

        foreach (['A','B','C','D','E'] as $label) {
            QuestionOption::create([
                'question_id' => $q->id,
                'label'       => $label,
                'text'        => $data[$label],
                'is_correct'  => $data['correct_label'] === $label,
            ]);
        }

        return redirect()
            ->route('admin.questions.create')
            ->with('status', 'Questão cadastrada com sucesso!');
    }

    public function edit(Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', [
            'question' => $question,
            'subjects' => Subject::orderBy('name')->get(),
            'topics'   => Topic::with('subject')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $req, Question $question)
    {
        $data = $req->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'topic_id'      => 'nullable|exists:topics,id',
            'year'          => 'nullable|integer|min:1990|max:2100',
            'source'        => 'nullable|string|max:100',
            'statement'     => 'nullable|string|required_without:image',
            'image'         => 'nullable|image|max:4096|required_without:statement',
            'A'             => 'required|string',
            'B'             => 'required|string',
            'C'             => 'required|string',
            'D'             => 'required|string',
            'E'             => 'required|string',
            'correct_label' => 'required|in:A,B,C,D,E',
        ]);

        DB::transaction(function () use ($req, $question, $data) {
            $question->update([
                'subject_id' => $data['subject_id'],
                'topic_id'   => $data['topic_id'] ?? null,
                'statement'  => $data['statement'] ?? null,
                'source'     => $data['source'] ?? null,
                'year'       => $data['year'] ?? null,
            ]);

            if ($req->hasFile('image')) {
                if ($question->image_path && Storage::disk('public')->exists($question->image_path)) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $year = $data['year'] ?? now()->year;
                $path = $req->file('image')->store("questions/{$year}", 'public');
                $question->image_path = $path;
                $question->save();
            }

            foreach (['A','B','C','D','E'] as $label) {
                $opt = $question->options()->firstOrNew(['label' => $label]);
                $opt->text = $data[$label];
                $opt->is_correct = $data['correct_label'] === $label;
                $opt->save();
            }
        });

        return redirect()->route('admin.questions.index')->with('status', 'Questão atualizada com sucesso!');
    }

    public function destroy(Question $question)
    {
        DB::transaction(function () use ($question) {
            if ($question->image_path && Storage::disk('public')->exists($question->image_path)) {
                Storage::disk('public')->delete($question->image_path);
            }
            $question->options()->delete();
            $question->delete();
        });

        return redirect()->route('admin.questions.index')->with('status', 'Questão excluída com sucesso!');
    }

}
