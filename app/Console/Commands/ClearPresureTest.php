<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Submission;

class ClearPresureTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:clear {--uid=3363 : Test Uid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear submissions in presure test';

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
        $uid = $this->option('uid');
        Submission::where('uid', $uid)->delete();
        $this->info("Presure test clear complete");
    }
}
