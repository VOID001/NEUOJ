<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Problem;
use App\Submission;

class correctSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submission:correct';

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
        $submissionObj = Submission::all();
        foreach($submissionObj as $submission)
        {
            if(Problem::where('problem_id', $submission->pid)->first() == NULL)
            {
                Submission::where('runid', $submission->runid)->delete();
                $this->info("Submission $submission->runid deleted");
            }
        }
        $this->info("Finish\n");
    }
}
