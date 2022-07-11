<?php

namespace Database\Seeders;

use App\Models\FlashcardAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashcardAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FlashcardAnswer::factory(10)->create();
    }
}
