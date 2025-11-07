<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Subject, Topic};

class SubjectTopicSeeder extends Seeder
{
    public function run(): void
    {
        $mat = Subject::firstOrCreate(['name' => 'Matemática']);
        $por = Subject::firstOrCreate(['name' => 'Português']);

        $map = [
            $mat->id => ['Funções','Geometria','Probabilidade'],
            $por->id => ['Interpretação de Texto','Gramática'],
        ];

        foreach ($map as $subjectId => $topics) {
            foreach ($topics as $name) {
                Topic::firstOrCreate(['subject_id' => $subjectId, 'name' => $name]);
            }
        }
    }
}
