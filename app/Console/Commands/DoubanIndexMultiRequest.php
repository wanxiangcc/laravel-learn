<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\DoubanCollectRepositoryInterface;
use App\Repositories\DoubanRepositoryInterface;
use App\Jobs\CollectDoubanIndexJob;

/**
 *
 * 该commond dispatch 分发任务到队列
 *
 * 队列在后台是一个持久化的进城
 * 执行命令启动任务
 * 说明： redis代表使用的那个队列的链接 --queue指定哪个queue任务
 * php artisan queue:work redis --queue=DoubanIndexCollect
 *
 * 根目录下QUEUE_DRIVER指定队列链接类型
 * 默认为sync，同步执行
 *
 * 队列参考说明 https://www.lijinma.com/blog/2017/01/31/laravel-queue/
 *
 * @author wx
 *        
 */
class DoubanIndexMultiRequest extends Command {
	// 默认无参数
	// protected $signature = 'douban:IndexMulti';
	// 带参数 {--queue= : 这个工作是否该进入队列}
	// protected $signature = 'email:send {user} {--queue=}';
	protected $signature = 'douban:IndexMulti {maxPage}';
	protected $description = 'Command description';
	protected $doubanCollectRepository;
	protected $doubanRepository;
	public function __construct(DoubanCollectRepositoryInterface $dcri, DoubanRepositoryInterface $dri) {
		parent::__construct ();
		$this->doubanCollectRepository = $dcri;
		$this->doubanRepository = $dri;
	}
	public function handle() {
		// 取参数 按照$signature的顺序
		// 如 php artisan douban:IndexMulti 100
		$maxPage = intval ( $this->argument ( 'maxPage' ) );
		$maxPage <= 0 && $maxPage = 1;
		$category = $this->doubanRepository->getAllIndexCategory ( 1 );
		foreach ( $category as $row ) {
			$url = $row->db_url;
			$urlArr = parse_url ( $url );
			$urlAppend = '?pager_offset=';
			if (isset ( $urlArr ['query'] )) {
				$urlAppend = '&pager_offset=';
			}
			for($i = 1; $i <= $maxPage; $i ++) {
				// 创建任务 并分发给DoubanIndexCollect 队列
				$job = new CollectDoubanIndexJob ( $this->doubanCollectRepository, $url . $urlAppend . $i, $row->id );
				dispatch ( $job->onQueue ( 'DoubanIndexCollect' ) ); // ->delay ( 10 ) 延迟10秒执行
			}
		}
		$this->info ( 'success . page ' . $maxPage );
	}
}
