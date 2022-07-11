<?php

namespace Tests\Feature;

use App\Models\Flashcard;
use App\Models\FlashcardAnswer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlashcardInteractivePracticeFlashcardTest extends TestCase
{
    /**
     * Some practice flashcards are tested here
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_flashcard_selection_and_exit()
    {
        Flashcard::factory(5)->create();
        FlashcardAnswer::factory(5)->create();

        $flashcards = Flashcard::with('flashcard_answer')->get()->toArray();
        $header = [__('selection_value'), __('question'), __('status')];
        $data = [];
        $selection_value = 0;
        $correct_answer_count = FlashcardAnswer::allCorrectAnswers()->count();
        foreach ($flashcards as $flash_card)
        {
            $answer_status = __('not_answered');
            ++$selection_value;
            if(count($flash_card['flashcard_answer']) > 0)
            {
                $was_answer_correct = false;
                foreach ($flash_card['flashcard_answer'] as $flash_card_answer)
                {
                    if($flash_card_answer['was_answer_correct'])
                    {
                        $was_answer_correct = true;
                        break;
                    }
                }
                if($was_answer_correct)
                    $answer_status = __('correct');
                else
                    $answer_status = __('incorrect');
            }
            if($answer_status != __('correct'))
                $selection_array[$selection_value] = $flash_card['question_text'];
            $data[] = [$selection_value, $flash_card['question_text'], $answer_status];
        }
        $count_text = __('questions_count') . count($flashcards);
        $correct_text = __('correct_count') . $correct_answer_count;
        $data[] = [__('stats'), $count_text ,$correct_text];
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(__('flash_interactive_menu'), __('flashcard_practice'))
            ->expectsTable($header, $data)
            ->expectsQuestion(__('question_select'), __('flashcard_exit'))
            ->assertExitCode(0);
    }

    public function test_correct_answer_insertion()
    {
        $objFlashCards = Flashcard::factory(5)->create();
        $flashcard =  $objFlashCards->first();
        $provided_answer = "test answer";
        $is_provided_answer_correct = false;
        if($provided_answer == $flashcard->answer_text)
            $is_provided_answer_correct = true;
        FlashcardAnswer::create(['flashcard_id' => $flashcard->id, 'user_id' => null, 'provided_answer' => $provided_answer, 'was_answer_correct' => $is_provided_answer_correct]);
        $this->assertDatabaseHas('flashcard_answers', [
            'flashcard_id' => $flashcard->id,
            'provided_answer' => $provided_answer
        ]);
    }
}
