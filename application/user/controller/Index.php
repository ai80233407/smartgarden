<?php
namespace app\user\controller;

use app\user\model\Users;

class Index extends \think\Controller{
	public function index(){
		if(Users::is_login()&&queryroot(array('用户'=>array('查看'=>1)))){
			return $this->fetch('/index/index');
		}else{
			return User::need_login();
		}
	}
}	