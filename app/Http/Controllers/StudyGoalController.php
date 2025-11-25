<?php

namespace App\Http\Controllers;

use App\Models\StudyGoal;
use Illuminate\Http\Request;

class StudyGoalController extends Controller
{
    public function index(Request $request)
    {
        $goals = StudyGoal::where('user_id', auth()->id())
            ->orderByRaw("FIELD(status,'pending','done')")
            ->orderBy('due_date')
            ->get();

        return view('study-goals.index', compact('goals'));
    }

    public function complete(StudyGoal $goal)
    {
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }

        $goal->update([ 'status' => 'done' ]);

        return back()->with('status', 'Meta concluída!');
    }
}
