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
		if(islogined()){
			return $this->redirect('/');
		}
		if(request()->isGet()){
			return $this->fetch('login/login');
		}else{
			// 先验证
			// 若不通过，程序会结束，并输出对应返回信息，告诉前端，需要重新获取验证码
			Slide::instance(3); 
			// 通过，程序则继续执行
			// RSA 解密 、 [使用了加密函数的，都需要 urldecode 再次解码]
			$account = urldecode(\Crypt\Rsa::decrypt(input('post.account')));
			$pwd  = urldecode(\Crypt\Rsa::decrypt(input('post.pwd')));
			$token = urldecode(\Crypt\Rsa::decrypt(input('post.__token__')));
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
			if(!$validate->scene('login')->check(array('account'=>$account,'password'=>$pwd))){
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
			if(!$validate->scene('token')->check(array('__token__'=>$token))){
				$msg['logstatus']=false;
				$msg['out']=$validate->getError();
				exit(json_encode($msg));
			}
			$remeber=urldecode(\Crypt\Rsa::decrypt(input('post.remeber')));
			if($remeber==1){
				cookie(['prefix'=>'think_','expire'=>60*60,'path'=>'/']);
				cookie('account',$account,60*60*24*365);
				cookie('password',input('post.pwd'),60*60*24*365);
				cookie('remeber',1,60*60*24*365);
			}else{
				cookie(null,'think_');
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
		if(islogined()){
			return $this->redirect('/');
		}
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
	
	public function forgot(){
		if(islogined()){
			return $this->redirect('/');
		}
		if(request()->isPost()){
			$account=input('post.account');
			$email=input('post.email');
			$verify=input('post.verify');
			$pass=input('post.password');
			$repwd=input('post.repwd');
			$emailcode=input('post.emailcode');
			if(!empty($account)){
				session(null);
				$user=Users::get_one_record($account);
				if(empty($user->id)){
					$msg['error']=1;
					$msg['msg']='账号不存在！';
					die(json_encode($msg));
				}
				if(empty($user->email)){
					$msg['error']=1;
					$msg['msg']='账号未绑定邮箱，无法通过邮箱找回！';
					die(json_encode($msg));
				}
				session('tempid',$user->id);
				session('email',$user->email);
				session('nick',$user->nickname);
				die(json_encode(array('error'=>0,'msg'=>'帐号获取成功！','email'=>hidestr($user->email,0,4,2))));
			}
			if(!empty($email)){
				if($email!=session('email')||!session('email')){
					$msg['error']=1;
					$msg['msg']='邮箱信息不正确或未获取到邮箱信息！';
					die(json_encode($msg));
				}
				session('emailcheck',1);
				die(json_encode(array('error'=>0,'msg'=>'邮箱验证成功！')));
			}
			if(!empty($verify)){
				if(strtoupper($verify)!=session('code')||!session('emailcheck')||!session('email')){
					$msg['error']=1;
					$msg['msg']='验证码信息不正确或邮箱信息未验证！';
					die(json_encode($msg));
				}
				session('code',null);
				$email=new \Email\SendEmail();
				$code=rand(100000,999999);
				$result=$email->send(array('add'=>session('email'),'nick'=>session('nick'),'number'=>$code));
				if(empty($result)){
					$msg['error']=1;
					$msg['msg']='邮件发送失败！';
					die(json_encode($msg));
				}else{
					session('emailcode',$code);
					$msg['error']=0;
					$msg['msg']='邮件发送成功！';
					die(json_encode($msg));
				}
			}
			if(!empty($emailcode)){
				if($emailcode!=session('emailcode')||!session('emailcode')||!session('tempid')||!session('emailcheck')){
					$msg['error']=1;
					$msg['msg']='邮件验证码信息不正确或帐号、邮箱信息未验证！';
					die(json_encode($msg));
				}
				if($pass!=$repwd&&!empty($pass)){
					$msg['error']=1;
					$msg['msg']='两次输入的密码不一致或输入的密码为空！';
					die(json_encode($msg));
				}
				Users::change_pass(session('tempid'),array('password'=>sha1($pass)));
				session(null);
				die(json_encode(array('error'=>0,'msg'=>'密码重置成功！')));
			}
			die(json_encode(array('error'=>1,'msg'=>'数据为空！')));
		}else{
			return $this->fetch('/forgot/index');
		}
	}
}
