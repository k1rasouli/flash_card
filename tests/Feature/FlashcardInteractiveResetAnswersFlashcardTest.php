<?php

namespace Tests\Feature;

use App\Models\FlashautcardAnswer;
use App\Models\FlashcardAnswer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlashcardInteractiveResetAnswersFlashcardTest extends TestCase
{
    /**
     * Resting all answers is tested here
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_if_answers_will_reset()
    {
        FlashcardAnswer::factory(10)->create();

        $this->artisan('flashcard:interactive')
            ->expectsQuestion(__('flash_interactive_menu'), __('flashcard_reset'))
            ->expectsQuestion(__('confirmation'), __('yes'))
            ->expectsOutput(__('reset_message'))
            ->assertExitCode(0);

        $this->assertDatabaseCount('flashcard_answers', 0);
    }
}
