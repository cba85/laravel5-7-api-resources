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

/*
DB::listen(function ($query) {
    dump($query->sql);
});
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
Route::get('/user/{user}', function (\App\User $user) {
    return $user;
});
*/

Route::get('/user/{user}', function (\App\User $user) {
    return new \App\Http\Resources\UserResource($user);
});


Route::get('/topics', function () {
    //return \App\Http\Resources\TopicResource::collection(\App\Topic::get());
    //return new \App\Http\Resources\TopicCollection(\App\Topic::get());
    //return \App\Http\Resources\TopicResource::collection(\App\Topic::paginate(3));
    //return new \App\Http\Resources\TopicCollection(\App\Topic::paginate(3));
    //return new \App\Http\Resources\TopicCollection(\App\Topic::get());
    //return new \App\Http\Resources\TopicCollection(\App\Topic::with(['user', 'posts'])->get());
    return new \App\Http\Resources\TopicCollection(\App\Topic::with(['user'])->get());
});

Route::get('/u', function () {
    return (new \App\Http\Resources\UserResource(\App\User::find(1)))->additional([
        'meta' => [
            'token' => '123456789'
        ]
    ]);
});
