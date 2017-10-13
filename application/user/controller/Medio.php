<?php
namespace app\user\controller;

use app\user\model\Users;
use app\index\model\Temperatures;
use app\index\model\Humiditys;

class Medio extends \think\Controller {	
	
	public function Index(){
		if(Users::is_login()){
			if(!queryroot(array('环境信息'=>array('查看'=>1)))){
				return redirect('/User/Index/Index');
			}
			if(request()->isPost()){
				foreach(Temperatures::get_thirty_temp() as $value){
					$times[]=substr($value['time'],11,5);
					$data[]=(int)$value['value'];
				}
				$humi=Humiditys::get_im_humi();
				exit(json_encode(array('value'=>$humi['value'],'time'=>substr($humi['time'],11,5),'tempstatuse'=>true,'times'=>array_reverse($times),'data'=>array_reverse($data))));
			}else{
				return $this->fetch('/medio/index');
			}
		}else{
			return Users::need_login();
		}
	}
	
	public function Monitor(){
		if(Users::is_login()){
			if(!queryroot(array('实时动态'=>array('查看'=>1)))){
				return redirect('/User/Index/Index');
			}
			if(request()->isPost()){
				
			}else{
				return $this->fetch('/medio/monitor');
			}
		}else{
			return Users::need_login();
		}
	}
	
	public function Statistics(){
		if(Users::is_login()){
			if(!queryroot(array('统计信息'=>array('查看'=>1)))){
				return redirect('/User/Index/Index');
			}
			return $this->fetch('/medio/statistics');
		}else{
			return Users::need_login();
		}
	}
}