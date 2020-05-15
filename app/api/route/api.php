<?php

use think\facade\Route;

// 发送短信
Route::rule('smscode', 'Sms/code','POST');
// 用户登录
Route::rule('login', 'Login/userLogin', "POST");
// 用户退出登录
Route::rule('logout', 'Logout/logout', 'GET');
// 栏目
//Route::rule('category', "Category/index");
//
Route::rule("lists", "mall.lists/index");
// 推荐商品
Route::rule("recommend", "mall.lists/recommend");
//
Route::rule("subcategory/:id", "category/sub");

Route::rule("detail/:id", "mall.detail/index");


//Route::rule('detail', 'Login/index2','GET')->middleware(app\admin\middleware\Param::class);
Route::resource('user', 'User')->except(['read']);

// 地址
Route::rule('address', 'User/address', "GET");


// 订单模块
Route::resource('order', 'order.index');
