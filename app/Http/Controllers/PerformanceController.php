<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function subjects()
    {
        $statsBySubject = DB::table('attempt_question')
            ->join('questions', 'attempt_question.question_id', '=', 'questions.id')
            ->join('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->where('attempt_question.user_id', auth()->id())
            ->select(
                'subjects.id',
                'subjects.name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(correct) as hits'),
                DB::raw('ROUND(SUM(correct) / COUNT(*) * 100, 1) as rate')
            )
            ->groupBy('subjects.id', 'subjects.name')
            ->orderBy('rate', 'asc')
            ->get();

        return view('performance.subjects', compact('statsBySubject'));
    }
}
