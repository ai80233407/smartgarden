<?php
namespace app\index\model;

use think\Model;

class Temperatures extends Model{
	
	public static function add_one_temp($data){
		
		return Temperatures::insert($data);
	}
	
	public static function get_thirty_temp(){
		
		return Temperatures::where('1=1')->order(['time'=>'desc'])->limit(30)->select();
	}
}