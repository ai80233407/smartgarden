<?php
namespace app\user\controller;

class Index extends \think\Controller{
	public function index(){
		$User=model('Users');
		if($User->is_login()){
			return $this->fetch('/index/index');
		}else{
			return $User->need_login();
		}
	}
}	