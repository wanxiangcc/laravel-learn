<?php

namespace App\Douban;

use Illuminate\Database\Eloquent\Model;

class DoubanArticle extends Model {
	
	/**
	 * 对应表结构
	 * 默认为模型的复数名，模型名多个s
	 *
	 * @var string
	 */
	// protected $table = "douban_articles";
	public function belongAuthor() {
		return $this->belongsTo ( 'App\Douban\DoubanAuthor', 'author_id', 'id' );
	}
}
