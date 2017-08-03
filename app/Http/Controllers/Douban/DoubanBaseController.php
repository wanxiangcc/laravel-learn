<?php

namespace App\Http\Controllers\Douban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Link;

class DoubanBaseController extends Controller {
	public function __construct() {
		// parent::__construct ();
		view ()->share ( 
				[ 
						'config' => [ 
								'site_name' => 'YangMao.group',
								'domain' => $_SERVER ['HTTP_HOST'],
								'description' => '',
								'icp' => '' 
						],
						'footerLinks' => Cache::get ( 'footerLinks', 
								function () {
									$data = Link::orderBy ( 'sort_order', 'DESC' )->where ( 'status', 1 )->take ( 5 )->get ();
									Cache::put ( 'footerLinks', $data, 24 * 60 ); // 第三个参数为分钟数
									return $data;
								} ) 
				] );
		// laravel cache 使用
		// https://docs.golaravel.com/docs/5.4/cache/
		// https://laravel-china.org/topics/2466/laravel-configuration-under-the-redis-so-that-the-cache-session-each-use-a-different-redis-database
	}
}
