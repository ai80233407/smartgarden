<?php
namespace app\index\controller;

use app\user\model\Users;
use Mine\Slide;
use Sms\SmsDemo;

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
				session(null);
				$msg['logstatus']  = true;
				// 若验证成功后需要跳转到某个地址 {"status":true,"url":"/"} 形式输出
				$msg['url']     = '/User/Index/Index';
				$msg['out'] = '登陆成功';
				session('uid',$uid);
				Users::update_one_logtime(array('id'=>$uid,'lastime'=>date("Y-m-d H:i:s")));
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
		Users::out_login();
		return redirect('/');
	}
	
	public function send_sms(){
		// 调用示例：
		$phone=input('post.phone');
		if(empty($phone)){
			$msg['sendstatus']=false;
			$msg['out']='手机号为空！';
			exit(json_encode($msg));
		}
		if(session('?user.count')){
			if(session('user.count')==6){
				$delaytime=60*60;
			}else if(session('user.count')==11){
				$delaytime=60*60*24;
			}else{
				$delaytime=60;
			}
		}
		$sendtime=time();
		$validate=rand(100000,999999);
		header('Content-Type: text/plain; charset=utf-8');

		$demo = new SmsDemo(
			"LTAIW0QlIRWMIMyL",
			"FMOJuQhOjSzQlPQXez5QMWd6qAjmj5"
		);
		if(true){
			if(isset($delaytime)&&($phone==session('user.phone'))){
				if(time()-session('user.sendtime')<$delaytime){
					$msg['sendstatus']=false;
					$msg['out']='请于 '.$delaytime.'s. 后在获取验证码!';
					exit(json_encode($msg));
				}
			}
			$response = $demo->sendSms(
				"智能花园", // 短信签名
				"SMS_101185042", // 短信模板编号
				$phone, // 短信接收者
				Array(  // 短信模板中字段的值
					"code"=>$validate
				),
				"SGYZ-".$phone.'-'.$sendtime
			);
			switch($response->Code){
				case 'OK':
					session('user.validate',$validate);
					session('user.phone',$phone);
					session('user.sendtime',$sendtime);
					if(session('?user.count')){
						$count=session('user.count');
						session('user.count',$count+1);
					}else{
						session('user.count',1);
					}
					$msg['sendstatus']=true;
					$msg['out']='验证码发送成功！';
					exit(json_encode($msg));
				break;
				case 'isv.BUSINESS_LIMIT_CONTROL':
					session('user.validate',$validate);
					session('user.phone',$phone);
					session('user.sendtime',$sendtime);
					if(substr($response->Message,-1,1)=='5'){
						$delaytime=60*60;
						session('user.count',6);
					}else if(substr($response->Message,-1,1)=='0'){
						$delaytime=60*60*24;
						session('user.count',11);
					}else{
						$delaytime=60;
						session('user.count',1);
					}
					$msg['sendstatus']=false;
					$msg['out']='请于 '.$delaytime.'s. 后在获取验证码!';
					//$msg['out']=$response->Message;
					//$msg['out']=substr($response->Message,-1,1);
					exit(json_encode($msg));
				break;
				default :
					$msg['sendstatus']=false;
					$msg['out']='未知原因，获取验证码失败，请与我们联系！';
					exit(json_encode($msg));
				break;	
			}
		}else if(false){
			$response = $demo->sendSms(
				"智能花园", // 短信签名
				"SMS_101230041", // 短信模板编号
				'15842273571', // 短信接收者
				Array(  // 短信模板中字段的值
					"role"=>'管理员',
					"name"=>'80233407',
					"conten"=>'今日发送验证码次数已达到最大值'
				),
				'SGTZ-15842273571-'.$sendtime
			);
			$msg['sendstatus']=false;
			$msg['out']='已达到今日发送验证码次数最大值，系统已通知管理员！';
			exit(json_encode($msg));
		}else{
			$msg['sendstatus']=false;
			$msg['out']='已达到今日发送验证码次数最大值！';
			exit(json_encode($msg));
		}
		//echo "SmsDemo::sendSms\n";
		
		
		/*
		$bizid=$response->BizId;
		print_r($response);

		
		echo "SmsDemo::queryDetails\n";
		$response = $demo->queryDetails(
			$phone,  // phoneNumbers 电话号码
			date("Ymd"), // sendDate 发送时间
			10, // pageSize 分页大小
			1, // currentPage 当前页码
			$bizid// "abcd" // bizId 短信发送流水号，选填
		);

		print_r($response);
		*/
		//echo sha1('smartgarden');
		//return $this->fetch('');

	}
}