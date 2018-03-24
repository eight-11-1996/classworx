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

//Test
Route::get('/test/test', 'TestController@test');
Route::post('/test/create', 'TestController@create');
Route::get('/test/retrieve', 'TestController@retrieve');
Route::post('/test/update', 'TestController@update');
Route::post('/test/delete', 'TestController@delete');



//Lesson
Route::get('/lesson/test', 'LessonController@test');
Route::post('/lesson/create', 'LessonController@create');
Route::post('/lesson/retrieve', 'LessonController@retrieve');
Route::post('/lesson/update', 'LessonController@update');
Route::post('/lesson/delete', 'LessonController@delete');
Route::post('/lesson/dashboard', 'LessonController@dashboard');


//Word Audio
Route::get('/word_audio/test', 'WordAudioController@test');
Route::post('/word_audio/create', 'WordAudioController@create');
Route::post('/word_audio/retrieve', 'WordAudioController@retrieve');
Route::post('/word_audio/update', 'WordAudioController@update');
Route::post('/word_audio/delete', 'WordAudioController@delete');
Route::post('/word_audio/deleteFile/{filename}', 'WordAudioController@deleteAudioFile');
Route::post('/word_audio/dashboard', 'WordAudioController@dashboard');


//Content Audio
Route::get('/content/test', 'ContentController@test');
Route::post('/content/create', 'ContentController@create');
Route::post('/content/retrieve', 'ContentController@retrieve');
Route::post('/content/update', 'ContentController@update');
Route::post('/content/delete', 'ContentController@delete');
Route::post('/content/dashboard', 'ContentController@dashboard');


//Answer Audio
Route::get('/sentence_popup/test', 'SentencePopupController@test');
Route::post('/sentence_popup/create', 'SentencePopupController@create');
Route::post('/sentence_popup/retrieve', 'SentencePopupController@retrieve');
Route::post('/sentence_popup/update', 'SentencePopupController@update');
Route::post('/sentence_popup/delete', 'SentencePopupController@delete');
Route::post('/sentence_popup/dashboard', 'SentencePopupController@dashboard');


//Answer Audio
Route::get('/word_popup/test', 'WordPopupController@test');
Route::post('/word_popup/create', 'WordPopupController@create');
Route::post('/word_popup/retrieve', 'WordPopupController@retrieve');
Route::post('/word_popup/update', 'WordPopupController@update');
Route::post('/word_popup/delete', 'WordPopupController@delete');
Route::post('/word_popup/dashboard', 'WordPopupController@dashboard');



//Save Words
Route::get('/save/test', 'SaveController@test');
Route::post('/save/create', 'SaveController@create');
Route::post('/save/retrieve', 'SaveController@retrieve');
Route::post('/save/custom_retrieve', 'SaveController@customRetrieve');
Route::post('/save/update', 'SaveController@update');
Route::post('/save/delete', 'SaveController@delete');
Route::post('/save/retrieve_history', 'SaveController@retrieveHistory');


//Save Contents
Route::get('/save_content/test', 'SaveContentController@test');
Route::post('/save_content/create', 'SaveContentController@create');
Route::post('/save_content/retrieve', 'SaveContentController@retrieve');
Route::post('/save_content/custom_retrieve', 'SaveContentController@customRetrieve');
Route::post('/save_content/update', 'SaveContentController@update');
Route::post('/save_content/delete', 'SaveContentController@delete');
Route::post('/save_content/retrieve_history', 'SaveContentController@retrieveHistory');



//Accent Audios
Route::get('/accent_audio/test', 'AccentAudioController@test');
Route::post('/accent_audio/create', 'AccentAudioController@create');
Route::post('/accent_audio/retrieve', 'AccentAudioController@retrieve');
Route::post('/accent_audio/custom_retrieve', 'AccentAudioController@customRetrieve');
Route::post('/accent_audio/update', 'AccentAudioController@update');
Route::post('/accent_audio/delete', 'AccentAudioController@delete');

//Accent Videos
Route::get('/accent_video/test', 'AccentVideoController@test');
Route::post('/accent_video/create', 'AccentVideoController@create');
Route::post('/accent_video/retrieve', 'AccentVideoController@retrieve');
Route::post('/accent_video/custom_retrieve', 'AccentVideoController@customRetrieve');
Route::post('/accent_video/update', 'AccentVideoController@update');
Route::post('/accent_video/delete', 'AccentVideoController@delete');


//Files
Route::get('/audio_file/test', 'AudioFileController@test');
Route::post('/audio_file/create', 'AudioFileController@create');
Route::post('/audio_file/retrieve', 'AudioFileController@retrieve');
Route::post('/audio_file/update', 'AudioFileController@update');
Route::post('/audio_file/delete', 'AudioFileController@delete');

//Files
Route::get('/category/test', 'TopLessonController@test');
Route::post('/category/create', 'TopLessonController@create');
Route::post('/category/retrieve', 'TopLessonController@retrieve');
Route::post('/category/update', 'TopLessonController@update');
Route::post('/category/delete', 'TopLessonController@delete');

//Files
Route::get('/sub_category/test', 'CategoryLessonController@test');
Route::post('/sub_category/create', 'CategoryLessonController@create');
Route::post('/sub_category/retrieve', 'CategoryLessonController@retrieve');
Route::post('/sub_category/update', 'CategoryLessonController@update');
Route::post('/sub_category/delete', 'CategoryLessonController@delete');