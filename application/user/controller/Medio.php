<?php
namespace app\user\controller;

class Medio extends \think\Controller {	
	
	public function Index(){
		$User=model('Users');
		if($User->is_login()){
			return $this->fetch('/medio/index');
		}else{
			return $User->need_login();
		}
	}
	
	public function Monitor(){
		$User=model('Users');
		if($User->is_login()){
			return $this->fetch('/medio/monitor');
		}else{
			return $User->need_login();
		}
	}
	
	public function Statistics(){
		$User=model('Users');
		if($User->is_login()){
			return $this->fetch('/medio/statistics');
		}else{
			return $User->need_login();
		}
	}
}