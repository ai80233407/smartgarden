<?php
namespace app\index\controller;

use Sms\SmsDemo;
use app\index\model\Temperatures;
use app\index\model\Humiditys;
use app\index\model\Qr_login;

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
	
	public function vcode(){
		$vcode=new \Code\Verify();
		$code=$vcode->make_rand(4);
		session('code',$code);
		$vcode->getAuthImage($code);
		//echo json_encode(array('用户1'=>1));
		//echo queryroot(array('管理'=>array('查看'=>1)))? 'true':'false';
		//echo json_encode(array('管理'=>array('查看'=>1,'新增'=>1)));
	}
	
	public function qr_code($sid=''){
		switch($sid){
			case 'insert':
			case 'Insert':
				$time=input('post.time');
				$key=input('post.key');
				if(!empty($key)&&!empty($time)){
					$time=date("Y-m-d H:i:s",str_replace(substr($time,-3),'',$time));
					Qr_login::insert_qrcode(array('keyword'=>$key,'time'=>$time));
					$msg['error']=0;
					$msg['msg']='获取二维码成功！';
					exit(json_encode($msg));
				}
				$msg['error']=1;
				$msg['msg']='数据为空！';
				exit(json_encode($msg));
			break;
			case 'scanf':
			case 'Scanf':
				$time=input('get.time');
				$key=input('get.key');
				if(!empty($key)&&!empty($time)){
					$msg['key']=$key;
					if(!Qr_login::has_qrcode($key)||Qr_login::timeout_qrcode($key)){
						$msg['error']=1;
						$msg['msg']='二维码不存在或二维码验证已过期！';
						$this->assign('msg',$msg);
						return $this->fetch('/qrlogin/index');
					}
					if(!Qr_login::safety_qrcode($key)){
						$msg['error']=2;
						$msg['msg']='此二维码可能被他人扫描过，存在安全隐患！';
						$this->assign('msg',$msg);
						return $this->fetch('/qrlogin/index');
					}
					if(Qr_login::get_status($key)>=2){
						$msg['error']=4;
						$msg['msg']='此二维码已被验证过，请获取新的二维码！';
						$this->assign('msg',$msg);
						return $this->fetch('/qrlogin/index');
					}
					if(Qr_login::update_qrcode($key,array('status'=>1))){
						$msg['error']=0;
						$msg['msg']='二维码已扫描，等待授权！';
						$this->assign('msg',$msg);
						return $this->fetch('/qrlogin/index');
					}
					$msg['error']=3;
					$msg['msg']='二维码扫描失败，请重新获取二维码！';
					$this->assign('msg',$msg);
					return $this->fetch('/qrlogin/index');
				}
				$msg['error']=5;
				$msg['msg']='数据为空！';
				$this->assign('msg',$msg);
				return $this->fetch('/qrlogin/index');
			break;
			case 'Query_status':
				$time=input('post.time');
				$key=input('post.key');
				if(!empty($key)&&!empty($time)){
					if(!Qr_login::has_qrcode($key)){
						$msg['error']=3;
						$msg['msg']='二维码不存！';
						exit(json_encode($msg));
					}
					if(Qr_login::timeout_qrcode($key)){
						Qr_login::del_qrcode($key);
						$msg['error']=6;
						$msg['msg']='二维码验证已过期！';
						exit(json_encode($msg));
					}
					if(Qr_login::get_status($key)==0){
						$msg['error']=2;
						$msg['msg']='等待用户扫描二维码！';
					}
					if(Qr_login::get_status($key)==1){
						$msg['error']=1;
						$msg['msg']='二维码已扫描，等待用户授权！';
					}
					if(Qr_login::get_status($key)==2){
						Qr_login::del_qrcode($key);
						session(null);
						$rootlist=\app\user\model\Roots_role::get_one_role(9)->rootlist;
						session('rootlist',$rootlist);
						session('uid',9);
						$msg['error']=0;
						$msg['msg']='用户授权成功，等待页面跳转！';
					}
					if(Qr_login::get_status($key)==3){
						Qr_login::del_qrcode($key);
						$msg['error']=5;
						$msg['msg']='用户拒绝授权，请使用帐号登录！';
					}
					exit(json_encode($msg));
				}
				$msg['error']=4;
				$msg['msg']='数据为空！';
				exit(json_encode($msg));
			break;
			case 'Auth':
				$auth=input('post.auth');
				$key=input('post.key');
				if(empty($auth)||empty($key)){
					$msg['error']=1;
					$msg['msg']='数据为空！';
					exit(json_encode($msg));
				}
				if($auth=='auth'){
					Qr_login::update_qrcode($key,array('status'=>2,'uid'=>9));
					$msg['error']=2;
					$msg['msg']='已授权！';
					exit(json_encode($msg));
				}
				if($auth=='noauth'){
					Qr_login::update_qrcode($key,array('status'=>3));
					$msg['error']=3;
					$msg['msg']='已拒绝授权！';
					exit(json_encode($msg));
				}
				$msg['error']=4;
				$msg['msg']='数据错误！';
				exit(json_encode($msg));
			break;
			default:
				$msg['error']=1;
				$msg['msg']='sid参数不存在！';
				exit(json_encode($msg));
		}
	} 
	
	public function temperature(){
		if(request()->isPost()){
			$value=input('post.value');
			$time=input('post.time');
			$eid=input('post.eid');
			if(empty($value)){
				exit(json_encode(array('tempstatus'=>false,'msg'=>'数据为空')));
			}
			Temperatures::add_one_temp(array('value'=>$value,'time'=>$time,'eid'=>$eid));
			exit(json_encode(array('tempstatus'=>true,'msg'=>'添加成功')));
		}else{
			exit(json_encode(array('tempstatus'=>false,'msg'=>'请求不合法')));
		}
	}
	
	public function humidity(){
		if(request()->isPost()){
			$value=input('post.value');
			$time=input('post.time');
			$eid=input('post.eid');
			if(empty($value)){
				exit(json_encode(array('humistatus'=>false,'msg'=>'数据为空')));
			}
			Humiditys::add_one_humi(array('value'=>$value,'time'=>$time,'eid'=>$eid));
			exit(json_encode(array('humistatus'=>true,'msg'=>'添加成功')));
		}else{
			exit(json_encode(array('humistatus'=>false,'msg'=>'请求不合法')));
		}
	}
}