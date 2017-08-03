<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;

class ArticleController extends Controller {
	/**
	 * 管理列表页
	 */
	public function index() {
		$articles = Article::paginate ( 10 );
		if (request ()->ajax ()) {
			$html = view ( 'admin/article/data' )->with ( 'articles', $articles )->render ();
			return response ()->json ( [ 
					'result' => 1,
					'html' => $html 
			] );
		} else {
			return view ( 'admin/article/index' )->with ( 'articles', $articles );
		}
	}
	/**
	 * 新增
	 */
	public function create() {
		return view ( 'admin/article/create' );
	}
	/**
	 * 新增保存
	 * 具体方法名参考https://docs.golaravel.com/docs/5.1/controllers/#restful-resource-controllers
	 * restful的方法
	 * 动词 路径 行为（方法） 路由名称
	 * GET /photos index photos.index
	 * GET /photos/create create photos.create
	 * POST /photos store photos.store
	 * GET /photos/{photo} show photos.show
	 * GET /photos/{photo}/edit edit photos.edit
	 * PUT/PATCH /photos/{photo} update photos.update
	 * DELETE /photos/{photo} destroy photos.destroy
	 */
	public function store(Request $request) { // Laravel 的依赖注入系统会自动初始化我们需要的 Request 类
	                                          // 数据验证
		$this->validate ( $request, 
				[ 
						'title' => 'required|unique:articles|max:255', // 必填、在 articles 表中唯一、最大长度 255
						'body' => 'required' 
				] );
		// 通过 Article Model 插入一条数据进 articles 表
		$article = new Article (); // 初始化 Article 对象
		$article->title = $request->get ( 'title' ); // 将 POST 提交过了的 title 字段的值赋给 article 的 title 属性
		$article->body = $request->get ( 'body' ); // 同上
		$article->user_id = $request->user ()->id; // 获取当前 Auth 系统中注册的用户，并将其 id 赋给 article 的 user_id 属性
		                                           
		// 将数据保存到数据库，通过判断保存结果，控制页面进行不同跳转
		if ($article->save ()) {
			return redirect ( 'admin/article' ); // 保存成功，跳转到 文章管理 页
		} else {
			// 保存失败，跳回来路页面，保留用户的输入，并给出提示
			// return redirect ()->back ()->withInput ()->withErrors ( '保存失败！' );
			return redirect ()->back ()->withInput ()->with ( 'errors', '保存失败！' );
		}
	}
	/**
	 * 删除
	 *
	 * @param unknown $id        	
	 */
	public function destroy($id) {
		Article::find ( $id )->delete ();
		return redirect ()->back ()->withInput ()->withErrors ( '删除成功！' );
	}
	/**
	 * 编辑
	 *
	 * @param unknown $id
	 *        	blade 语法
	 *        	{!! !!} 相当于 <?php echo $value; ?>
	 *        	{{ }} 相当于 <?php echo htmlspecialchars($value); ?>
	 */
	public function edit($id) {
		$article = Article::find ( $id );
		return view ( 'admin/article/edit' )->with ( 'article', $article );
	}
	/**
	 * 更新
	 *
	 * @param \Request $request        	
	 */
	public function update($id, Request $request) {
		$this->validate ( $request, 
				[ 
						'title' => 'required|unique:articles|max:255', // 必填、在 articles 表中唯一、最大长度 255
						'body' => 'required' 
				] );
		$article = Article::findOrFail ( $id );
		$article->title = $request->get ( 'title' ); // 将 POST 提交过了的 title 字段的值赋给 article 的 title 属性
		$article->body = $request->get ( 'body' ); // 同上
		$article->user_id = $request->user ()->id; // 获取当前 Auth 系统中注册的用户，并将其 id 赋给 article 的 user_id 属性
		
		if ($article->save ()) {
			return redirect ( 'admin/article' );
		} else {
			// 保存失败，跳回来路页面，保留用户的输入，并给出提示
			// return redirect ()->back ()->withInput ()->withErrors ( '保存失败！' );
			return redirect ()->back ()->withInput ()->with ( 'errors', '更新失败！' );
		}
	}
	/**
	 * 默认查看
	 *
	 * @param unknown $id        	
	 */
	public function show($id) {
		$article = Article::find ( $id );
	}
}
