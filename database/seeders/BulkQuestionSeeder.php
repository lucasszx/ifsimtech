<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Topic, Question, QuestionOption};

class BulkQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $topics = Topic::with('subject')->get();
        if ($topics->isEmpty()) return;

        foreach ($topics as $topic) {
            // crie 20 por tópico (ajuste se quiser)
            for ($i = 1; $i <= 20; $i++) {
                $q = Question::firstOrCreate([
                    'subject_id' => $topic->subject_id,
                    'topic_id'   => $topic->id,
                    'statement'  => "({$topic->subject->name} - {$topic->name}) Questão {$i}: Qual é a alternativa correta?",
                ],[
                    'source'     => 'IFSul',
                    'year'       => 2023,
                ]);

                // opções A–E (C correta)
                $options = [
                    ['A','Alternativa A',false],
                    ['B','Alternativa B',false],
                    ['C','Alternativa C',true],
                    ['D','Alternativa D',false],
                    ['E','Alternativa E',false],
                ];
                foreach ($options as [$label,$text,$ok]) {
                    QuestionOption::firstOrCreate(
                        ['question_id'=>$q->id,'label'=>$label],
                        ['text'=>$text,'is_correct'=>$ok]
                    );
                }
            }
        }
    }
}
