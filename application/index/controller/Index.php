<?php
namespace app\index\controller;

use Mine\Slide;
use app\user\model\Users;
use app\user\model\Roots_role;

class Index extends \think\Controller{
    
	public function index(){
		return $this->fetch('index/index');
	}
	
	public function login(){
		if(request()->isGet()){
			return $this->fetch('login/login');
		}else{
			// 先验证
			// 若不通过，程序会结束，并输出对应返回信息，告诉前端，需要重新获取验证码
			Slide::instance(3); 
			// 通过，程序则继续执行
			// RSA 解密 、 [使用了加密函数的，都需要 urldecode 再次解码]
			$account = urldecode(   \Crypt\Rsa::decrypt(input('post.account'))    );
			$pwd  = urldecode(   \Crypt\Rsa::decrypt(input('post.pwd'))     );
			$token = urldecode(   \Crypt\Rsa::decrypt(input('post.__token__'))     );
			// 待验证的 帐号与密码
			//$account=input('post.account');
			//$pwd=input('post.pwd');
			//echo sha1($pwd);
			if(empty($account)||empty($pwd)){
				$msg['logstatus']  = false; 
				// 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
				$msg['out'] = '帐号或者密码为空哟';
				exit( json_encode($msg)  );
			}
			$validate=validate('Register');
			if(!$validate->scene('login')->check(array('account'=>$account,'password'=>$pwd,'__token__'=>$token))){
				$msg['logstatus']=false;
				$msg['out']=$validate->getError();
				exit(json_encode($msg));
			}
			$test=Users::get_one_record($account);
			if(empty($test->account)){
				$msg['logstatus']  = false; 
				// 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
				$msg['out'] = '帐号不存在哟';
				exit( json_encode($msg)  );
			}
			$user_account = $test->account;
			$password = $test->password;
			$uid=$test->id;
			$status=$test->status;
			$role=$test->role;
			if( !($account==$user_account &&  sha1($pwd)==$password) ){
				// 验证成功时，一定要返回  {"status":true} 
				// 需要用户重新输入的时候，一定要返回  {"status":false} 
				$msg['logstatus']  = false; 
				// 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
				$msg['out'] = '帐号或者密码不正确哟';
				exit( json_encode($msg)  );
			}
			if($status!=1){
				$msg['logstatus']  = false; 
				// 提示用户的错误信息，请以 {"status":false,"out":"这里是错误信息"} 形式输出
				$msg['out'] = '您的帐号受限，无法登录';
				exit( json_encode($msg) );
			}
			session(null);
			$rootlist=Roots_role::get_one_role($role)->rootlist;
			session('rootlist',$rootlist);
			session('uid',$uid);
			$msg['logstatus']  = true;
			// 若验证成功后需要跳转到某个地址 {"status":true,"url":"/"} 形式输出
			$msg['out'] = '登陆成功';
			if(queryroot(array('用户'=>array('查看'=>1),'管理'=>array('查看'=>1)))){
				$msg['url'] = '/Index/Index/Index';
			}else if(queryroot(array('用户'=>array('查看'=>1)))){
				$msg['url'] = '/User/Index/Index';
			}else if(queryroot(array('管理'=>array('查看'=>1)))){
				$msg['url'] = '/Admin/Index/Index';
			}else{
				$msg['url'] = '/Index/About/Index';
			}
			Users::update_one_logtime(array('id'=>$uid,'lastime'=>date("Y-m-d H:i:s")));
			exit( json_encode($msg) );
		}
	}
		
	public function out_login(){
		Users::out_login();
		return redirect('/');
	}
	
	public function Register(){
		if(request()->isGet()){
			return $this->fetch('register/register');
		}else{
			$regdata=input('post.');
			if(empty($regdata)){
				$msg['regstatus']=false;
				$msg['out']='注册信息为空！';
				exit(json_encode($msg));
			}
			//Loader::validate('Register');
			//$this->validate($data,'Register.register');
			$validate=validate('Register');
			if(!$validate->scene('register')->check($regdata)){
				$msg['regstatus']=false;
				$msg['out']=$validate->getError();
				exit(json_encode($msg));
			}
			unset($regdata['__token__']);
			unset($regdata['repwd']);
			if(time()-session('user.sendtime')>301){
				$msg['regstatus']=false;
				$msg['out']='短信验证码超时！';
				exit(json_encode($msg));
			}
			if($regdata['sms']!=session('user.validate')){
				$msg['regstatus']=false;
				$msg['out']='短信验证码错误！';
				//$msg['out']=session('user.validate');
				exit(json_encode($msg));
			}
			unset($regdata['sms']);
			if(!empty(Users::get_one_record($regdata['account'])->id)){
				$msg['regstatus']=false;
				$msg['out']='账号已经存在！';
				exit(json_encode($msg));
			}
			$regdata['password']=sha1($regdata['password']);
			$regdata['regtime']=date("Y-m-d H:i:s");
			$regdata['lastime']=$regdata['regtime'];
			$regdata['nickname']=$regdata['account'];
			$regdata['role']=1;
			$regdata['status']=1;
			$regdata['head']=rand(1,20);
			Users::add_one_user($regdata);
			$msg['regstatus']=true;
			$msg['out']='注册成功！';
			session(null);
			exit(json_encode($msg));
		}
	}
}
