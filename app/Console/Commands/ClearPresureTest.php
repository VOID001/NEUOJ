<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Submission;
use Storage;

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
        $files = Storage::allFiles("/submissions");
        foreach($files as $file)
        {
            if(preg_match("/".$uid."-test_[0-9]*-[0-9]*-[0-9]*.cpp/", $file))
            {
                Storage::delete($file);
            }
        }
        $this->info("Presure test clear complete");
    }
}
