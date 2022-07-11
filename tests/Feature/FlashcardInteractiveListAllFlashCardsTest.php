<?php

namespace Tests\Feature;

use App\Models\Flashcard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlashcardInteractiveListAllFlashCardsTest extends TestCase
{
    /**
     * Test flashcard list functionality
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_see_flashcards_list()
    {
        $flashcards = Flashcard::factory(10)->create();
        $header = [__('question'), __('answer')];
        $data = [];
        foreach ($flashcards as $flashcard)
        {
            $data[] = [$flashcard->question_text, $flashcard->answer_text];
        }
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(__('flash_interactive_menu'), __('flashcard_list'))
            ->expectsTable($header, $data)
            ->assertExitCode(0);
    }

    public function test_see_flashcards_empty_list()
    {
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(__('flash_interactive_menu'), __('flashcard_list'))
            ->expectsOutput(__('empty_flashcard_list'))
            ->assertExitCode(0);
    }
}
