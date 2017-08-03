<?php

namespace App\Repositories;

interface DoubanCollectRepositoryInterface {
	/**
	 * 采集豆瓣首页 列表数据
	 * https://www.dbmeinv.com/
	 */
	public function collectIndex($url = '', $cid = 0);
	/**
	 * 采集豆瓣首页的分类信息
	 *
	 * @param string $url        	
	 */
	public function collectIndexCategory($url = '');
	/**
	 * 采集豆瓣详情页面信息
	 *
	 * @param number $articleQueueId
	 *        	douban_article_queues 主键id
	 */
	public function collectArticle($articleQueueId = 0);
}