<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Http\Response;

class CommentController extends Controller {
	/**
	 * 保存评论
	 *
	 * @param Request $request        	
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request) {
		$result = Comment::create ( $request->all () );
		// 下述两种方式都可以fetch html
		// $html = \View::make ( 'comment.singleComment', [
		// 'comment' => $result
		// ] )->render ();
		$html = view ( 'comment/singleComment' )->with ( 'comment', $result )->render ();
		return response ()->json ( [ 
				'result' => 1,
				'html' => $html 
		] );
	}
}
