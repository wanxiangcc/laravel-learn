<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class CollectDoubanDownloadImageJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $data;
	public function __construct($data = []) {
		$this->data = $data;
	}
	/**
	 * data[fromUrl,savePath]
	 *
	 * @return boolean
	 */
	public function handle() {
		if (empty ( $this->data )) {
			Log::notice ( '执行CollectDoubanDownloadImageJob data is empty' );
			return false;
		}
		// 检查尝试运行次数
		if ($this->attempts () > 3) {
			// 同一个任务两次运行之间的等待时间
			$this->release ( 10 );
		}
		// img4.doubanio.com已无法访问
		$fromUrl = str_replace ( 'img4.doubanio.com', 'img3.doubanio.com', $this->data ['fromUrl'] );
		$client = new Client ();
		$res = $client->request ( 'GET', $fromUrl, [ 
				'verify' => false 
		] );
		if ($res->getStatusCode () == 200) {
			$savePath = public_path () . DIRECTORY_SEPARATOR . $this->data ['savePath'];
			if ('/' == DIRECTORY_SEPARATOR) {
				$savePath = str_replace ( '\\', DIRECTORY_SEPARATOR, $savePath );
			} else {
				$savePath = str_replace ( '/', DIRECTORY_SEPARATOR, $savePath );
			}
			$fp2 = fopen ( $savePath, 'w' );
			fwrite ( $fp2, $res->getBody () );
			fclose ( $fp2 );
		} else {
			Log::notice ( 
					'执行CollectDoubanDownloadImageJob 请求失败. code ' . $res->getStatusCode () . ' url ' .
							 $this->data ['fromUrl'] );
		}
	}
	/**
	 * 处理失败任务
	 *
	 * @return void
	 */
	public function failed() {
		// Called when the job is failing...
	}
}
