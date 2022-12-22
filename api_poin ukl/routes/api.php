<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//user
Route::post('register','UserController@register');
Route::post('login','userController@login');
Route::delete('user/delete/{id}', "UserController@delete");
Route::put('user/{id}', "UserController@Update");
Route::get('user/check','userController@getAuthenticatedUser');
Route::get('user/data', "UserController@index");

//siswa
Route::get('siswa/data','SiswaController@index');
Route::get('siswa/{id}','SiswaController@show');
Route::post('siswa/tambah','SiswaController@store');
Route::put('siswa/{id}','SiswaController@update');
Route::delete('siswa/{id}','SiswaController@destroy');

//Poin Siswa
Route::get('poin_siswa/data', 'PoinSiswaController@index');
Route::get('poin_siswa/{id}', 'PoinSiswaController@show');
Route::get('poin_siswa/siswa/{id}', 'PoinSiswaController@detail');
Route::post('poin_siswa/tambah', 'PoinSiswaController@store');
Route::put('poin_siswa/{id}', 'PoinSiswaController@update');
Route::delete('poin_siswa/{id}', 'PoinSiswaController@destroy');
Route::post('poin_siswa', 'PoinSiswaController@findPoin');


//pelanggaran
Route::get('pelanggaran/data', 'PelanggaranController@index');
Route::get('pelanggaran/{id}', 'PelanggaranController@show');
Route::post('pelanggaran/tambah', 'PelanggaranController@store');
Route::put('pelanggaran/{id}', 'PelanggaranController@update');
Route::delete('pelanggaran/{id}', 'PelanggaranController@destroy');


//dashboard
Route::get('dashboard/statistik','DashboardController@dashboard');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
