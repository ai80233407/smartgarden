<?php
namespace app\user\controller;

use app\user\model\Users;

class Medio extends \think\Controller {	
	
	public function Index(){
		if(Users::is_login()){
			return $this->fetch('/medio/index');
		}else{
			return Users::need_login();
		}
	}
	
	public function Monitor(){
		if(Users::is_login()){
			return $this->fetch('/medio/monitor');
		}else{
			return Users::need_login();
		}
	}
	
	public function Statistics(){
		if(Users::is_login()){
			return $this->fetch('/medio/statistics');
		}else{
			return Users::need_login();
		}
	}
}