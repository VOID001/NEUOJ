<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ContestUser;
use App\User;

class correctContestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contestuser:correct';

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
        $contestUserObj = ContestUser::where('user_id', 0)->get();
        foreach($contestUserObj as $contestUser)
        {
            $userObj = User::where('username', $contestUser->username)->first();
            if($userObj != NULL)
            {
                ContestUser::where(['username' => $contestUser->username, 'contest_id' => $contestUser->contest_id])->update(['user_id' => $userObj->uid]);
                $this->info("User $contestUser->username in contest $contestUser->contest_id corrected, uid=$userObj->uid");
            }
        }
    }
}
