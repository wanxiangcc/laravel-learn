<?php

namespace App\Http\Controllers\Douban;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Douban\DoubanArticle;

class ArticleController extends DoubanBaseController {
	public function show($id) {
		$article = DoubanArticle::with ( 'belongAuthor' )->find ( $id );
		if (empty ( $article )) {
		}
		$lastArticles = DoubanArticle::orderBy ( 'id', 'desc' )->take ( 10 )->get ();
		return view ( 'douban/article/show' )->with ( 
				[ 
						'article' => $article,
						'lastArticles' => $lastArticles 
				] );
	}
}
