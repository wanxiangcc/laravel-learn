<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;

/**
 * 生成command
 * php artisan make:command MultithreadingRequest --command=test:multithreading-request
 * 注册命令
 * 编辑 app/Console/Kernel.php，在 $commands 数组中增加Commands\MultithreadingRequest::class,
 * 执行命令
 * php artisan test:multithreading-request
 * 
 * @author wx
 *        
 */
class MultithreadingRequest extends Command {
	protected $signature = 'test:multithreading-request';
	protected $description = 'Command description';
	private $totalPageCount;
	private $counter = 1;
	private $concurrency = 7; // 同时并发抓取
	private $users = [ 
			'CycloneAxe',
			'appleboy',
			'Aufree',
			'lifesign',
			'overtrue',
			'zhengjinghua',
			'NauxLiu' 
	];
	public function __construct() {
		parent::__construct ();
	}
	public function handle() {
		$this->totalPageCount = count ( $this->users );
		$client = new Client ();
		$requests = function ($total) use ($client) {
			foreach ( $this->users as $key => $user ) {
				$uri = 'https://api.github.com/users/' . $user;
				yield function () use ($client, $uri) {
					return $client->getAsync ( $uri );
				};
			}
		};
		$pool = new Pool ( $client, $requests ( $this->totalPageCount ), 
				[ 
						'concurrency' => $this->concurrency,
						'fulfilled' => function ($response, $index) {
							// this is delivered each successful response
							$res = json_decode ( $response->getBody ()->getContents () );
							$this->info ( "请求第 $index 个请求，用户 " . $this->users [$index] . " 的 Github ID 为：" . $res->id );
							$this->countedAndCheckEnded ();
						},
						'rejected' => function ($reason, $index) {
							// this is delivered each failed request
							$this->error ( "rejected" );
							$this->error ( "rejected reason: " . $reason );
							$this->countedAndCheckEnded ();
						} 
				] );
		// 开始发送请求
		$promise = $pool->promise ();
		$promise->wait ();
	}
	public function countedAndCheckEnded() {
		if ($this->counter < $this->totalPageCount) {
			$this->counter ++;
			return;
		}
		$this->info ( "request end!" );
	}
}
