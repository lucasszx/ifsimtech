<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        return view('admin.topics.index', [
            'topics' => Topic::with('subject')->orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('admin.topics.create', [
            'subjects' => Subject::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        Topic::create($data);

        return redirect()->route('admin.topics.index')
            ->with('status', 'Tópico criado com sucesso!');
    }

    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', [
            'topic' => $topic,
            'subjects' => Subject::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, Topic $topic)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $topic->update($data);

        return redirect()->route('admin.topics.index')
            ->with('status', 'Tópico atualizado!');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('admin.topics.index')
            ->with('status', 'Tópico removido.');
    }
}
