<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoubanGalleriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('douban_galleries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('article_id')->nullable()->default(0);
			$table->string('img_url', 100)->nullable();
			$table->string('db_img_url', 150)->nullable();
			$table->boolean('type')->nullable()->default(0)->comment('0封面图1相册');
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
		Schema::drop('douban_galleries');
	}

}
