<?php

namespace App\Console\Commands;

use App\Models\Flashcard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class NewFlashCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new flashcard.';

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
        $question_text = $this->ask(__('question_text_prompt'));
        $answer_text = $this->ask(__('question_answer_prompt'));
        $flash_card_question_validator = Validator::make(['question_text' => $question_text, 'answer_text' => $answer_text], Flashcard::$rules);
        if($flash_card_question_validator->passes())
        {
            Flashcard::create(['question_text' => $question_text, 'answer_text' => $answer_text]);
            $this->info(__('add_success'));
            return 0;
        }
        else
        {
            $header = [__('errors_header')];
            $data = [];
            foreach ($flash_card_question_validator->errors()->all() as $error_message)
            {
                $data[] = [$error_message];
            }
            $this->table($header, $data);
            return 0;
        }
    }
}
