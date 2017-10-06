<?php
namespace app\admin\controller;

use think\Controller;
use app\user\model\Users;

class User extends Controller{
	
	public function all_user(){
		if(Users::is_login()){
			if(!queryroot(array('所有用户'=>array('查看'=>1)))){
				return redirect('/Admin/Index/Index');
			}
			if(request()->isPost()){
				
			}
			$key=input('get.key');
			if(empty($key)){
				$key='';
				$alluser=Users::get_all_user();
			}else{
				$alluser=Users::search_user($key);
			}
			$this->assign('key',$key);
			$this->assign('userlist',$alluser);
			//dump($alluser);
			return $this->fetch('/user/alluser');
		}else{
			return Users::need_login();
		}
	}
	
}