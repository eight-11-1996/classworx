<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "heel";//view('welcome');
});
/*
  Accessing uploaded files
*/
Route::get('file/icon/{filename}', function ($filename)
{
  $path = storage_path('/icons/' . $filename);
  if (!File::exists($path)) {
      abort(404);
  }
  $file = File::get($path);
  $type = File::mimeType($path);
  $response = Response::make($file, 200);
  $response->header("Content-Type", $type);
  return $response;
});
Route::get('file/company/{filename}', function ($filename)
{
  $path = storage_path('/company/' . $filename);
  if (!File::exists($path)) {
      abort(404);
  }
  $file = File::get($path);
  $type = File::mimeType($path);
  $response = Response::make($file, 200);
  $response->header("Content-Type", $type);
  return $response;
});
Route::get('file/q_card/{filename}', function ($filename)
{
  $path = storage_path('/qcards/' . $filename);
  if (!File::exists($path)) {
      abort(404);
  }
  $file = File::get($path);
  $type = File::mimeType($path);
  $response = Response::make($file, 200);
  $response->header("Content-Type", $type);
  return $response;
});
Route::get('storage/audio_files/{filename}', function ($filename)
{
    $path = storage_path('/app/audioFiles/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Route::get('/cache', function () {
    $exitCode = Artisan::call('config:cache');
    return 'hey'.$exitCode;

    //
});
Route::get('/clear', function () {
    $exitCode = Artisan::call('config:cache');
    return 'hey'.$exitCode;

    //
});
Route::get('/migrate', function () {
    $exitCode = Artisan::call('migrate');
    return 'hey'.$exitCode;

    //
});

/* Authentication Router */
Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
Route::post('authenticate/refresh', 'AuthenticateController@refreshToken');
Route::post('authenticate/invalidate', 'AuthenticateController@deauthenticate');


//Accounts
Route::post('/accounts/create', "AccountController@create");
Route::post('/accounts/retrieve', "AccountController@retrieve");
Route::post('/accounts/update', "AccountController@update");
Route::post('/accounts/delete', "AccountController@delete");
Route::get('/accounts/test', 'AccountController@test');


//Account Informations
Route::post('/account_informations/create', "AccountInformationController@create");
Route::post('/account_informations/retrieve', "AccountInformationController@retrieve");
Route::post('/account_informations/update', "AccountInformationController@update");
Route::post('/account_informations/delete', "AccountInformationController@delete");
Route::get('/account_informations/test', 'AccountInformationController@test');


//Account Profiles
Route::post('/account_profiles/create', "AccountProfileController@create");
Route::post('/account_profiles/retrieve', "AccountProfileController@retrieve");
Route::post('/account_profiles/update', "AccountProfileController@update");
Route::post('/account_profiles/delete', "AccountProfileController@delete");
Route::get('/account_profiles/test', 'AccountProfileController@test');



//Modules
Route::post('/modules/create', "ModuleController@create");
Route::post('/modules/retrieve', "ModuleController@retrieve");
Route::post('/modules/update', "ModuleController@update");
Route::post('/modules/delete', "ModuleController@delete");
Route::get('/modules/test', 'ModuleController@test');



//Semesters
Route::post('/semesters/create', "SemesterController@create");
Route::post('/semesters/retrieve', "SemesterController@retrieve");
Route::post('/semesters/update', "SemesterController@update");
Route::post('/semesters/delete', "SemesterController@delete");
Route::get('/semesters/test', 'SemesterController@test');

//Courses
Route::post('/courses/create', "CourseController@create");
Route::post('/courses/retrieve', "CourseController@retrieve");
Route::post('/courses/update', "CourseController@update");
Route::post('/courses/delete', "CourseController@delete");
Route::post('/courses/accounts', "CourseController@accounts");
Route::get('/courses/test', 'CourseController@test');

//Courses
Route::post('/enrolled_courses/create', "EnrolledAccountController@create");
Route::post('/enrolled_courses/retrieve', "EnrolledAccountController@retrieve");
Route::post('/enrolled_courses/update', "EnrolledAccountController@update");
Route::post('/enrolled_courses/delete', "EnrolledAccountController@delete");
Route::get('/enrolled_courses/test', 'EnrolledAccountController@test');


//Grades Settings
Route::post('/grade_settings/create', "GradeSettingController@create");
Route::post('/grade_settings/retrieve', "GradeSettingController@retrieve");
Route::post('/grade_settings/update', "GradeSettingController@update");
Route::post('/grade_settings/delete', "GradeSettingController@delete");
Route::get('/grade_settings/test', 'GradeSettingController@test');

//Exams
Route::post('/exams/create', "ExamController@create");
Route::post('/exams/retrieve', "ExamController@retrieve");
Route::post('/exams/update', "ExamController@update");
Route::post('/exams/delete', "ExamController@delete");
Route::get('/exams/test', 'ExamController@test');

//Quizes
Route::post('/quizzes/create', "QuizController@create");
Route::post('/quizzes/retrieve', "QuizController@retrieve");
Route::post('/quizzes/update', "QuizController@update");
Route::post('/quizzes/delete', "QuizController@delete");
Route::get('/quizzes/test', 'QuizController@test');

//Questions
Route::post('/questions/create', "QuestionController@create");
Route::post('/questions/retrieve', "QuestionController@retrieve");
Route::post('/questions/update', "QuestionController@update");
Route::post('/questions/delete', "QuestionController@delete");
Route::get('/questions/test', 'QuestionController@test');

//Question Options
Route::post('/question_options/create', "QuestionOptionController@create");
Route::post('/question_options/retrieve', "QuestionOptionController@retrieve");
Route::post('/question_options/update', "QuestionOptionController@update");
Route::post('/question_options/delete', "QuestionOptionController@delete");
Route::get('/question_options/test', 'QuestionOptionController@test');


//Resources
Route::post('/resources/create', "ResourceController@create");
Route::post('/resources/retrieve', "ResourceController@retrieve");
Route::post('/resources/update', "ResourceController@update");
Route::post('/resources/delete', "ResourceController@delete");
Route::get('/resources/test', 'ResourceController@test');

//Resource Viewers
Route::post('/resource_viewers/create', "ResourceViewerController@create");
Route::post('/resource_viewers/retrieve', "ResourceViewerController@retrieve");
Route::post('/resource_viewers/update', "ResourceViewerController@update");
Route::post('/resource_viewers/delete', "ResourceViewerController@delete");
Route::get('/resource_viewers/test', 'ResourceViewerController@test');



