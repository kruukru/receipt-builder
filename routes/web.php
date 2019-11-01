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

Route::name("home")->get("/", "HomeController@index");

Route::name("login")->post("/login", "AccountController@postLogin");
Route::name("logout")->get("/logout", "AccountController@getLogout");

Route::group(['middleware' => ['auth']], function() {
	Route::group(['middleware' => "App\Http\Middleware\SuperAdminMiddleware"], function() {
		Route::name("retrieve-account-one")->get("/retrieve-account-one", "JSONController@getAccountOne");

		Route::name("admin-account")->get("/admin", "AccountController@getAccount");
		Route::name("admin-account-save")->post("/admin/save", "AccountController@postSaveAccount");
		Route::name("admin-account-remove")->post("/admin/remove", "AccountController@postRemoveAccount");
	});
});