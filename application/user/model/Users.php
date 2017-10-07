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
			session(null);
		}
	}
	
	public static function get_one_record($account){
		return Users::get(['account'=>$account]);
	}
	
	public static function get_one_user($uid){
		return Users::get(['id'=>$uid]);
	}
	
	public static function add_one_user($data){
		return Users::create($data);
	}
	
	public static function update_one_logtime($data){
		return Users::update($data);
	}
	
	public static function get_all_user(){
		return Users::table(['users'=>'u','root_role'=>'r','users_status'=>'s'])->field('u.id,account,nickname,r.name as role,s.name as status,phone,email,regtime,lastime')->where('u.role=r.id and u.status=s.id')->paginate(10,false,['type' => 'app\common\page\Amh']);
	}
	
	public static function search_user($key){
		return Users::table(['users'=>'u','root_role'=>'r','users_status'=>'s'])->field('u.id,account,nickname,r.name as role,s.name as status,phone,email,regtime,lastime')->where("u.role=r.id and u.status=s.id and( account like '%{$key}%' or nickname like '%{$key}%' or email like '%{$key}%' or phone like '%{$key}%' )")->paginate(10,false,['type' => 'app\common\page\Amh']);
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