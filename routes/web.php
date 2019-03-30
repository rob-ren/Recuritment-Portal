<?php

use Illuminate\Support\Facades\Redirect;

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
    return Redirect::route('login');
});

Route::get('candidate/add', 'CandidateController@create')->name('candidateCreate');

Route::get('candidate/add/position/{position_id}', 'CandidateController@create')->name('candidateCreateWithPosition');

Route::post('candidate/store', 'CandidateController@store')->name('candidateCreated');

Route::post('candidate/all', 'CandidateController@query')->name('candidateQuery');

Route::get('candidate/all', 'CandidateController@index')->name('candidateList');

Route::get('candidate/{candidate_id}', 'CandidateController@edit')->name('candidateUpdate');

Route::post('candidate/{candidate_id}', 'CandidateController@update')->name('candidateUpdated');

Route::get('candidate/cv/{file_path}', 'CandidateController@downloadCV')->name('candidateCVDownload');

Route::get('position/add', 'PositionController@create')->name('positionCreate');

Route::post('position/store', 'PositionController@store')->name('positionCreated');

Route::post('position/all', 'PositionController@query')->name('positionQuery');

Route::get('position/all', 'PositionController@index')->name('positionList');

Route::get('position/{position_id}', 'PositionController@edit')->name('positionUpdate');

Route::post('position/{position_id}', 'PositionController@update')->name('positionUpdated');

Route::post('position/{position_id}/candidate', 'PositionCandidateController@store')->name('positionCandidateCreated');

Route::get('position_candidate/{position_candidate_id}', 'PositionCandidateController@edit')->name('positionCandidateUpdate');

Route::post('position_candidate/remove/{position_candidate_id}', 'PositionCandidateController@delete')->name('positionCandidateDeleted');

Route::post('position_candidate/{position_candidate_id}', 'PositionCandidateController@update')->name('positionCandidateUpdated');

Route::get('client/all', 'ClientController@index')->name('clientList');

Route::post('client/add', 'ClientController@store')->name('clientCreated');

Route::post('client/{client_id}', 'ClientController@update')->name('clientUpdated');

Route::get('recruiter/all', 'RecruiterController@index')->name('recruiterList');

Route::post('recruiter/add', 'RecruiterController@store')->name('recruiterCreated');

Route::post('recruiter/{recruiter_id}', 'RecruiterController@update')->name('recruiterUpdated');

Route::get('role/all', 'RoleController@index')->name('roleList');

Route::post('role/add', 'RoleController@store')->name('roleCreated');

Route::post('role/{role_id}', 'RoleController@update')->name('roleUpdated');

Route::get('users/all', 'UserController@index')->name('userList');

Route::post('users/add', 'UserController@store')->name('userCreated');

Route::post('users/{user_id}', 'UserController@update')->name('userUpdated');

Auth::routes();

Route::get('/home', 'PositionController@index')->name('home');

Route::post('/login', [
  'uses' => 'Auth\LoginController@login',
  'middleware' => 'checkstatus',
]);