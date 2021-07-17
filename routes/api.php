<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user', function() {
  return Auth::user();
})->name('user');

Route::group(['middleware'=> 'auth'], function() {
  Route::apiResources([
    'food' => 'Foodcontroller',
    'menu' => 'MenuController',
    'monthmenu' => 'MonthMenuController'
  ]);
});
