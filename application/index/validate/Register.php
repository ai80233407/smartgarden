<?php
namespace app\index\validate;

use think\Validate;

class Register extends Validate{
	protected $rule=[
		'account|帐号'=>'alphaDash|require|length:6,18',
		'password|密码'=>'alphaDash|require|length:6,18',
		'phone|手机号'=>'number|length:11',
		'email|邮箱'=>'email',
		'repwd|重复密码'=>'looklike|require',
		'sms|短信验证码'=>'require|number|length:6',
		'token|令牌'=>'token',
	];
	
	protected $message=[
		'repwd'=>'两次输入密码不一致！',
		'token'=>'令牌失效，请刷新页面后重新提交！',
	];
	
	protected $scene=[
		'login'=>['account','password'],
		'register'=>['account','password','phone','email','repwd','sms'],
		'token'=>['token'],
	];
	function looklike($value,$rule,$data){
		return $value==$data['password'] ? true:false;
	}
}