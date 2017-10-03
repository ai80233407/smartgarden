<?php
namespace app\user\model;

use think\Model;

class Users extends Model{
	public function get_uid(){
		$uid=session('uid');
		if(empty($uid)){
			return null;
		}else{
			return $uid;
		}
	}
	
	public function is_login(){
		if(session('?uid')){
			return true;
		}else{
			return false;
		}
	}
	
	public function need_login(){
		return redirect('/Index/Index/Login');
	}
	
	public function out_login(){
		if($this->is_login()){
			session('uid',null);
		}
	}
}