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
use App\Post;
use Illuminate\Http\Request;

// Socialite FB login
Route::get('/login', 'SocialAuthController@login');
Route::get('/callback', 'SocialAuthController@callback');

// Welcome/Login Page
Route::get('/', function () {
    return view('welcome');
});

// Home
Route::get('/home', 'HomeController@homeView');
Route::post('home/{type}', 'PostController@post');
Route::delete('/home/comment/delete/{id}', 'PostController@deleteComment');

//Activity - my posts, delete posts etc
Route::get('/activity/myposts', 'MyContent@getInfo');
Route::delete('/activity/myposts/delete/{id}', 'MyContent@deleteInfo');

// Category/Post
Route::get('/c/{category}', 'PostController@catHome');

// API for Ajax
Route::get('/api/search/{data}', 'ApiController@categoryList');
Route::get('/api/getpost/{data}', 'ApiController@getPost');
Route::get('/api/getcomment/{data}', 'ApiController@getComment');
Route::get('/api/reply/{post}/{parent}/{msg}', 'ApiController@reply');
Route::get('/api/category/search/{data}', 'ApiController@search');
Route::post('/api/category/follow', 'ApiController@follow');
Route::delete('/api/category/unfollow', 'ApiController@unfollow');

// Post Updates
Route::post('/update/photo', 'PostController@editPhoto');
Route::post('/update/text', 'PostController@editText');
Route::post('/update/comment', 'PostController@editComment');



// Not yet used routes for messaging and logging out
Route::get('/messages/{id}', function ($id) {
    return view('messages',['id'=>$id]);
});
Route::get('/signout', function () {
    return view('signout');
});
