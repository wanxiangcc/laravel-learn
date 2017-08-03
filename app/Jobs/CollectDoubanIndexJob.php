<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\DoubanCollectRepositoryInterface;

/**
 * 执行豆瓣首页采集的任务
 *
 * @author wx
 *        
 */
class CollectDoubanIndexJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $doubanCollectRepository;
	protected $url;
	protected $cid;
	public function __construct(DoubanCollectRepositoryInterface $dcri, $url = '', $cid = 0) {
		$this->doubanCollectRepository = $dcri;
		$this->url = $url;
		$this->cid = $cid;
	}
	public function handle() {
		$this->doubanCollectRepository->collectIndex ( $this->url, $this->cid );
	}
}
