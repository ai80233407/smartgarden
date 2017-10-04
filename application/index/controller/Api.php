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
        $account = urldecode(   \Crypt\Rsa::decrypt(input('post.account'))    );
        $pwd  = urldecode(   \Crypt\Rsa::decrypt(input('post.pwd'))     );
            // 待验证的 帐号与密码
		//$account=input('post.account');
		//$pwd=input('post.pwd');
		//echo sha1($pwd);
		if(empty($account)||empty($pwd)){
			$msg['logstatus']  = false; 
            // 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
            $msg['out'] = '帐号或者密码为空哟';
            exit( json_encode($msg)  );
		}else{
			$test=Users::get_one_record($account);
			if(empty($test->account)){
				$msg['logstatus']  = false; 
				// 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
				$msg['out'] = '帐号不存在哟';
				exit( json_encode($msg)  );
			}else{
				$user_account = $test->account;
				$password = $test->password;
				$uid=$test->id;
				$status=$test->status;
			}
		}
		
        
        if( $account==$user_account &&  sha1($pwd)==$password ){
            // 验证成功时，一定要返回  {"status":true} 
            if($status==1){
				$msg['logstatus']  = true;
				// 若验证成功后需要跳转到某个地址 {"status":true,"url":"/"} 形式输出
				$msg['url']     = '/User/Index/Index';
				$msg['out'] = '登陆成功';
				session('uid',$uid);
				exit( json_encode($msg) );
			}else{
				$msg['logstatus']  = false; 
				// 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
				$msg['out'] = '您的帐号受限，无法登录';
				exit( json_encode($msg)  );
			}
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