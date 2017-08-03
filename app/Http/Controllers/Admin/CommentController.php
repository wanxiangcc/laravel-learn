<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;

class CommentController extends Controller {
	/**
	 * 评论管理首页
	 */
	public function index() {
		// paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
		// $perPage代表每页显示数目，$columns代表查询字段，$pageName代表页码名称，$page代表第几页
		$comments = Comment::with ( 'article' )->paginate ( 10 );
		return view ( 'admin/comment/index' )->with ( 'comments', $comments );
	}
	/**
	 * 删除
	 *
	 * @param unknown $id        	
	 */
	public function destroy($id) {
		Comment::find ( $id )->delete ();
		return redirect ()->back ()->withInput ()->withErrors ( '删除成功！' );
		// return redirect ( url ( 'admin/comment' ) );
	}
	/**
	 * 编辑
	 *
	 * @param unknown $id        	
	 */
	public function edit($id) {
		$comment = Comment::find ( $id );
		return view ( 'admin/comment/edit' )->with ( 'comment', $comment );
	}
	/**
	 * 更新
	 *
	 * @param unknown $id        	
	 * @param Request $request        	
	 */
	public function update($id, Request $request) {
		$this->validate ( $request, 
				[ 
						'nickname' => 'required|max:255', // 必填、最大长度 255
						'content' => 'required' 
				] );
		$comment = Comment::findOrFail ( $id );
		$comment->nickname = $request->get ( 'nickname' );
		$comment->content = $request->get ( 'content' );
		$comment->email = $request->get ( 'email' );
		$comment->website = $request->get ( 'website' );
		if ($comment->save ()) {
			return redirect ( 'admin/comment' );
		} else {
			return redirect ()->back ()->withInput ()->with ( 'errors', '更新失败！' );
		}
	}
}
