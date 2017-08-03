<?php

namespace App\Http\Controllers\Douban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Douban\DoubanIndexCategory;
use App\Douban\DoubanArticle;

class IndexCategoryController extends DoubanBaseController {
	/**
	 * 访问首页
	 */
	public function index() {
		return $this->show ( 1 );
	}
	/**
	 * 访问分类列表页
	 *
	 * @param number $cid        	
	 */
	public function show($cid = 1) {
		$cats = $this->getIndexCategory ( 10 );
		$articles = $this->getArticles ( $cid );
		return view ( 'douban/cat/index' )->with ( 
				[ 
						'cid' => $cid,
						'indexCategory' => $cats,
						'articles' => $articles 
				] );
	}
	private function getArticles($cid = 0 , $pageSize = 20) {
		if ($cid == 1) {
			return DoubanArticle::paginate ( $pageSize );
		} else {
			return DoubanArticle::where ( 'cid', $cid )->paginate ( $pageSize );
		}
	}
	private function getIndexCategory($limit = 10) {
		return DoubanIndexCategory::limit ( $limit )->get ();
	}
}
