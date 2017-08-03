<?php

namespace App\Douban;

use Illuminate\Database\Eloquent\Model;

class DoubanGallery extends Model {
	protected $fillable = [ 
			'article_id',
			'img_url',
			'db_img_url',
			'type' 
	];
}
