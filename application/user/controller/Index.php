<?php
namespace app\user\controller;

class Index extends \think\Controller{
	public function index(){
		return $this->fetch('/index/index');
	}
}	