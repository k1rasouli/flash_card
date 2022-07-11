<?php

namespace App\Console\Commands;

use App\Models\FlashcardAnswer;
use Illuminate\Console\Command;

class ResetFlashCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erase all practice progress and allow a fresh start.';

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
        $delete_conformation = $this->choice(__('confirmation'), [1 => __('yes'), 2 => __('no')]);
        if($delete_conformation == __('yes'))
        {
            if(auth()->check())
                FlashcardAnswer::where('user_id', '=', auth()->user()->id)->delete();
            else
                FlashcardAnswer::truncate();

            $this->info(__('reset_message'));
        }
        return 0;
    }
}
