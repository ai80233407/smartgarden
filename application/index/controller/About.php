<?php
namespace app\index\controller;

class About extends \think\Controller{
    public function index()
    {
		return $this->fetch('about/index');
	}
}