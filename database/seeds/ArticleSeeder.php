<?php
use Illuminate\Database\Seeder;
/**
 * 生成制造数据类命令：php artisan make:seeder ArticleSeeder
 * 制造测试数据，article类的测试数据
 * 需要注册到databaseSeeder.php中$this->call(ArticleSeeder::class);
 * 然后执行命令
 * php artisan db:seed
 *
 * @author wx
 *        
 */
class ArticleSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		// DB::table('articles')->delete();
		for($i = 100; $i < 200; $i ++) {
			\App\Article::create ( 
					[ 
							'title' => 'Title ' . $i,
							'body' => 'Body ' . $i,
							'user_id' => 1 
					] );
		}
	}
}
