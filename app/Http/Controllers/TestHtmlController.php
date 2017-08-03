<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Repositories\DoubanCollectRepositoryInterface;

class TestHtmlController extends Controller {
	protected $doubanCollectService;
	public function __construct(DoubanCollectRepositoryInterface $dcri) {
		$this->doubanCollectService = $dcri;
	}
	public function index() {
		// $this->doubanCollectService->collectIndexCategory('https://www.dbmeinv.com/');
		// $this->doubanCollectService->collectIndex('https://www.dbmeinv.com/dbgroup/show.htm?cid=5');
		 $this->doubanCollectService->collectArticle ( 572 );
	}
	public function index1() {
		$html = file_get_contents ( 'https://www.dbmeinv.com/dbgroup/show.htm?cid=5' );
		$crawler = new Crawler ( $html );
		// $crawler = $crawler->filterXPath('descendant-or-self::body/p');
		// $crawler = $crawler->filter('body > p');
		// $crawler = $crawler->filter('.thumbnails > li');
		$crawler = $crawler->filter ( '.img_single > a' );
		
		// filter后的文本
		// <div class="img_single">
		// <a href="https://www.dbmeinv.com:443/dbgroup/1399351"
		// class="link" target="_topic_detail">
		// <img class="height_min" title="喜欢露出" alt="喜欢露出"
		// onerror="img_error(this);"
		// src="http://ww1.sinaimg.cn/bmiddle/0060lm7Tgy1fhx9c6avp6j30ku0rpju2.jpg">
		// </a>
		// </div>
		foreach ( $crawler as $domElement ) {
			// $domElement->
			var_dump ( $domElement );
			// print_r($domElement->firstChild);
			foreach ( $domElement->attributes as $a ) {
				echo "a attributes : ";
				// var_dump($a);
			}
			foreach ( $domElement->childNodes as $b ) {
				var_dump ( $b );
				$tmpArr = [ ];
				if ($b->nodeName == 'img') {
					// a标签下的图片节点
					$attr = $b->attributes;
					// object DOMNamedNodeMap
					// http://php.net/manual/zh/class.domnamednodemap.php
					// $src = $attr->getNamedItem('src');
					
					for($i = 0; $i < $attr->length; $i ++) {
						$tmp = $attr->item ( $i );
						$tmpArr [$tmp->name] = $tmp;
						// var_dump ( $b->attributes->item ( $i ) );
					}
				}
				// print_r($tmpArr);
			}
			exit ();
		}
	}
}
