<?php

namespace Database\Seeders;

use App\Models\Keyword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Keyword::create(['name' => 'Urgente']);
        Keyword::create(['name' => 'Importante']);
        Keyword::create(['name' => 'Baja Prioridad']);
    }
}
