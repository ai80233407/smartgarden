<?php
namespace app\user\controller;

use app\user\model\Users;

class Medio extends \think\Controller {	
	
	public function Index(){
		if(Users::is_login()){
			if(!queryroot(array('环境信息'=>array('查看'=>1)))){
				return redirect('/User/Index/Index');
			}
			return $this->fetch('/medio/index');
		}else{
			return Users::need_login();
		}
	}
	
	public function Monitor(){
		if(Users::is_login()){
			if(!queryroot(array('实时动态'=>array('查看'=>1)))){
				return redirect('/User/Index/Index');
			}
			return $this->fetch('/medio/monitor');
		}else{
			return Users::need_login();
		}
	}
	
	public function Statistics(){
		if(Users::is_login()){
			if(!queryroot(array('统计信息'=>array('查看'=>1)))){
				return redirect('/User/Index/Index');
			}
			return $this->fetch('/medio/statistics');
		}else{
			return Users::need_login();
		}
	}
}