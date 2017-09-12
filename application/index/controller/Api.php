<?php
namespace app\index\controller;

class Api extends \think\Controller{
	public function login(){
		return $this->fetch('login/login');
	}
	
	public function Test(){
		echo phpinfo();
		//return $this->fetch('');
	}
}