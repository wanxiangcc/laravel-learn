<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller {
	public function index() {
		return view ( 'admin/home' );
	}
	/**
	 * 清除所有缓存
	 */
	public function clearCache() {
		Cache::flush ();
		return back ();
	}
}
