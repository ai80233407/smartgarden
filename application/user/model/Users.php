<?php
namespace app\user\model;

use think\Model;

class Users extends Model{
	
	public static function is_login(){
		if(session('?uid')){
			return true;
		}else{
			return false;
		}
	}
	
	public static function need_login(){
		return redirect('/Index/Index/Login');
	}
	
	public static function out_login(){
		if(Users::is_login()){
			session('uid',null);
		}
	}
	
	public static function get_one_record($account){
		return Users::get(['account'=>$account]);
	}
	
	public static function get_one_user($uid){
		return Users::get(['id'=>$uid]);
	}
	
	public function get_uid(){
		$uid=session('uid');
		if(empty($uid)){
			return null;
		}else{
			return $uid;
		}
	}
}