<?php
use think\facade\Route;

// 首页
Route::get('/', 'index/index/index');

// 商品路由
Route::get('product', 'index/product/index');
Route::get('product/index', 'index/product/index');
Route::get('product/detail/:id', 'index/product/detail');
Route::get('product/create', 'index/product/create');
Route::post('product/save', 'index/product/save');
Route::get('product/edit/:id', 'index/product/edit');
Route::post('product/update/:id', 'index/product/update');
Route::get('product/complete/:id', 'index/product/complete');
Route::get('product/cancel/:id', 'index/product/cancel');

// 求购路由
Route::get('wanted', 'index/wanted/index');
Route::get('wanted/index', 'index/wanted/index');
Route::get('wanted/detail/:id', 'index/wanted/detail');
Route::get('wanted/create', 'index/wanted/create');
Route::post('wanted/save', 'index/wanted/save');
Route::get('wanted/edit/:id', 'index/wanted/edit');
Route::post('wanted/update/:id', 'index/wanted/update');
Route::get('wanted/complete/:id', 'index/wanted/complete');
Route::get('wanted/cancel/:id', 'index/wanted/cancel');

return [];
