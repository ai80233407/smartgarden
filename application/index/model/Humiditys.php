<?php
namespace app\index\model;

use think\Model;

class Humiditys extends Model{
	
	public static function add_one_humi($data){
		
		return Humiditys::insert($data);
	}
	
	public static function get_im_humi(){
		return Humiditys::where('1=1')->order(['time' =>'desc'])->find();
	}
	
}