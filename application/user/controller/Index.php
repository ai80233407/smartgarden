<?php
namespace app\user\controller;

use app\user\model\Users;

class Index extends \think\Controller{
	public function index(){
		if(Users::is_login()){
			if(!queryroot(array('用户'=>array('查看'=>1)))){
				return redirect('/Index/Index/Index');
			}
			return $this->fetch('/index/index');
		}else{
			return Users::need_login();
		}
	}
}	