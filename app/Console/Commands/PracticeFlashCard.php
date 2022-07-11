<?php

namespace App\Console\Commands;

use App\Models\Flashcard;
use App\Models\FlashcardAnswer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class PracticeFlashCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:practice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is where a user will practice the flashcards that have been added.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user_id = null;
        $correct_answer_count = FlashcardAnswer::allCorrectAnswers()->count();
        $flash_cards = Flashcard::with('flashcard_answer')->get()->toArray();
        if(env('MULTI_USER'))
        {
            $this->error(__('use_interactive_error'));
            return 0;
        }
        if(auth()->check())
        {
            $user_id = auth()->user()->id;
            $correct_answer_count = FlashcardAnswer::allCorrectAnswersByUserId($user_id);
            $flash_cards = Flashcard::with(['flashcard_answer', function($query) use($user_id){
                $query->where('user_id', '=', $user_id);
            }])->get()->toArray();
        }

        $header = [__('selection_value'), __('question'), __('status')];
        $data = $selection_array  = array();
        $selection_value = 0;

        foreach ($flash_cards as $flash_card)
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
        $data[] = [__('stats'), __('questions_count') . count($flash_cards) , __('correct_count') . $correct_answer_count];
        $this->table($header, $data);

        $selection_array[0] = __('flashcard_exit');
        $menu_choice = $this->choice(__('question_select'), $selection_array);
        if($menu_choice == __('flashcard_exit'))
        {
            return 0;
        }
        $selected_flash_card = Flashcard::byQuestionText($menu_choice)->first();
        $provided_answer = $this->ask(__('question_answer_prompt'));
        $is_provided_answer_correct = $provided_answer == $selected_flash_card->answer_text;
        $flash_card_answer_validation = Validator::make([
            'flashcard_id' => $selected_flash_card->id,
            'user_id' => $user_id, 'provided_answer' => $provided_answer,
            'was_answer_correct' => $is_provided_answer_correct], FlashcardAnswer::$rules);
        if($flash_card_answer_validation->passes())
        {
            FlashcardAnswer::create(['flashcard_id' => $selected_flash_card->id, 'user_id' => $user_id, 'provided_answer' => $provided_answer, 'was_answer_correct' => $is_provided_answer_correct]);
        }
        else
        {
            $header = [__('errors_header')];
            $data = array();
            foreach ($flash_card_answer_validation->errors()->all() as $error_message)
            {
                $data[] = [$error_message];
            }
            $this->table($header, $data);
            $this->call('flashcard:practice');
        }

        if($is_provided_answer_correct)
            $this->info(__('correct'));
        else
            $this->error(__('incorrect'));
        $this->call('flashcard:practice');

    }
}
