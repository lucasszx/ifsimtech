<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('topics')->insert([

            // Matemática
            ['subject_id' => 1, 'name' => 'Operações Básicas',       'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 1, 'name' => 'Porcentagem',             'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 1, 'name' => 'Equações do 1º Grau',     'created_at' => now(), 'updated_at' => now()],

            // Português
            ['subject_id' => 2, 'name' => 'Interpretação de Texto',  'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 2, 'name' => 'Ortografia',              'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 2, 'name' => 'Classes Gramaticais',     'created_at' => now(), 'updated_at' => now()],

            // História
            ['subject_id' => 3, 'name' => 'Brasil Colônia',          'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 3, 'name' => 'Idade Média',             'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 3, 'name' => 'Revolução Industrial',    'created_at' => now(), 'updated_at' => now()],

            // Geografia
            ['subject_id' => 4, 'name' => 'Relevo Brasileiro',       'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 4, 'name' => 'Climas do Brasil',        'created_at' => now(), 'updated_at' => now()],
            ['subject_id' => 4, 'name' => 'Globalização',            'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
