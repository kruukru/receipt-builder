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
	Route::name("retrieve-product-one")->get("/retrieve-product-one", "JSONController@getProductOne");

	Route::name("print-receipt")->post("/print-receipt", "PrintController@postPrintReceipt");

	Route::name('product')->get("/product", "ProductController@getProduct");
	Route::name("product-save")->post("/product/save", "ProductController@postSaveProduct");
	Route::name("product-remove")->post("/product/remove", "ProductController@postRemoveProduct");

	Route::group(['middleware' => "App\Http\Middleware\SuperAdminMiddleware"], function() {
		Route::name("retrieve-account-one")->get("/retrieve-account-one", "JSONController@getAccountOne");

		Route::name("admin-account")->get("/admin", "AccountController@getAccount");
		Route::name("admin-account-save")->post("/admin/save", "AccountController@postSaveAccount");
		Route::name("admin-account-remove")->post("/admin/remove", "AccountController@postRemoveAccount");
	});
});