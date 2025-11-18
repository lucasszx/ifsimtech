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
            'subject_id' => 'required|exists:subjects,id',
            'topics'     => 'nullable|array',
            'topics.*'   => 'exists:topics,id',
            'year'       => 'nullable|integer|min:1990|max:2100',
            'source'     => 'nullable|string|max:100',

            'statement'  => 'nullable|string|required_without:image',
            'image'      => 'nullable|image|max:4096|required_without:statement',

            'A' => 'required|string',
            'B' => 'required|string',
            'C' => 'required|string',
            'D' => 'required|string',
            'E' => 'required|string',
            'correct_label' => 'required|in:A,B,C,D,E',
        ]);

        $q = Question::create([
            'subject_id' => $data['subject_id'],
            'statement'  => $data['statement'] ?? null,
            'source'     => $data['source'] ?? null,
            'year'       => $data['year'] ?? null,
        ]);

        // MULTI-TÓPICOS
        $q->topics()->sync($data['topics'] ?? []);

        if ($req->hasFile('image')) {
            $year = $data['year'] ?? now()->year;
            $path = $req->file('image')->store("questions/{$year}", 'public');
            $q->update(['image_path' => $path]);
        }

        foreach (['A','B','C','D','E'] as $label) {
            QuestionOption::create([
                'question_id' => $q->id,
                'label'       => $label,
                'text'        => $data[$label],
                'is_correct'  => $data['correct_label'] === $label,
            ]);
        }

        return redirect()
            ->route('admin.questions.index')
            ->with('status', 'Questão cadastrada com sucesso!');
    }

    public function edit(Question $question)
    {
        $question->load('options', 'topics');

        return view('admin.questions.edit', [
            'question' => $question,
            'subjects' => Subject::orderBy('name')->get(),
            'topics'   => Topic::with('subject')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'subject_id' => 'required',
            'year' => 'nullable|integer',
            'source' => 'nullable|string',
            'statement' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'topics' => 'array',
            'topics.*' => 'integer|exists:topics,id',
            'A' => 'required|string',
            'B' => 'required|string',
            'C' => 'required|string',
            'D' => 'required|string',
            'E' => 'required|string',
            'correct_label' => 'required|in:A,B,C,D,E'
        ]);

        // Atualiza os campos simples
        $question->update($data);

        // 🔥 **Aqui está o ponto ESSENCIAL**
        $question->topics()->sync($request->topics ?? []);

        // Atualiza opções
        foreach (['A','B','C','D','E'] as $label) {
            $question->options()
                ->where('label', $label)
                ->update([
                    'text' => $request->$label,
                    'is_correct' => $request->correct_label === $label
                ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Questão atualizada.');
    }

    public function destroy(Question $question)
    {
        DB::transaction(function () use ($question) {

            if ($question->image_path && Storage::disk('public')->exists($question->image_path)) {
                Storage::disk('public')->delete($question->image_path);
            }

            // limpa pivô
            $question->topics()->detach();

            // deleta alternativas
            $question->options()->delete();

            // deleta questão
            $question->delete();
        });

        return redirect()
            ->route('admin.questions.index')
            ->with('status', 'Questão excluída com sucesso!');
    }
}