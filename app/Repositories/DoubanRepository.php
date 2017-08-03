<?php

namespace App\Repositories;

use App\Douban\DoubanIndexCollected;
use App\Douban\DoubanIndexCategory;
use App\Douban\DoubanArticleQueue;
use App\Douban\DoubanAuthor;
use App\Douban\DoubanGallery;

class DoubanRepository implements DoubanRepositoryInterface {
	/**
	 * 根据url查询 douban_index_collected
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanRepositoryInterface::findIndexCollectedByUrl()
	 */
	public function findIndexCollectedByUrl($url = '') {
		if (empty ( $url )) {
			return false;
		}
		// findOrFail 以及 firstOrFail 方法会取回查询的第一个结果。
		// 如果没有找到相应结果，则会抛出一个 Illuminate\Database\Eloquent\ModelNotFoundException：
		return DoubanIndexCollected::where ( 'url', $url )->first ();
	}
	/**
	 * 保存index category
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanRepositoryInterface::saveIndexCategory()
	 */
	public function saveIndexCategory($data = null) {
		if (empty ( $data )) {
			return false;
		}
		$url = $data ['db_url'];
		// unset ( $data ['db_url'] );
		return DoubanIndexCategory::updateOrCreate ( [ 
				'db_url' => $url 
		], $data );
	}
	/**
	 * 获取所有的index category
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanRepositoryInterface::getAllIndexCategory()
	 */
	public function getAllIndexCategory($status = -1) {
		if ($status != - 1) {
			return DoubanIndexCategory::where ( 'status', $status )->get ();
		} else {
			return DoubanIndexCategory::all ();
		}
	}
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanRepositoryInterface::saveIndexCollected()
	 */
	public function saveIndexCollected($data = null) {
		if (empty ( $data )) {
			return false;
		}
		if (is_array ( $data ))
			return DoubanIndexCollected::create ( $data );
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanRepositoryInterface::saveArticleQueue()
	 */
	public function saveArticleQueue($data = null) {
		if (empty ( $data )) {
			return false;
		}
		if (is_array ( $data ))
			return DoubanArticleQueue::create ( $data );
		return false;
	}
	public function getArticlesQueue($id = 0) {
		if (empty ( $id ))
			return false;
		return DoubanArticleQueue::find ( $id );
	}
	public function deleteArticlesQueue($id = 0) {
		if (empty ( $id ))
			return false;
			// $queue = DoubanArticleQueue::find($id);
			// return $queue->delete();
		return DoubanArticleQueue::destroy ( $id );
	}
	/**
	 * 保存或者更新作者
	 *
	 * @param unknown $data        	
	 */
	public function saveOrUpdateAuthorByName($data = null) {
		if (empty ( $data )) {
			return false;
		}
		return DoubanAuthor::updateOrCreate ( [ 
				'name' => $data ['name'] 
		], $data );
	}
	
	/**
	 * 获取作者
	 *
	 * @param number $id        	
	 * @param string $name        	
	 */
	public function getAuthor($id = 0, $name = '') {
		if (empty ( $id ) && $name == '')
			return false;
		if (! empty ( $id )) {
			return DoubanAuthor::find ( $id );
		} else {
			return DoubanAuthor::where ( 'name', $name )->first ();
		}
	}
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanRepositoryInterface::saveArticleGallery()
	 */
	public function saveArticleGallery($data = null) {
		if (empty ( $data )) {
			return false;
		}
		return DoubanGallery::create ( $data );
	}
}