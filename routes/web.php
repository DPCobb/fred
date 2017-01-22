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

/**
 * Daniel Cobb
 * ASL - nmbley v2.0
 * 1-22-2017
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
Route::get('/links', 'HomeController@links');
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
Route::post('/api/reply', 'ApiController@reply');
Route::get('/api/category/search/{data}', 'ApiController@search');
Route::post('/api/category/follow', 'ApiController@follow');
Route::delete('/api/category/unfollow', 'ApiController@unfollow');
Route::post('/api/like', 'ApiController@like');
Route::delete('/api/unlike', 'ApiController@unlike');
Route::post('/api/newcategory', 'ApiController@newCategory');
Route::post('/api/message/send', 'ApiController@sendMessage');
Route::get('/api/message/mail', 'ApiController@gotMail');
Route::post('/api/message/markread', 'ApiController@read');
Route::post('/api/message/deleteread', 'ApiController@deleteMsgR');
Route::post('/api/message/deletesend', 'ApiController@deleteMsgS');
Route::post('/api/message/reply', 'ApiController@replyMessage');

//Route::get('/api/home', 'ApiController@homeView');

// Post Updates
Route::post('/update/photo', 'PostController@editPhoto');
Route::post('/update/text', 'PostController@editText');
Route::post('/update/comment', 'PostController@editComment');

// Messages View
Route::get('/messages/{id}', 'MessageController@messageView');

// Not yet used route for logging out
Route::get('/signout', function () {
    return view('signout');
});
