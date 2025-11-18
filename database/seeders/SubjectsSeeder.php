<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subjects')->insert([
            ['name' => 'Matemática',   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Português',    'created_at' => now(), 'updated_at' => now()],
            ['name' => 'História',     'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Geografia',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
