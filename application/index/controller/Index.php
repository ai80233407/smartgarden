<?php
namespace app\index\controller;

class Index extends \think\Controller{
    public function index(){
		return $this->fetch('index/index');
	}
	
	public function login(){
		return $this->fetch('login/login');
	}
	
	public function Register(){
		return $this->fetch('register/register');
	}
	
}
