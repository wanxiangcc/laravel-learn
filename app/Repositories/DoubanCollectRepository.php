<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;
use App\Jobs\CollectDoubanDownloadImageJob;
use App\Douban\DoubanAuthor;

class DoubanCollectRepository implements DoubanCollectRepositoryInterface {
	protected $doubanRepository;
	public function __construct(DoubanRepositoryInterface $doubanRepository) {
		$this->doubanRepository = $doubanRepository;
	}
	/**
	 * 采集首页数据
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanCollectRepositoryInterface::collectIndex()
	 */
	public function collectIndex($url = '', $cid = 0) {
		if (empty ( $url )) {
			return false;
		}
		Log::info ( '执行collectIndex采集 start：' . $url );
		$client = new Client ();
		$res = $client->request ( 'GET', $url, [ 
				'verify' => false 
		] );
		$html = $res->getBody ()->getContents ();
		if (empty ( $html )) {
			Log::notice ( '执行collectIndex,请求返回结果 html为空. url ' . $url . ',statusCode ' . $res->getStatusCode () );
			return false;
		}
		$crawler = new Crawler ( $html );
		$crawler = $crawler->filter ( '.img_single > a' );
		$collectCount = 0;
		foreach ( $crawler as $domElement ) {
			$url = $domElement->attributes->getNamedItem ( 'href' )->value;
			$isCollected = $this->doubanRepository->findIndexCollectedByUrl ( $url );
			if (! empty ( $isCollected )) {
				continue;
			}
			$urlArr = explode ( '/', $url );
			$articleId = intval ( $urlArr [count ( $urlArr ) - 1] );
			
			$imgArr = [ ];
			foreach ( $domElement->childNodes as $childNode ) {
				if ($childNode->nodeName == 'img') {
					// a标签下的图片节点
					$attr = $childNode->attributes;
					for($i = 0; $i < $attr->length; $i ++) {
						$tmp = $attr->item ( $i );
						$imgArr [$tmp->name] = $tmp;
					}
				} else {
					continue;
				}
			}
			$title = isset ( $imgArr ['title'] ) ? $imgArr ['title']->value : '';
			$thumbImg = isset ( $imgArr ['src'] ) ? $imgArr ['src']->value : '';
			$this->doubanRepository->saveIndexCollected ( 
					[ 
							'title' => $title,
							'url' => $url,
							'thumb_img' => $thumbImg,
							'cid' => $cid,
							'article_id' => $articleId 
					] );
			$articleQueue = $this->doubanRepository->saveArticleQueue ( 
					[ 
							'title' => $title,
							'url' => $url,
							'thumb_img' => $thumbImg,
							'cid' => $cid 
					] );
			$collectCount ++;
			// 分发任务
			$job = new \App\Jobs\CollectDoubanArticleJob ( $this, $articleQueue->id );
			dispatch ( $job->onQueue ( 'DoubanArticleCollect' ) );
		}
		Log::info ( '执行collectIndex采集 end：' . $url . ' , collectCount :' . $collectCount );
	}
	/**
	 * 采集首页分类数据
	 *
	 * {@inheritdoc}
	 *
	 * @see \App\Repositories\DoubanCollectRepositoryInterface::collectIndexCategory()
	 */
	public function collectIndexCategory($url = '') {
		if (empty ( $url )) {
			return false;
		}
		Log::info ( '执行collectIndexCategory采集：' . $url );
		// 文档http://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html#verify
		$client = new Client ();
		// 证书 https://github.com/guzzle/guzzle/blob/4.2.3/src/cacert.pem
		$res = $client->request ( 'GET', $url, [ 
				'verify' => false 
		] );
		// 使用 getBody 方法可以获取响应的主体部分(body)，主体可以当成一个字符串或流对象使用
		// $body = $response->getBody();
		// echo时会自动转换为string
		// 1.echo $body;
		// 强制转为string
		// 2.$stringBody = (string) $body;
		// 读取10 bytes
		// 3.$tenBytes = $body->read(10);
		// 获取contents string
		// 4.$contents = $body->getContents();
		$html = $res->getBody ()->getContents ();
		if (empty ( $html ))
			return false;
		$crawler = new Crawler ( $html );
		$crawler = $crawler->filter ( '.nav-pills > li > a' );
		foreach ( $crawler as $domElement ) {
			$catName = $domElement->nodeValue;
			$url = $domElement->attributes->getNamedItem ( 'href' )->value;
			$data = [ 
					'name' => $catName,
					'db_url' => $url,
					'status' => $catName == '所有' ? 0 : 1 
			];
			$urlArr = parse_url ( $url );
			if (isset ( $urlArr ['query'] )) {
				parse_str ( $urlArr ['query'], $tmpArr );
				isset ( $tmpArr ['cid'] ) && $data ['db_id'] = intval ( $tmpArr ['cid'] );
			} else {
				$data ['db_id'] = 0;
			}
			$this->doubanRepository->saveIndexCategory ( $data, $cid );
		}
		return true;
	}
	/**
	 * 采集豆瓣详情页面信息
	 *
	 * @param number $articleQueueId
	 *        	douban_article_queues 主键id
	 */
	public function collectArticle($articleQueueId = 0) {
		if (empty ( $articleQueueId )) {
			return false;
		}
		$articleQueue = $this->doubanRepository->getArticlesQueue ( $articleQueueId );
		if (empty ( $articleQueue )) {
			Log::notice ( '执行collectArticle, articleQueue 不存在. id ' . $articleQueueId );
			return false;
		}
		if (empty ( $articleQueue->url )) {
			Log::notice ( '执行collectArticle, articleQueue url 不存在. id ' . $articleQueueId );
			return false;
		}
		Log::info ( '执行collectArticle采集：start ' . $articleQueue->url );
		$galleryArr = [ ];
		if (! empty ( $articleQueue->thumb_img )) {
			// 缩略图
			$articleThumbImg = self::getLocalImagePath ( $articleQueue->thumb_img );
			$galleryArr [] = [ 
					'savePath' => $articleThumbImg,
					'fromUrl' => $articleQueue->thumb_img,
					'type' => 0 
			];
		}
		$client = new Client ();
		$res = $client->request ( 'GET', $articleQueue->url, [ 
				'verify' => false 
		] );
		$html = $res->getBody ()->getContents ();
		if (empty ( $html )) {
			Log::notice ( 
					'执行collectArticle, articleQueue url 请求返回结果 html为空. id ' . $articleQueueId . ',statusCode ' .
							 $res->getStatusCode () );
			return false;
		}
		$crawler = new Crawler ( $html );
		$crawlerBody = $crawler->filter ( '.panel-body' );
		
		$htmlNew = '';
		foreach ( $crawlerBody->children () as $domElement ) {
			$class = $domElement->getAttribute ( 'class' );
			if ($domElement->nodeName == 'script' || strpos ( $class, 'mobile-hide' ) !== false) {
				// 去除掉文本内容中的script 和 隐藏的控件
				continue;
			}
			if ($domElement->nodeName == 'div' && strpos ( $class, 'topic-figure' ) !== false) {
				foreach ( $domElement->childNodes as $dom ) {
					if ($dom->nodeName == 'img') {
						$src = $dom->attributes->getNamedItem ( 'src' )->value;
						$newSrc = self::getLocalImagePath ( $src );
						$galleryArr [] = [ 
								'savePath' => $newSrc,
								'fromUrl' => $src,
								'type' => 1 
						];
						// 重新赋值
						// attributes is DOMNamedNodeMap
						// $dom is DOMElement http://php.net/manual/en/class.domelement.php
						$newSrc = str_replace ( '\\', '/', $newSrc );
						$dom->attributes->getNamedItem ( 'src' )->value = '';
						$dom->setAttribute ( 'db-src', $src );
						$dom->setAttribute ( 'class', 'lazyload' );
						$dom->setAttribute ( 'data-original', $newSrc );
					}
				}
			}
			$htmlNew .= $domElement->ownerDocument->saveHTML ( $domElement );
		}
		// 处理头像start
		try {
			$avatar = $crawler->filter ( '.media-object' )->attr ( 'src' );
			$authorName = $crawler->filter ( '.name' )->text ();
			$author = $this->doubanRepository->getAuthor ( 0, $authorName );
			$avatarLocal = self::getLocalImagePath ( $avatar, 'avatar', false );
			if (empty ( $author )) {
				$author = new DoubanAuthor ();
				$author->db_avatar = $avatar;
				$author->avatar = str_replace ( '\\', '/', $avatarLocal );
				$author->name = $authorName;
				$author->save ();
				$avatarJob = new CollectDoubanDownloadImageJob ( 
						[ 
								'savePath' => $avatarLocal,
								'fromUrl' => $avatar 
						] );
			} else {
				if ($author->db_avatar != $avatar) {
					// 更新头像
					$author->db_avatar = $avatar;
					$author->avatar = str_replace ( '\\', '/', $avatarLocal );
					$author->save ();
					$avatarJob = new CollectDoubanDownloadImageJob ( 
							[ 
									'savePath' => $avatarLocal,
									'fromUrl' => $avatar 
							] );
				}
			}
			// 分发头像
			isset ( $avatarJob ) && dispatch ( $avatarJob->onQueue ( 'DoubanDownloadImage' ) );
		} catch ( \InvalidArgumentException $e ) {
			Log::error ( '执行collectArticle 查找avatar or name 节点无法找到。' . $articleQueue->url );
		}
		// 处理头像end
		
		// 保存article
		$article = new \App\Douban\DoubanArticle ();
		$article->cid = $articleQueue->cid;
		$article->title = $articleQueue->title;
		$article->content = $htmlNew;
		isset ( $articleThumbImg ) && $article->thumb_img = str_replace ( '\\', '/', $articleThumbImg );
		! empty ( $articleQueue->thumb_img ) && $article->db_thumb_img = $articleQueue->thumb_img;
		$article->author_id = $author->id;
		$article->author_name = $author->name;
		$article->db_url = $articleQueue->url;
		$article->save ();
		
		foreach ( $galleryArr as $row ) {
			$this->doubanRepository->saveArticleGallery ( 
					[ 
							'article_id' => $article->id,
							'img_url' => $row ['savePath'],
							'db_img_url' => $row ['fromUrl'],
							'type' => isset ( $row ['type'] ) ? $row ['type'] : 1 
					] );
			// 分发图片下载任务 $row数组必须包括savePath , fromUrl
			$job = new CollectDoubanDownloadImageJob ( $row );
			dispatch ( $job->onQueue ( 'DoubanDownloadImage' ) );
		}
		// 删除queue表数据
		$articleQueue->destroy ( $articleQueue->id );
		Log::info ( '执行collectArticle采集：end ' . $articleQueue->url );
		return $articleQueue->id;
	}
	/**
	 * 获取本地文件
	 *
	 * @param string $src        	
	 * @return string
	 */
	public static function getLocalImagePath($src = '', $middle = '', $withMonth = true) {
		if (empty ( $src ))
			return '';
			// config目录：config_path();
			// public目录：public_path();
			// base_path
			// app_path
		$base = 'upload' . DIRECTORY_SEPARATOR . 'douban';
		if (! empty ( $middle )) {
			$base .= DIRECTORY_SEPARATOR . $middle;
		}
		$withMonth && $base .= DIRECTORY_SEPARATOR . date ( 'Ym' );
		mkFolder ( public_path () . DIRECTORY_SEPARATOR . $base );
		return $base . DIRECTORY_SEPARATOR . date ( 'Ymd' ) . '_' . basename ( $src );
	}
}