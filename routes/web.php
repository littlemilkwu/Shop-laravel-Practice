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
// 首頁
Route::get('/', "homeController@indexPage");

// user
Route::group(["prefix" => "user"], function(){
    Route::group(["prefix" => "auth"], function(){
        Route::get("/sign-up", "UserAuthController@signUpPage");
        Route::post("/sign-up", "UserAuthController@signUpProcess");
        Route::get("/sign-in", "UserAuthController@signInPage");
        Route::post("/sign-in", "UserAuthController@signInProcess");
        Route::get("/sign-out", "UserAuthController@signOut");
    });
});

// merchandise
Route::group(["prefix" => "merchandise"], function(){
    // 商品清單檢視
    Route::get("/", "MerchandiseController@merchandiseListPage");
    // 商品資料新增，管理員
    Route::get("/create", "MerchandiseController@merchandiseCreateProcess")->middleware(['user.auth.admin']);
    // 商品編輯清單，管理員
    Route::get("/manage", "MerchandiseController@merchandiseManageListPage")->middleware(['user.auth.admin']);
    Route::group(["prefix" => "{m_id}"], function(){
        // 單品檢視
        Route::get("/", "MerchandiseController@merchandiseItemPage");
        // 單品編輯頁面，管理員
        Route::get("/edit", "MerchandiseController@merchandiseItemEditPage")->middleware(['user.auth.admin']);
        // 單品修改資料，管理員
        Route::put("/", "MerchandiseController@merchandiseUpdateProcess")->middleware(['user.auth.admin']);
        // 購買商品，會員身份
        Route::post("/buy", "MerchandiseController@merchandiseItemBuyProcess")->middleware(['user.auth']);
    });
});

// transaction
Route::get("/transaction", "TransactionController@transactionListPage")->middleware(['user.auth']);
