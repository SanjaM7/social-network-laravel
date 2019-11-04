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

Auth::routes();

Route::resource('profiles', 'Profile\ProfileController');

Route::resource('friends', 'Friend\FriendController');
Route::post('friends/{id}/removeFriend', 'Friend\FriendController@removeFriend')->name('removeFriend');
Route::post('friends/{id}/declineFriendRequest', 'Friend\FriendController@declineFriendRequest')->name('declineFriendRequest');
Route::post('friends/{id}/withdrawFriendRequest', 'Friend\FriendController@withdrawFriendRequest')->name('withdrawFriendRequest');

Route::get('/', 'Timeline\TimelineController@index')->name('timeline');
Route::resource('statuses', 'Timeline\StatusController'); 
Route::resource('statuses/{status_id}/replies', 'Timeline\ReplyController'); 
Route::resource('statuses/{status_id}/likes', 'Timeline\LikeController');




