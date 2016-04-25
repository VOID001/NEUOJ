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
    ]);

    Route::match(['post', 'get'], '/problem', [
        "uses" => "ProblemController@getProblem"
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
        //"middleware" => "",
        "uses" => "AuthController@loginAction"
    ]);

    Route::match(['post', 'get'], '/auth/signup', [
        "as" => "signup",
        //"middleware" => "",
        "uses" => "AuthController@registAction"
    ]);

    Route::match(['post', 'get'], '/status/p/{page_id}', [
        "as" => "status",
        "uses" => "SubmissionController@getSubmissionListByPageID"
    ]);

    Route::get('/status/{run_id}', [
        "uses" => "SubmissionController@getSubmissionByID",
        "middleware" => "role:view",
    ])->where('run_id', '[0-9]+');

    Route::get('/ajax/submission', [
        "uses" => "SubmissionController@getSubmissionJSONByRunID"
    ]);

    Route::get('/contest', [
        "uses" => "ContestController@getContest"
    ]);

    Route::get('/contest/p/{page_id}', [
        "uses" => "ContestController@getContestListByPageID"
    ]);

    Route::get('/contest', [
        "uses" => "ContestController@getContest"
    ]);

    Route::get('/contest/p/{page_id}', [
        "uses" => "ContestController@getContestListByPageID"
    ]);


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
        ]);

        Route::match(['post', 'get'], '/dashboard/profile', [
            "as" => "dashboard.profile",
            "uses" => "UserController@setProfile"
        ]);

        Route::match(['post', 'get'], '/dashboard/settings', [
            "as" => "dashboard.settings",
            "uses" => "UserController@setSettings",
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

        Route::post('/submit/{problem_id}', [
            "as" => "submit",
            "uses" => "SubmissionController@submitAction"
        ]);

        Route::get('/dashboard/contest/', [
            "middleware" => "role:admin",
            "uses" => "ContestController@showContestDashboard"
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
        ])->where('problem_id', '[0-9]+');

        Route::get('/contest/{contest_id}', [
            "uses" => "ContestController@getContestByID"
        ]);

        Route::get('/ajax/contest/balloon', [
            "middleware" => "role:balloon",
            "uses" => "ContestController@getBalloonlist"
        ]);

        Route::get('/contest/{contest_id}/balloon', [
            "middleware" => "role:balloon",
            "uses" => "ContestController@getContestBalloonView"
        ]);

        Route::get('/contest/{contest_id}/balloon/{id}',[
            "middleware" => "role:balloon",
            "uses" =>"ContestController@changeContestBalloonStatus"
        ])->where([
            "contest_id" => "[0-9]+",
            "id" => "[0-9]+"
        ]);

        Route::get('/contest/{contest_id}/problem/{problem_id}', [
            "uses" => "ProblemController@getContestProblemByContestProblemID"
        ]);

        Route::post('/submit/{contest_id}/{problem_id}', [
            "uses" => "SubmissionController@contestSubmitAction"
        ]);

        Route::get('/contest/{contest_id}/ranklist', [
            "uses" => "ContestController@getContestRanklist"
        ]);

        Route::get('/contest/{contest_id}/ranklist/p/{page_id}', [
            "uses" => "ContestController@getContestRanklistByPageID"
        ]);

        Route::get('/contest/{contest_id}/status', [
            "uses" => "ContestController@getContestStatus"
        ]);

        Route::get('/contest/{contest_id}/status/p/{page_id}', [
            "uses" => "ContestController@getContestStatusByPageID"
        ]);

        Route::match(['post', 'get'], '/dashboard/contest/{contest_id}', [
            "middleware" => "role:admin",
            "uses" => "ContestController@setContest"
        ]);

        Route::post('/rejudge/{contest_id}/{problem_id}', [
            "middleware" => "role:admin",
            "uses" => "SubmissionController@rejudgeSubmissionByContestIDAndProblemID"
        ]);

        Route::post('/rejudge/{run_id}', [
            "middleware" => "role:admin",
            "uses" => "SubmissionController@rejudgeSubmissionByRunID"
        ]);

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
]);
