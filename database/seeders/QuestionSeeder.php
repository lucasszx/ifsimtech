<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Subject, Topic, Question, QuestionOption};

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $topic = Topic::where('name','Funções')->first() 
              ?? Topic::first(); // fallback se não existir

        if (!$topic) return;

        $q = Question::firstOrCreate([
            'subject_id' => $topic->subject_id,
            'topic_id'   => $topic->id,
            'statement'  => 'Se f(x) = 2x + 3, então f(5) = ?',
            'source'     => 'IFSul',
            'year'       => 2022,
        ]);

        $opts = [
            ['A','7',false], ['B','10',false], ['C','13',true],
            ['D','8',false], ['E','15',false],
        ];

        foreach ($opts as [$label,$text,$ok]) {
            QuestionOption::firstOrCreate([
                'question_id' => $q->id,
                'label'       => $label,
            ],[
                'text'        => $text,
                'is_correct'  => $ok,
            ]);
        }
    }
}
