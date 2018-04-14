<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesCreateIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->index('username');
        });
        Schema::table('problems', function ($table) {
            $table->index('visibility_locks');
        });
        Schema::table('submissions', function ($table) {
            $table->index('pid');
            $table->index('uid');
            $table->index('cid');
            $table->index('result');
            $table->index('judge_status');
        });
        Schema::table('testcases', function ($table) {
            $table->index('pid');
        });
        Schema::table('contest_problems', function ($table) {
            $table->index('contest_id');
            $table->index('contest_problem_id');
        });
        Schema::table('contest_users', function ($table) {
            $table->index('contest_id');
        });
        Schema::table('threads', function ($table) {
            $table->index('cid');
            $table->index('pid');
        });
        Schema::table('sims', function ($table) {
            $table->index('runid');
        });
        Schema::table('train_problems', function ($table) {
            $table->index('train_id');
            $table->index('chapter_id');
        });
        Schema::table('train_users', function ($table) {
            $table->index('train_id');
        });
        Schema::table('contest_ranklist', function ($table) {
            $table->index('contest_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropIndex('users_username_index');
        });
        Schema::table('problems', function ($table) {
            $table->dropIndex('problems_visibility_locks_index');
        });
        Schema::table('submissions', function ($table) {
            $table->dropIndex('submissions_pid_index');
            $table->dropIndex('submissions_uid_index');
            $table->dropIndex('submissions_cid_index');
            $table->dropIndex('submissions_result_index');
            $table->dropIndex('submissions_judge_status_index');
        });
        Schema::table('testcases', function ($table) {
            $table->dropIndex('testcases_pid_index');
        });
        Schema::table('contest_problems', function ($table) {
            $table->dropIndex('contest_problems_contest_id_index');
            $table->dropIndex('contest_problems_contest_problem_id_index');
        });
        Schema::table('contest_users', function ($table) {
            $table->dropIndex('contest_users_contest_id_index');
        });
        Schema::table('threads', function ($table) {
            $table->dropIndex('threads_cid_index');
            $table->dropIndex('threads_pid_index');
        });
        Schema::table('sims', function ($table) {
            $table->dropIndex('sims_runid_index');
        });
        Schema::table('train_problems', function ($table) {
            $table->dropIndex('train_problems_train_id_index');
            $table->dropIndex('train_problems_chapter_id_index');
        });
        Schema::table('train_users', function ($table) {
            $table->dropIndex('train_users_train_id_index');
        });
        Schema::table('contest_ranklist', function ($table) {
            $table->dropIndex('contest_ranklist_contest_id_index');
        });
    }
}
