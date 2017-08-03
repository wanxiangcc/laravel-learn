<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoubanArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('douban_articles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cid')->default(0);
			$table->string('title');
			$table->text('content', 65535)->nullable();
			$table->string('thumb_img')->nullable();
			$table->integer('author_id')->nullable()->default(0);
			$table->string('author_name', 100)->nullable();
			$table->string('db_url');
			$table->string('db_thumb_img')->nullable();
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
		Schema::drop('douban_articles');
	}

}
