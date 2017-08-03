<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 生成模型类：php artisan make:model Article
 *
 * 下述是laravel集成的auth
 * 生成Auth验证类：php artisan make:auth
 *
 * @author wx
 *        
 */
class Article extends Model {
	
	public function hasManyComments() {
		// 关联模型，关联模型的外键id，本模型的id
		return $this->hasMany ( 'App\Comment', 'article_id', 'id' );
	}
}
