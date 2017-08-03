<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoubanIndexCollectedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('douban_index_collected', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('url');
			$table->string('thumb_img')->nullable();
			$table->integer('cid')->default(0);
			$table->integer('article_id')->default(0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('douban_index_collected');
	}

}
