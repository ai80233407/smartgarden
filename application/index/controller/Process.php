<?php
namespace app\index\controller;

class Process extends \think\Controller{
    public function index()
    {
		return $this->fetch('process/index');
	}
}