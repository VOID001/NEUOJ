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
/*Route group need profile middleware*/
Route::group(['middleware' => 'profile'],function() {
    Route::get('/', [
        "as" => "home",
        "uses" => "HomeController@showHome",
    ]);

    Route::get('/auth', function () {
        return "This is the authenticate root";
    });

    Route::get('/profile/{user_id}', [
        "uses" => "UserController@showProfile"
    ])->where('user_id','[0-9]+');

    Route::get('/problem/{problem_id}', [
        "uses" => "ProblemController@getProblemByID"
    ])->where('problem_id', '[0-9]+');

    Route::match(['post', 'get'], '/problem/p/{page_id}', [
        "uses" => "ProblemController@getProblemListByPageID"
    ]);

    Route::match(['post', 'get'], '/problem', [
        "uses" => "ProblemController@getProblem"
    ]);


    Route::get('/status', [
        "uses" => "SubmissionController@getSubmission"
    ]);

    Route::get('/contest', function () {
        return "This is the contest root";
    });

    Route::get('/discuss', function () {
        return "This is the BBS root";
    });

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

        Route::get('/dashboard', function(){
            return Redirect::route('dashboard.profile');
        });

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

        Route::match(['post', 'get'], '/dashboard/contest/add/', [
            "as" => "contest.add",
            "middleware" => "role:admin",
            "uses" => "ContestController@addContest"
        ]);

        Route::get('/contest/{contest_id}', [
            "uses" => "ContestController@getContestByID"
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

    });
});
/*
 * RESTful API routes
 */

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

Route::put('/api/judgings/{id}',[
    "uses" => "RESTController@putJudgings"
]);

Route::get('api/testcase_files',[
    "uses" => "RESTController@getTestcaseFiles"
]);

Route::post('/api/judging_runs',[
    "uses" => "RESTController@postJudgingRuns"
]);
