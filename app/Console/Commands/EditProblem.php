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
    protected $signature = 'problem:edit {col_name} {after}';

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
        $updateCount = 0;

        if ($this->argument('col_name') != NULL && $this->argument('col_name') != NULL) {
            $colName = $this->argument('col_name');
            $after = $this->argument('after');
        }
        if ($colName == NULL || $after == NULL) {
            $this->error("Missing params!");
            return;
        }
        $problemObj = Problem::all();
        $problemObjNum = Problem::all()->count();
        if ($problemObjNum == 0) {
            $this->error("Problem doesn't exist!");
            return;
        }
        for ($i = 0; $i < $problemObjNum; $i++) {
            if ($problemObj[$i][$colName] != $after) {
                Problem::where('problem_id', $problemObj[$i]->problem_id)->update([$colName => $after]);
                $updateCount++;
            }
        }
        $this->info("$updateCount Problems has changed $colName to $after.");
    }
}
