<?php
namespace app\index\controller;

use app\user\model\Users;

class Index extends \think\Controller{
    public function index(){
		return $this->fetch('index/index');
	}
	
	public function login(){
		return $this->fetch('login/login');
	}
	
	public function Register(){
		if(request()->isGet()){
			return $this->fetch('register/register');
		}else{
			$regdata=input('post.');
			if(!empty($regdata)){
				//Loader::validate('Register');
				//$this->validate($data,'Register.register');
				$validate=validate('Register');
				if($validate->scene('register')->check($regdata)){
					unset($regdata['__token__']);
					unset($regdata['repwd']);
					unset($regdata['sms']);
					if(empty(Users::get_one_record($regdata['account'])->id)){
						$regdata['password']=sha1($regdata['password']);
						$regdata['regtime']=date("Y-m-d H:i:s");
						$regdata['lastime']=$regdata['regtime'];
						$regdata['nickname']=$regdata['account'];
						$regdata['role']=1;
						$regdata['status']=2;
						$regdata['head']=rand(1,20);
						Users::add_one_user($regdata);
						$msg['regstatus']=true;
						$msg['out']='注册成功！';
						exit(json_encode($msg));
					}else{
						$msg['regstatus']=false;
						$msg['out']='账号已经存在！';
						exit(json_encode($msg));
					}
				}else{
					$msg['regstatus']=false;
					$msg['out']=$validate->getError();
					exit(json_encode($msg));
				}
			}else{
				$msg['regstatus']=false;
				$msg['out']='注册信息为空！';
				exit(json_encode($msg));
			}
		}
	}
	
}
