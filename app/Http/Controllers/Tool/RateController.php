<?php

namespace App\Http\Controllers\Tool;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RateController extends Controller {
	public function index() {
		return view ( 'tool.rate.index' );
	}
}
