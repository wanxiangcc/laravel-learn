<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {
	public function __construct() {
		// $this->middleware ( 'auth' );
	}
	public function index() {
		// homeNew 视图blade名称
		return view ( 'home' )->with ( "articles", \App\Article::paginate ( 15 ) );
	}
}
