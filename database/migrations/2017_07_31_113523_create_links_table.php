<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateLinksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create ( 'links', 
				function (Blueprint $table) {
					$table->increments ( 'id' );
					$table->string ( 'name' );
					$table->string ( 'url' );
					$table->tinyInteger ( 'status' )->default ( 1 );
					$table->string ( 'description' );
					$table->string ( 'open_type' );
					$table->timestamps ();
				} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists ( 'links' );
	}
}
