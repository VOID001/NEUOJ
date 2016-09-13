<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Userinfo;
use App\ContestUser;


class ClearGhost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghosts:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $userObj = User::all();
        foreach($userObj as $user)
        {
            if(UserInfo::where('uid', $user->uid)->first() == NULL)
            {
                User::where('uid', $user->uid)->delete();
                ContestUser::where('username', $user->username)->delete();
                $this->info("User $user->uid Deleted");
            }
        }
    }
}
