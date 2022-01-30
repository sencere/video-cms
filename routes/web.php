<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('medium/{filename}', function ($filename)
{
    $path = storage_path('uploads/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('/home', 'HomeController@index');

Route::post('/webhook/encoding', 'EncodingWebhookController@handle');

Route::get('/videos/{video}', 'VideoController@show');

Route::post('/videos/{video}/views', 'VideoViewController@create');

Route::get('/search', 'SearchController@index');

Route::get('/videos/{video}/votes', 'VideoVoteController@show');

Route::get('/videos/{video}/comments', 'VideoCommentController@index');

Route::get('/subscription/{channel}', 'ChannelSubscriptionController@show');

Route::get('/channel/{channel}', 'ChannelController@show');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/upload', 'VideoUploadController@index');
    Route::post('/upload', 'VideoUploadController@store');

    Route::get('/videos', 'VideoController@index');
    Route::post('/videos', 'VideoController@store');
    Route::delete('/videos/{video}', 'VideoController@delete');
    Route::get('/videos/{video}/edit', 'VideoController@edit');
    Route::put('/videos/{video}', 'VideoController@update');

    Route::get('/channel/{channel}/edit', 'ChannelSettingsController@edit');
    Route::put('/channel/{channel}/edit', 'ChannelSettingsController@update');

    Route::post('/videos/{video}/votes', 'VideoVoteController@create');
    Route::delete('/videos/{video}/votes', 'VideoVoteController@remove');

    Route::post('/videos/{video}/comments', 'VideoCommentController@create');
    Route::delete('/videos/{video}/comments/{comment}', 'VideoCommentController@delete');

    Route::post('/subscription/{channel}', 'ChannelSubscriptionController@create');
    Route::delete('/subscription/{channel}', 'ChannelSubscriptionController@delete');


});
