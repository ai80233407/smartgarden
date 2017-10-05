<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use app\user\model\Users;
use app\user\model\Roots_role;

function queryroot($block,$level=0){
	if(Users::is_login()){
		switch($level){
			case 1:
				$role=Roots_role::get_one_role(session('role'));
				$b1=json_decode($role->b1,true);
				//print_r($b1);
				//print_r($block);
				foreach($block as $key=>$val){
					if(empty($b1[$key])){
						return false;
					}
					if($b1[$key]<$val){
						return false;
					}
				}
				return true;
			break;
			case 2:
				$role=Roots_role::get_one_role(session('role'));
				$b2=json_decode($role->b2,true);
				//print_r($b2);
				//print_r($block);
				foreach($block as $key=>$val){
					if(empty($b2[$key])){
						return false;
					}
					if($b2[$key]<$val){
						return false;
					}
				}
				return true;
			break;
			case 3:
				$role=Roots_role::get_one_role(session('role'));
				$b3=json_decode($role->b3,true);
				//print_r($b3);
				//print_r($block);
				foreach($block as $key=>$val){
					if(empty($b3[$key])){
						return false;
					}
					if($b3[$key]<$val){
						return false;
					}
				}
				return true;
			break;
			case 4:
				$role=Roots_role::get_one_role(session('role'));
				$b4=json_decode($role->b4,true);
				//print_r($b4);
				//print_r($block);
				foreach($block as $key=>$val){
					if(empty($b4[$key])){
						return false;
					}
					if($b4[$key]<$val){
						return false;
					}
				}
				return true;
			break;
			default :
				$role=Roots_role::get_one_role(session('role'));
				$b1=json_decode($role->b1,true);
				//print_r($b1);
				//print_r($block);
				foreach($block as $key=>$val){
					if(empty($b1[$key])){
						return false;
					}
					if($b1[$key]<$val){
						return false;
					}
				}
				return true;
			break;
		}
	}else{
		return Users::need_login();
	}
}