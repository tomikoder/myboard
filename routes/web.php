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

Route::get('/', 'PostController@index')->name('home');
Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::get('login', function () {
    return redirect()->route("home");
});
Route::get('register', function () {
    return redirect()->route("home");
});
Route::get('logout', 'Auth\LoginController@logout');
Route::get('post/{link}', 'PostController@detail');
Route::post('delete/post{post}', 'PostController@destroy')->middleware('auth');
Route::post('edit/post{post}', 'PostController@update')->middleware('auth');
Route::get('delete/comment{comment}', 'PostController@commentDel')->middleware('auth');
Route::post('edit/comment{comment}', 'PostController@commentEdit')->middleware('auth');
Route::post('like/post{post}', 'PostController@postLike')->middleware('auth');
Route::post('ulike/post{post}', 'PostController@postUlike')->middleware('auth');
Route::get('search', 'PostController@search');
Route::get('posts/{user}', 'PostController@user_posts');
Route::get('notifications', 'UserController@user_notifications')->middleware('auth')->name('notify');
Route::get('panel/admin', 'UserController@admin_panel')->name('admin_panel')->middleware('auth');
Route::post('panel/admin/users', 'UserController@admin_panel_user_action')->name('admin_panel_users')->middleware('auth');
Route::post('panel/admin/posts', 'UserController@admin_panel_posts_action')->name('admin_panel_posts')->middleware('auth');
Route::get('panel/{user}', 'UserController@user_public_panel');
Route::get('panel/your/account', 'UserController@user_private_panel')->middleware('auth')->name('private_panel');
Route::post('send/message/{user}', 'UserController@send_private_message')->middleware('auth');
Route::post('send/message/reply/{m_link}', 'UserController@send_private_message_reply')->middleware('auth');
Route::get('read/message/{m_link}', 'UserController@read_private_message')->middleware('auth');
Route::get('you/messages', 'UserController@list_your_private_messages')->middleware('auth')->name('your_messages');
Route::group(['middleware' => ['auth',]], function () {
    Route::post('add/post', 'PostController@store');
    Route::post('comment/post{post}', 'PostController@commentAdd');
    Route::post('comment{comment}/post{post}', 'PostController@commentReply');
});
Route::post('change/password', 'UserController@change_pass')->middleware('auth')->name('change_pass');
Route::post('remove/account', 'UserController@remove_account')->middleware('auth')->name('remove_account');
Route::get('/home', 'HomeController@index');
Auth::routes();


