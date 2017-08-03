<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\DoubanCollectRepositoryInterface;

class CollectDoubanArticleJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $doubanCollectRepository;
	protected $queueId;
	public function __construct(DoubanCollectRepositoryInterface $dcri, $queueId = 0) {
		$this->queueId = $queueId;
		$this->doubanCollectRepository = $dcri;
	}
	public function handle() {
		if (empty ( $this->queueId )) {
			return false;
		}
		$this->doubanCollectRepository->collectArticle ( $this->queueId );
	}
}
