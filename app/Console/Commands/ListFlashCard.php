<?php

namespace App\Console\Commands;

use App\Models\Flashcard;
use Illuminate\Console\Command;

class ListFlashCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A table listing all the created flashcard questions with the correct answer.';

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

        if(Flashcard::all()->count() > 0)
        {
            $header = [__('question'), __('answer')];
            $data = array();
            foreach (Flashcard::all() as $flashcard)
            {
                $data[] = [$flashcard->question_text, $flashcard->answer_text];
            }
            $this->table($header, $data);
        }
        else
            $this->error(__('empty_flashcard_list'));
        return 0;
    }
}
