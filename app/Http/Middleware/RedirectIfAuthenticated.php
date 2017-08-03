<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated {
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request        	
	 * @param \Closure $next        	
	 * @param string|null $guard        	
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
		if (Auth::guard ( $guard )->check ()) {
			// 如果已登录访问登录页面，这里需要重定向 2017-07-31 wanxiang
			if ($guard == 'admin') {
				return redirect ( '/admin' );
			} else {
				return redirect ( '/home' );
			}
		}
		return $next ( $request );
	}
}
