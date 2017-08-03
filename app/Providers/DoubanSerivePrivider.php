<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DoubanSerivePrivider extends ServiceProvider {
	public function boot() {
	}
	public function register() {
		$this->app->bind ( 'App\Repositories\DoubanRepositoryInterface', 'App\Repositories\DoubanRepository' );
		$this->app->bind ( 'App\Repositories\DoubanCollectRepositoryInterface', 
				'App\Repositories\DoubanCollectRepository' );
	}
}
