<?php

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */
// Route::get ( '/', function () {
// return view ( 'welcome' );
// } );
Route::get ( '/', 'HomeController@index' );
Auth::routes ();
// 退出有问题，这里手动添加route
Route::get ( '/logout', 'Auth\LoginController@logout' );
Route::get ( '/home', 'HomeController@index' )->name ( 'home' );
Route::get ( '/article/{id}', 'ArticleController@show' );
// 提交评论
Route::post ( 'comment', 'CommentController@store' );
// 提交评论
Route::get ( '/test', 'TestHtmlController@index' );

// 后台管理auth
Route::any ( 'admin/login', 'Admin\AuthController@login' );
Route::any ( 'admin/logout', 'Admin\AuthController@logout' );
Route::any ( 'admin/register', 'Admin\AuthController@register' );

// 后台管理
Route::group ( 
		[ 
				'middleware' => 'admin', // 注册了admin auth的中间件名为admin,http\Kernel.php $routeMiddleware
				'namespace' => 'Admin',
				'prefix' => 'admin' 
		], 
		function () {
			Route::get ( '/', 'HomeController@index' );
			Route::get ( '/clearCache', 'HomeController@clearCache' );
			// Route::get ( 'article', 'ArticleController@index' );
			// 修改为restful风格
			Route::resource ( 'article', 'ArticleController' );
			Route::resource ( 'comment', 'CommentController' );
		} );

// 豆瓣采集 前台展现 相关处理
Route::group ( [ 
		'namespace' => 'Douban',
		'prefix' => 'db' 
], 
		function () {
			Route::get ( '/', 'IndexCategoryController@index' );
			Route::resource ( 'cat', 'IndexCategoryController', 
					[ 
							'only' => [ 
									'index',
									'show' 
							] 
					] );
			Route::resource ( 'show', 'ArticleController', [ 
					'only' => [ 
							'show' 
					] 
			] );
		} );
// 工具类 controller
Route::group ( [ 
		'namespace' => 'tool',
		'prefix' => 'tool' 
], function () {
	Route::get ( 'rate/index', 'RateController@index' );
} );