<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Psy\Readline\Hoa\ConsoleException;

class FalshCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an interactive CLI program for flashcards';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(env('MULTI_USER'))
        {
            $this->info(__('credentials_message'));
            $email = $this->ask(__('email_prompt'));
            $password = $this->secret(__('password_prompt'));
            if(!auth()->attempt(['email' => $email, 'password' => $password]))
            {
                $this->error(__('login_error'));
                return 0;
            }
        }
        $options = [
            1 => __('new_flashcard'),
            2 => __('flashcard_list'),
            3 => __('flashcard_practice'),
            4 => __('flashcard_stat'),
            5 => __('flashcard_reset'),
            6 => __('flashcard_exit')];
        $menuChoice = $this->choice(__('flash_interactive_menu'), $options);

        switch (array_search($menuChoice, $options))
        {
            case 1:
                $this->call('flashcard:new');
                break;
            case 2:
                $this->call('flashcard:list');
                break;
            case 3:
                $this->call('flashcard:practice');
                break;
            case 4:
                $this->call('flashcard:stats');
                break;
            case 5:
                $this->call('flashcard:reset');
                break;
            case 6:
                exit();
        }
        return 0;
    }
}
