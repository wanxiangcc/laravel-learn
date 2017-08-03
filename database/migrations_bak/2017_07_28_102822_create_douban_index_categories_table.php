<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoubanIndexCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('douban_index_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('db_url');
			$table->boolean('status')->default(1);
			$table->integer('db_id')->default(0);
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
		Schema::drop('douban_index_categories');
	}

}
