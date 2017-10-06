<?php
namespace app\index\controller;

use Sms\SmsDemo;

class Api extends \think\Controller{
	
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
	
	public function test(){
		//echo json_encode(array('用户1'=>1));
		echo queryroot(array('管理'=>array('查看'=>1)))? 'true':'false';
		//echo json_encode(array('管理'=>array('查看'=>1,'新增'=>1)));
	}
}