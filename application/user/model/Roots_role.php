<?php
namespace app\user\model;

use think\Model;

class Roots_role extends Model{
	
	protected $table='root_role';
	
	public static function get_one_role($id){
		return self::get($id);
	}
	
}