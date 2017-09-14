<?php
namespace app\user\controller;

class Medio extends \think\Controller {
	public function Index(){
		return $this->fetch('/medio/index');
	}
	
	public function Monitor(){
		return $this->fetch('/medio/monitor');
	}
	
	public function Statistics(){
		return $this->fetch('/medio/statistics');
	}
}