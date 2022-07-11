<?php

namespace App\Console\Commands;

use App\Models\Flashcard;
use App\Models\FlashcardAnswer;
use Illuminate\Console\Command;

class StatsFlashCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display these stats: 1-Total amount of questions. 2-Questions that have an answer. 3-Questions that have a correct answer.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(env('MULTI_USER'))
        {
            $this->error(__('use_interactive_error'));
            return 0;
        }

        $flash_card_count = Flashcard::all()->count();
        $all_answered_questions_count = count(FlashcardAnswer::allAnsweredQuestionIdArray());
        $correct_answers_count = FlashcardAnswer::allCorrectAnswers()->count();
        if(auth()->check())
        {
            $user_id = auth()->user()->id;
            $all_answered_questions_count = count(FlashcardAnswer::allAnsweredQuestionIdArrayByUserId($user_id));
            $correct_answers_count = FlashcardAnswer::allCorrectAnswersByUserId($user_id);
        }
        $this->info(__('question_amount') . $flash_card_count);
        $this->info(__('answered_amount') . $all_answered_questions_count);
        $this->info(__('correct_amount') . $correct_answers_count);
        return 0;
    }
}
