<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
	
	/**
	 * 可以被批量赋值的属性。
	 *
	 * @var array
	 */
	protected $fillable = [ 
			'nickname',
			'email',
			'website',
			'content',
			'article_id' 
	];
	/**
	 * 不可被批量赋值的属性。
	 *
	 * @var array
	 */
	protected $guarded = [ ];
	
	/**
	 * 返回belongsTo，方法名为comment对象可以调用的对象
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function article() {
		// 关联模型， 当前模型(Comment)的外键key ， 关联模型的key
		return $this->belongsTo ( 'App\Article', 'article_id', 'id' );
	}
}
