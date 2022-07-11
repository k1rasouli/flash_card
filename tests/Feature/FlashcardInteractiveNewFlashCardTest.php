<?php

namespace Tests\Feature;

use App\Models\Flashcard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlashcardInteractiveNewFlashCardTest extends TestCase
{
    /**
     * New flashcard test.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_if_we_can_add_new_question()
    {
        $question = 'Last laravel version?';
        $answer = '9';
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(__('flash_interactive_menu'), __('new_flashcard'))
            ->expectsQuestion(__('question_text_prompt'), $question)
            ->expectsQuestion(__('question_answer_prompt'), $answer)
            ->expectsOutput(__('add_success'))
            ->assertExitCode(0);
        $objFlashCard = Flashcard::byQuestionText($question);
        $this->assertEquals($objFlashCard->count(), 1);
    }

    public function test_new_flashcard_invalid_inputs()
    {
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(__('flash_interactive_menu'), __('new_flashcard'))
            ->expectsQuestion(__('question_text_prompt'), '')
            ->expectsQuestion(__('question_answer_prompt'), '')
            ->expectsTable(
                [__('errors_header')],
                [['The question text field is required.'],
                 ['The answer text field is required.']])
            ->assertExitCode(0);

        $objFlashCard = Flashcard::byQuestionText('');
        $this->assertEquals($objFlashCard->count(), 0);
    }
}
