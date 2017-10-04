<?php
namespace app\index\validate;

use think\Validate;

class Register extends Validate{
	protected $rule=[
		'account|帐号'=>'alphaDash|require|length:6,18|token',
		'password|密码'=>'alphaDash|require|length:6,18',
		'phone|手机号'=>'number|length:11',
		'email|邮箱'=>'email',
		'repwd|重复密码'=>'looklike|require',
	];
	
	protected $message=[
		'repwd'=>'两次输入密码不一致！',
	];
	
	protected $scene=[
		'login'=>['account','password'],
		'register'=>['account','password','phone','email','repwd'],
	];
	function looklike($value,$rule,$data){
		return $value==$data['password'] ? true:false;
	}
}