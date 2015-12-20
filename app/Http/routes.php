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

    Route::get('/profile', function () {
        return "This is the profile root";
    });

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
        "middleware" => "role",
    ]);

    /*Route group need auth middleware*/
    Route::group(['middleware' => 'auth'], function () {

        Route::get('/auth/logout', [
            "as" => "logout",
            "uses" => "AuthController@logoutAction"
        ]);

        Route::get('/dashboard/problem/', [
            "uses" => "ProblemController@showProblemDashboard",
        ]);

        Route::get('/dashboard/problem/p/{page_id}', [
            "as" => "dashboard.problem",
            "uses" => "ProblemController@showProblemDashboardByPageID",
        ]);

        Route::match(['post', 'get'], '/dashboard/profile', [
            "as" => "dashboard.profile",
            "uses" => "UserController@setProfile"
        ]);

        Route::match(['post', 'get'], '/dashboard/problem/{problem_id}', [
            "uses" => "ProblemController@setProblem",
        ])->where('problem_id', '[0-9]+');

        Route::delete('/dashboard/problem/{problem_id}', [
            "uses" => "ProblemController@delProblem"
        ])->where('problem_id', '[0-9]+');

        Route::match(['post', 'get'], '/dashboard/problem/add', [
            "uses" => "ProblemController@addProblem"
        ]);

        Route::post('/submit/{problem_id}', [
            "as" => "submit",
            "uses" => "SubmissionController@submitAction"
        ]);

        Route::get('/dashboard/contest/', [
            "uses" => "ContestController@showContestDashboard"
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
