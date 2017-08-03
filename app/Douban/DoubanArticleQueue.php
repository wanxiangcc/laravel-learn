<?php

namespace App\Douban;

use Illuminate\Database\Eloquent\Model;

class DoubanArticleQueue extends Model
{
    protected $fillable = [ 
			'title',
			'url',
			'cid',
			'thumb_img' 
	];
}
