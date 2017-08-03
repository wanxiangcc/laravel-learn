<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller {
	public function index() {
	}
	public function show($id) {
		$article = Article::with ( 'hasManyComments' )->find ( $id );
		return view('article/show')->with("article" , $article);
	}
	
	
	
	public function testShow($id) {
		$article = Article::find ( $id );
		// 查找标题为“我是标题”的文章
		$article = Article::where ( 'title', '我是标题' )->first ();
		if (! empty ( $article )) {
			echo $article->id;
		}
		// 查询出所有文章并循环打印出所有标题
		// 此处得到的 $articles 是一个对象集合，可以在后面加上 '->toArray()' 变成多维数组。
		$articles = Article::all ();
		
		// 查找 id 在 10~20 之间的所有文章并打印所有标题
		$articles = Article::where ( 'id', '>', 10 )->where ( 'id', '<', 20 )->get ();
		// 查询出所有文章并循环打印出所有标题，按照 updated_at 倒序排序
		$articles = Article::where ( 'id', '>', 10 )->where ( 'id', '<', 20 )->orderBy ( 'updated_at', 'desc' )->get ();
		
		$articles = Article::paginate ( 15 );
		// print_r($articles);exit;
		
		echo $articles->lastPage ();
		
		foreach ( $articles as $article ) {
			echo $article->title . '<br/>';
		}
	}
	public function testPage() {
		// 分页,url中直接给参数page=2
		$articles = Article::paginate ( 15 );
		$articles = Article::where ( 'id', '>', 100 )->paginate ( 15 );
		$articles = Article::where ( 'id', '>', 100 )->simplePaginate ( 15 );
		// 获取总数
		$articles->total ();
		// 每页大小perPage
		// 最后一页lastPage
		// 当前页currentPage
		// 页码标识pageName(默认page)
		
		// 其他自定义
		// Model::offset(0)->limit(10)->get()
	}
}
