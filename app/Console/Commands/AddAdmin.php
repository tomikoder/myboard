<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class AddAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant admin privileges to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::whereName($this->argument('user'))->first();
        $user->is_super_user = TRUE;
        $user->save();
    }
}
