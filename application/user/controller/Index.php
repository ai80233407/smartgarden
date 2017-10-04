<?php
namespace app\user\controller;

use app\user\model\Users;

class Index extends \think\Controller{
	public function index(){
		if(Users::is_login()){
			return $this->fetch('/index/index');
		}else{
			return User::need_login();
		}
	}
}	