<?php

namespace Database\Factories;

use App\Models\Flashcard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashcardAnswer>
 */
class FlashcardAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user_id = null;
        if(env('MULTI_USER'))
            $user_id = User::factory(1)->create()->toArray()[0]['id'];
        $flashcard = Flashcard::factory(1)->create();
        $provided_answers = [Str::random(5), Str::random(5), $flashcard->toArray()[0]['answer_text']];
        shuffle($provided_answers);
        $answer = $provided_answers[0];
        $was_answer_correct = false;
        if($answer == $flashcard->toArray()[0]['answer_text'])
            $was_answer_correct = true;
        return [
            'flashcard_id' => $flashcard->toArray()[0]['id'],
            'user_id' => $user_id,
            'provided_answer' => $answer,
            'was_answer_correct' => $was_answer_correct,
        ];
    }
}
