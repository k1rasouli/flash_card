<?php

namespace Tests\Feature;

use App\Models\Flashcard;
use App\Models\FlashcardAnswer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlashcardInteractiveStatusFlashcardTest extends TestCase
{
    /**
     * Flashcard status comment is tested here
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_status_information()
    {
        FlashcardAnswer::factory(10)->create();
        $flash_card_count = Flashcard::all()->count();
        $all_answered_questions_count = count(FlashcardAnswer::allAnsweredQuestionIdArray());
        $correct_answers_count = FlashcardAnswer::allCorrectAnswers()->count();
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(__('flash_interactive_menu'), __('flashcard_stat'))
            ->expectsOutput(__('question_amount') . $flash_card_count)
            ->expectsOutput(__('answered_amount') . $all_answered_questions_count)
            ->expectsOutput(__('correct_amount') . $correct_answers_count)
            ->assertExitCode(0);
        $this->assertEquals($flash_card_count, 10);
        $this->assertEquals($all_answered_questions_count, 10);
        $this->assertNotEquals($correct_answers_count, 11);
    }
}
