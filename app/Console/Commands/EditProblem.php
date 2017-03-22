<?php

namespace App\Console\Commands;

use App\Problem;
use Illuminate\Console\Command;

class EditProblem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'problem:sed {col_name} {from} {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit the info of problems';

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
        if ($this->argument('col_name') != NULL && $this->argument('from') != NULL && $this->argument('to') != NULL) {
            $colName = $this->argument('col_name');
            $from = $this->argument(('from'));
            $to = $this->argument('to');
        }
        if ($colName == NULL || $to == NULL || $from == NULL) {
            $this->error("Missing params!");
            return;
        }
        $problemObjNum = Problem::where($colName, $from)->count();
        if ($problemObjNum == 0) {
            $this->error("Problem doesn't exist!");
            return;
        }
        Problem::where($colName, $from)->update([$colName => $to]);
        $this->info("$problemObjNum problems have changed $colName from $from to $to.");
    }
}
