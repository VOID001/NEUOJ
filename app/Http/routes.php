<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\View;

/*Route group need profile middleware*/
Route::group(['middleware' => 'profile'],function() {

    Route::get('/', [
        "as" => "home",
        "uses" => "HomeController@showHome",
    ]);

    //Route::get('/auth', function () {
    //    return "This is the authenticate root";
    //});

    Route::match(['get', 'post'], '/auth/request', [
        "uses" => "AuthController@requestResetAction"
    ]);

    Route::match(['get', 'post'], '/auth/reset',[
        "uses" => "AuthController@resetPasswordAction"
    ]);

    Route::get('/auth/ssologin', [
        "sso" => "ssologin",
        "uses" => "SsoAuthController@casloginAction"
    ]);    

    Route::get('/profile/{user_id}', [
        "uses" => "UserController@showProfile"
    ])->where('user_id','[0-9]+');


    Route::match(['post', 'get'], '/problem/p/{page_id}', [
        "uses" => "ProblemController@getProblemListByPageID"
    ])->where('page_id', '[0-9]+');

    Route::match(['post', 'get'], '/problem', [
        "uses" => "ProblemController@getProblem"
    ]);

    Route::get('/chatroom', [
        "uses" => "ChatroomController@showChatroomIndex"
    ]);

    Route::post('/chatroom/send', [
        "uses" => "ChatroomController@sendMessage"
    ]);

    Route::post('/chatroom/record/{channel}/{recordCount}', [
        "uses" => "ChatroomController@getlastrecord"
    ]);

    Route::get('/status', [
        "uses" => "SubmissionController@getSubmission"
    ]);

    //Route::get('/contest', function () {
    //    return "This is the contest root";
    //});

    //Route::get('/discuss', function () {
    //    return "This is the BBS root";
    //});

    Route::match(['post', 'get'], '/auth/signin', [
        "as" => "signin",
        "middleware" => "nologin",
        "uses" => "AuthController@loginAction"
    ]);

    Route::match(['post', 'get'], '/auth/signup', [
        "as" => "signup",
        "middleware" => "nologin",
        "uses" => "AuthController@registAction"
    ]);

    Route::match(['post', 'get'], '/status/p/{page_id}', [
        "as" => "status",
        "uses" => "SubmissionController@getSubmissionListByPageID"
    ])->where('page_id', '[0-9]+');

    Route::get('/status/{run_id}', [
        "uses" => "SubmissionController@getSubmissionByID",
        "middleware" => "role:view-code",
    ])->where('run_id', '[0-9]+');

    Route::get('/ajax/submission', [
        "uses" => "SubmissionController@getSubmissionJSONByRunID"
    ]);

    Route::get('/ajax/problem_title', [
        "uses" => "ProblemController@getAllProblemTitleJSON"
    ]);

    Route::post('/ajax/memberlist', [
        "uses" => "ContestController@postMemberList"
    ]);

    Route::get('/contest', [
        "uses" => "ContestController@getContest"
    ]);

    Route::get('/contest/p/{page_id}', [
        "uses" => "ContestController@getContestListByPageID"
    ])->where('page_id', '[0-9]+');

    Route::get('/contest', [
        "uses" => "ContestController@getContest"
    ]);

    Route::get('/contest/p/{page_id}', [
        "uses" => "ContestController@getContestListByPageID"
    ])->where('page_id', '[0-9]+');

    Route::get('/ranklist', [
        "uses" => "RanklistController@getRanklist"
    ]);

    Route::get('/ranklist/p/{page_id}', [
        "uses" => "RanklistController@getRanklistByPageID"
    ])->where('page_id', '[0-9]+');


    /*Route group need auth middleware*/
    Route::group(['middleware' => 'auth'], function () {

        Route::get('/auth/logout', [
            "as" => "logout",
            "uses" => "AuthController@logoutAction"
        ]);

        Route::get('/problem/{problem_id}', [
            "uses" => "ProblemController@getProblemByID"
        ])->where('problem_id', '[0-9]+');


        Route::get('/problem/quick_access', [
            "uses" => "ProblemController@quickAccess"
        ]);

        Route::get('/dashboard', [
            "uses" => "UserController@getDashboardIndex"
        ]);

        Route::get('/dashboard/problem/', [
            "as" => "dashboard.problem",
            "middleware" => "role:admin",
            "uses" => "ProblemController@showProblemDashboard",
        ]);

        Route::get('/dashboard/problem/p/{page_id}', [
            "middleware" => "role:admin",
            "uses" => "ProblemController@showProblemDashboardByPageID",
        ])->where('page_id', '[0-9]+');

        Route::match(['post', 'get'], '/dashboard/profile', [
            "as" => "dashboard.profile",
            "uses" => "UserController@setProfile"
        ]);

        Route::match(['post', 'get'], '/dashboard/settings', [
            "as" => "dashboard.settings",
            "uses" => "UserController@setSettings",
        ]);

        Route::match(['post', 'get'], '/dashboard/settings/bind', [
            "uses" => "UserController@bindUser"
        ]);

        Route::get('/dashboard/judgehost', [
            "uses" => "JudgehostController@getIndex",
            "middleware" => "role:admin",
        ]);

        Route::get('/ajax/judgehost_status', [
            "uses" => "JudgehostController@getJudgeStatus",
        ]);

        Route::post('/ajax/judgehost_start', [
            "uses" => "JudgehostController@startAll",
        ]);

        Route::post('/ajax/judgehost_stop', [
            "uses" => "JudgehostController@stopAll",
        ]);

        Route::post('/ajax/judgehost_clean', [
            "uses" => "JudgehostController@cleanAll",
        ]);

        Route::match(['post', 'get'], '/dashboard/problem/{problem_id}', [
            "uses" => "ProblemController@setProblem",
            "middleware" => "role:admin",
        ])->where('problem_id', '[0-9]+');

        Route::delete('/dashboard/problem/{problem_id}', [
            "uses" => "ProblemController@delProblem",
            "middleware" => "role:admin",
        ])->where('problem_id', '[0-9]+');

        Route::match(['post', 'get'], '/dashboard/problem/add', [
            "middleware" => "role:admin",
            "uses" => "ProblemController@addProblem"
        ]);

        Route::get('/dashboard/executable', [
            "middleware" => "role:admin",
            "uses" => "ExecutableController@getExecutableDashboard"
        ]);

        Route::get('/dashboard/executable/{execId}', [
            "middleware" => "role:admin",
            "uses" => "ExecutableController@getExecutableFile"
        ]);

        Route::get('/dashboard/users', [
            "middleware" => "role:admin",
            "uses" => "UserController@getUserDashboard"
        ]);

        Route::get('/dashboard/users/toggle', [
            "middleware" => "role:admin",
            "uses" => "UserController@toggleUserPermission"
        ]);

        Route::post('/submit/{problem_id}', [
            "as" => "submit",
            "uses" => "SubmissionController@submitAction"
        ])->where('problem_id', '[0-9]+');

        Route::get('/dashboard/contest/', [
            "middleware" => "role:admin",
            "uses" => "ContestController@showContestDashboard"
        ]);

        Route::post('/dashboard/contest/randusers/{contest_id}/{school}/{count}', [
            "middleware" => "role:admin",
            "uses" => "ContestController@newContestRandomUsers"
        ])->where('problem_id', '[0-9]+');

        Route::delete('/dashboard/contest/randusers/{contest_id}',[
            "middleware" => "role:admin",
            "uses" => "ContestController@deleteContestRandomUsers"
        ]);

        Route::get('/dashboard/contest/p/{page_id}', [
            "middleware" => "role:admin",
            "uses" => "ContestController@showContestDashboardByPageID"
        ])->where('page_id', '[0-9]+');

        Route::match(['post', 'get'], '/dashboard/contest/add/', [
            "as" => "contest.add",
            "middleware" => "role:admin",
            "uses" => "ContestController@addContest"
        ]);

        Route::match(['post'], '/dashboard/problem/import', [
            "middleware" => "role:admin",
            "uses" => "ProblemController@importProblem"
        ]);

        Route::delete('/dashboard/contest/{contest_id}',[
            "middleware" => "role:admin",
            "uses" => "ContestController@deleteContest"
        ])->where('contest_id', '[0-9]+');

        Route::get('/contest/{contest_id}', [
            "uses" => "ContestController@getContestByID"
        ])->where('contest_id', '[0-9]+');

        Route::get('/ajax/contest/balloon', [
            "middleware" => "role:balloon",
            "uses" => "ContestController@getBalloonlist"
        ]);

        Route::get('/contest/{contest_id}/balloon', [
            "middleware" => "role:balloon",
            "uses" => "ContestController@getContestBalloonView"
        ])->where('contest_id', '[0-9]+');

        Route::get('/contest/{contest_id}/balloon/{id}',[
            "middleware" => "role:balloon",
            "uses" =>"ContestController@changeContestBalloonStatus"
        ])->where([
            "contest_id" => "[0-9]+",
            "id" => "[0-9]+"
        ]);

        Route::get('/contest/{contest_id}/problem/{problem_id}', [
            "uses" => "ProblemController@getContestProblemByContestProblemID"
        ])->where([
            "contest_id" => "[0-9]+",
            "problem_id" => "[0-9]+"
        ]);

        Route::post('/submit/{contest_id}/{problem_id}', [
            "uses" => "SubmissionController@contestSubmitAction"
        ])->where([
            "contest_id" => "[0-9]+",
            "problem_id" => "[0-9]+"
        ]);

        Route::get('/contest/{contest_id}/ranklist', [
            "uses" => "ContestController@getContestRanklist"
        ])->where('contest_id', '[0-9]+');

        Route::get('/contest/{contest_id}/ranklist/export', [
            "middleware" => "role:admin",
            "uses" => "ContestController@exportContestRanklist"
        ])->where('contest_id', '[0-9]+');

        Route::get('/contest/{contest_id}/ranklist/p/{page_id}', [
            "uses" => "ContestController@getContestRanklistByPageID"
        ])->where([
            'contest_id' => '[0-9]+',
            'page_id' => '[0-9]+'
        ]);

        Route::get('/contest/{contest_id}/status', [
            "uses" => "ContestController@getContestStatus"
        ])->where('contest_id', '[0-9]+');

        Route::get('/contest/{contest_id}/status/p/{page_id}', [
            "uses" => "ContestController@getContestStatusByPageID"
        ])->where([
            'contest_id' => '[0-9]+',
            'page_id' => '[0-9]+'
        ]);

        Route::match(['post', 'get'], '/dashboard/contest/{contest_id}', [
            "middleware" => "role:admin",
            "uses" => "ContestController@setContest"
        ])->where('contest_id', '[0-9]+');

        Route::post('/rejudge/{contest_id}/{problem_id}', [
            "middleware" => "role:admin",
            "uses" => "SubmissionController@rejudgeSubmissionByContestIDAndProblemID"
        ])->where([
            "contest_id" => "[0-9]+",
            "problem_id" => "[0-9]+"
        ]);

        Route::post('/rejudge/{run_id}', [
            "middleware" => "role:admin",
            "uses" => "SubmissionController@rejudgeSubmissionByRunID"
        ])->where('run_id', '[0-9]+');

        Route::match(['post', 'get'], '/contest/{contest_id}/register', [
            "uses" => "ContestController@registerContest"
        ])->where('contest_id', '[0-9]+');

        Route::get('/dashboard/problem/{problem_id}/visibility',[
            "middleware" => "role:admin",
            "uses" => "ProblemController@changeVisibility"
        ])->where('problem_id', '[0-9]+');

        Route::get('/storage/{path_name}', [
            "middleware" => "role:admin",
            "uses" => "StorageController@getStoredFile"
        ]);

        Route::get('/status/sim', [
            "middleware" => "role:admin",
            "uses" => "SubmissionController@getSim"
        ]);

        Route::get('/dashboard/system', [
            "middleware" => "role:admin",
            "uses" => "SystemController@getSystemSummary"
        ]);

        Route::get('/discuss/{contest_id}/{problem_id}',[
            "uses" => "ThreadController@getThreadByContestIDAndProblemID"
        ])->where([
            'contest_id' => '[0-9]+',
            'problem_id' => '[0-9]+'
        ]);

        Route::get('/discuss/t/{thread_id}',[
            "uses" => "ThreadController@getThreadByThreadID"
        ])->where('thread_id', '[0-9]+');

        Route::post('/discuss/add/{contest_id}/{problem_id}',[
            "uses" => "ThreadController@addThreadByContestIDAndProblemID"
        ])->where([
            'contest_id' => '[0-9]+',
            'problem_id' => '[0-9]+'
        ]);

        Route::post('/discuss/delete/{thread_id}',[
            "middleware" => "role:admin",
            "uses" => "ThreadController@deleteThreadByThreadID"
        ])->where('thread_id', '[0-9]+');

        Route::get('/discuss/{contest_id}',[
            "uses" => "ThreadController@getThreadByContestID"
        ])->where('contest_id', '[0-9]+');

        Route::get('/discuss/{contest_id}/p/{page_id}',[
            "uses" => "ThreadController@getThreadByContestIDAndPageID"
        ])->where([
            'contest_id' => '[0-9]+',
            'page_id' => '[0-9]+'
        ]);

        Route::get('/dashboard/training', [
            "middleware" => "role:admin",
            "uses" => "TrainingController@showTrainingDashboard"
        ]);

        Route::get('/dashboard/training/p/{page_id}', [
            "middleware" => "role:admin",
            "uses" => "TrainingController@showTrainingDashboard"
        ])->where('page_id', '[0-9]+');

        Route::get('/training', [
            "uses" => "TrainingController@getTrainingList"
        ]);
        Route::get('/training/p/1',[
            "uses" => "TrainingController@getTrainingList"
        ]);

        Route::get('/training/{train_id}', [
            "uses" => "TrainingController@getTrainingByID"
        ])->where('train_id', '[0-9]+');

        Route::get('/training/{train_id}/chapter{chapter_id}/{train_problem_id}', [
            "uses" => "ProblemController@getTrainProblemByTrainProblemID"
        ])->where([
            'train_id' => '[0-9]+',
            'chapter_id' => '[0-9]+',
            'train_problem_id' => '[0-9]+'
        ]);

        Route::match(['post','get'], '/dashboard/training/add', [
            "middleware" => "role:admin",
            "uses" => "TrainingController@addTraining"
        ]);

        Route::match(['post','get'], '/dashboard/training/{train_id}', [
            "middleware" => "role:admin",
            "uses" => "TrainingController@setTraining"
        ])->where('train_id', '[0-9]+');

        Route::delete('/dashboard/training/{train_id}', [
            "middleware" => "role:admin",
            "uses" => "TrainingController@deleteTraining"
        ])->where('train_id', '[0-9]+');

        Route::get('/training/{train_id}/ranklist/p/{page_id}', [
            "uses" => "TrainingController@getTrainingRanklistByPageID"
        ])->where([
            'train_id' => '[0-9]+',
            'page_id' => '[0-9]+'
        ]);

        Route::get('/training/{train_id}/update', [
            "uses" => "TrainingController@updateTrainingProgress"
        ])->where('train_id', '[0-9]+');

        Route::get('/training/{train_id}/updateall', [
            "middleware" => "role:admin",
            "uses" => "TrainingController@updateAllTrainingProgress"
        ])->where('train_id', '[0-9]+');

        Route::get('/ranklist/init', [
            "middleware" => "role:admin",
            "uses" => "RanklistController@initRanklist"
        ]);

        /*
         * Api to show messages that uses in front page
         */
        Route::get('/ajax/contests', [
            "uses" => "ContestController@getRunningContestsJson"
        ]);

        Route::get('/ajax/unfinished_problems', [
            "uses" => "ProblemController@getUnfinishedProblemsJson"
        ]);

        Route::get('/ajax/trainings', [
            "uses" => "TrainingController@getTrainingsJson"
        ]);

        Route::post('/ajax/executable', [
            "middleware" => "role:admin",
            "uses" => "ExecutableController@addExecutable"
        ]);

        Route::get('/ajax/executable', [
            "middleware" => "role:admin",
            "uses" => "ExecutableController@getExecutable"
        ]);

        Route::put('/ajax/executable', [
            "middleware" => "role:admin",
            "uses" => "ExecutableController@updateExecutable"
        ]);

        Route::delete('/ajax/executable', [
            "middleware" => "role:admin",
            "uses" => "ExecutableController@deleteExecutable"
        ]);

        Route::get('/ajax/user', [
            "uses" => "UserController@getUserJson"
        ]);
    });
});
/*
 * RESTful API routes
 *
 * Use Judge RoleCheck to Ensure the Data won't revealed
 */

Route::group(['middleware' => "role:judge"], function() {
    Route::post('/api/judgings', [
        "uses" => "RESTController@postJudgings"
    ]);

    Route::get('/api/config', [
        "uses" => "RESTController@getConfig"
    ]);

    Route::get('/api/submission_files', [
        "uses" => "RESTController@getSubmissionFiles"
    ]);

    Route::get('/api/testcases', [
        "uses" => "RESTController@getTestcases"
    ]);

    Route::get('/api/executable', [
        "uses" => "RESTController@getExecutable"
    ]);

    Route::any('/api/judgehosts', [
        "uses" => "RESTController@postJudgeHosts"
    ]);

    Route::put('/api/judgings/{id}', [
        "uses" => "RESTController@putJudgings"
    ]);

    Route::get('api/testcase_files', [
        "uses" => "RESTController@getTestcaseFiles"
    ]);

    Route::post('/api/judging_runs', [
        "uses" => "RESTController@postJudgingRuns"
    ]);
});

/*
 * Show avatars route
 */

Route::get('/avatar/{user_id}',[
    "uses" => "UserController@showAvatar"
])->where('user_id', '[0-9]+');
