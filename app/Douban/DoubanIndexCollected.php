<?php

namespace App\Douban;

use Illuminate\Database\Eloquent\Model;

class DoubanIndexCollected extends Model {
	/**
	 * 对应表结构
	 * 默认为模型的复数名，模型名多个s
	 *
	 * @var string
	 */
	protected $table = "douban_index_collected";
	protected $fillable = [ 
			'title',
			'url',
			'cid',
			'thumb_img',
			'article_id' 
	];
}
