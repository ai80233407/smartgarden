<?php
namespace app\index\controller;

use app\user\model\Users;
use Mine\Slide;

class Api extends \think\Controller{
	public function login(){
		// 先验证
            // 若不通过，程序会结束，并输出对应返回信息，告诉前端，需要重新获取验证码
        Slide::instance(3); 
            // 通过，程序则继续执行
                // RSA 解密 、 [使用了加密函数的，都需要 urldecode 再次解码]
        $name = urldecode(   \Crypt\Rsa::decrypt(input('post.name'))    );
        $pwd  = urldecode(   \Crypt\Rsa::decrypt(input('post.pwd'))     );
            // 待验证的 帐号与密码
		//$name=input('post.name');
		//$pwd=input('post.pwd');
        $user_account = 'admin';
        $password = '123';
		$uid=1;
        if( $name==$user_account &&  $pwd==$password ){
            // 验证成功时，一定要返回  {"status":true} 
            $msg['logstatus']  = true;
                // 若验证成功后需要跳转到某个地址 {"status":true,"url":"/"} 形式输出
            $msg['url']     = '/User/Index/Index';
			$msg['out'] = '登陆成功';
			session('uid',$uid);
            exit( json_encode($msg) );
        }else{
            // 需要用户重新输入的时候，一定要返回  {"status":false} 
            $msg['logstatus']  = false; 
            // 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
            $msg['out'] = '帐号或者密码不正确哟';
            exit( json_encode($msg)  );
        }
		//return $this->fetch('login/login');
	}
	
	public function out_login(){
		$User=new Users;
		$User->out_login();
		return redirect('/');
	}
	
	public function test(){
		echo phpinfo();
		//return $this->fetch('');
	}
}