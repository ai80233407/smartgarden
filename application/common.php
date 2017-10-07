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

function queryroot($block){
	if(!empty(session('rootlist'))){
		//echo $role->rootlist;
		$b1=json_decode(session('rootlist'),true);
		//print_r($b1);
		//print_r($block);
		foreach($block as $key=>$val){
			if(empty($b1[$key])){
				return false;
			}
			foreach($val as $k=>$v){
				if(empty($b1[$key][$k])){
					return false;
				}
				if($b1[$key][$k]!=$v){
					return false;
				}
			}
		}
		return true;
	}else{
		return Users::need_login();
	}
}