<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use Validator;
use Auth;
use Redirect;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate;
// use Illuminate\Foundation\Auth\RegistersUsers;
class AuthController extends Controller {
	use AuthenticatesUsers;
	protected $redirectTo = 'admin';
	public function __construct() {
		$this->middleware ( 'guest:admin', [ 
				'except' => 'logout' 
		] );
	}
	// 登录
	public function login(Request $request) {
		if ($request->isMethod ( 'post' )) {
			$validator = $this->validateLogin ( $request->input () );
			if ($validator->fails ()) {
				return redirect ()->back ()->withErrors ( $validator )->withInput ();
			}
			$loginResult = Auth::guard ( 'admin' )->attempt ( 
					[ 
							'account_number' => $request->account_number,
							'password' => $request->password 
					] );
			if ($loginResult) {
				return Redirect::to ( 'admin' )->with ( 'success', '登录成功！' ); // login success, redirect to admin
			} else {
				return redirect ()->back ()->withErrors ( '账号或密码错误' )->withInput ();
			}
		}
		return view ( 'admin.auth.login' );
	}
	
	// 退出登录
	public function logout() {
		if (Auth::guard ( 'admin' )->user ()) {
			Auth::guard ( 'admin' )->logout ();
		}
		return Redirect::to ( 'admin/login' );
	}
	// 注册
	public function register(Request $request) {
		if ($request->isMethod ( 'post' )) {
			return back ()->withErrors ( '暂不开放管理员注册！' )->withInput ();
			
			$validator = $this->validateRegister ( $request->input () );
			if ($validator->fails ()) {
				return back ()->withErrors ( $validator )->withInput ();
			}
			$admin = new Admin ();
			$admin->name = $request->name;
			$admin->account_number = $request->account_number;
			$admin->password = bcrypt ( $request->password );
			if ($admin->save ()) {
				return redirect ( 'admin/login' )->with ( 'success', '注册成功！' );
			} else {
				return back ()->withErrors ( '注册失败！' )->withInput ();
			}
		}
		return view ( 'admin.auth.register' );
	}
	/**
	 * 登录页面验证
	 *
	 * @param array $data        	
	 * @return \Illuminate\Validation\Validator
	 */
	protected function validateLogin(array $data) {
		return Validator::make ( $data, 
				[ 
						'account_number' => 'required|alpha_num',
						'password' => 'required' 
				], [ 
						'required' => ':attribute 为必填项',
						'min' => ':attribute 长度不符合要求' 
				], [ 
						'account_number' => '账号',
						'password' => '密码' 
				] );
	}
	/**
	 * 注册页面认证
	 *
	 * @param array $data        	
	 * @return \Illuminate\Validation\Validator
	 */
	protected function validateRegister(array $data) {
		return Validator::make ( $data, 
				[ 
						'name' => 'required|alpha_num|max:255',
						'account_number' => 'required|alpha_num|unique:admins',
						'password' => 'required|min:6|confirmed',
						'password_confirmation' => 'required|min:6|' 
				], 
				[ 
						'required' => ':attribute 为必填项',
						'min' => ':attribute 长度不符合要求',
						'confirmed' => '两次输入的密码不一致',
						'unique' => '该账户已存在',
						'alpha_num' => ':attribute 必须为字母或数字',
						'max' => ':attribute 长度过长' 
				], 
				[ 
						'name' => '昵称',
						'account_number' => '账号',
						'password' => '密码',
						'password_confirmation' => '确认密码' 
				] );
	}
}
