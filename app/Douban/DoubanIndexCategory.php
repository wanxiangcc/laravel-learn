<?php

namespace App\Douban;

use Illuminate\Database\Eloquent\Model;

class DoubanIndexCategory extends Model {
	protected $fillable = [ 
			'name',
			'db_url',
			'db_id',
			'status' 
	];
}
