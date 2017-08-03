<?php

namespace App\Repositories;

interface DoubanRepositoryInterface {
	/**
	 * 根据url查询
	 *
	 * @param string $url        	
	 */
	public function findIndexCollectedByUrl($url = '');
	/**
	 * 保存采集的文章
	 *
	 * @param unknown $data        	
	 */
	public function saveIndexCollected($data = null);
	/**
	 * 保存采集的文档到queue表中
	 *
	 * @param unknown $data        	
	 */
	public function saveArticleQueue($data = null);
	/**
	 * 保存首页的分类信息
	 *
	 * @param unknown $data        	
	 */
	public function saveIndexCategory($data = null);
	/**
	 * 获取所有分类
	 *
	 * @param unknown $status
	 *        	默认-1所有，传入值大于1会加入条件
	 */
	public function getAllIndexCategory($status = -1);
	/**
	 * 获取articles queue 记录
	 * 
	 * @param number $id        	
	 */
	public function getArticlesQueue($id = 0);
	/**
	 * 删除articles queue 记录
	 * 
	 * @param number $id        	
	 */
	public function deleteArticlesQueue($id = 0);
	/**
	 * 保存或者更新作者
	 * @param unknown $data
	 */
	public function saveOrUpdateAuthorByName($data = null);
	/**
	 * 获取作者
	 * @param number $id
	 * @param string $name
	 */
	public function getAuthor($id = 0 , $name = '');
	/**
	 * 保存相册
	 * @param unknown $data
	 */
	public function saveArticleGallery($data = null);
}