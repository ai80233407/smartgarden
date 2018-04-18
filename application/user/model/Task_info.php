<?php

namespace app\user\model;

use think\Model;

class Task_info extends Model{
	
	public static function insert_task($arr){
		return Task_info::insert($arr);
	}
	
	public static function del_task($id){
		return Task_info::where("id='{$id}'")->delete();
	}
	
	public static function update_task($id,$arr){
		return Task_info::where("id='{$id}'")->update($arr);
	}
	
	public static function one_task($id){
		return Task_info::where("id='{$id}'")->find();
	}
	
	public static function all_task(){
		return Task_info::where('1=1')->paginate(10,false,['type'=>'app\common\page\Amh']);
	}
	
	public static function all_task_id(){
		return Task_info::where('1=1')->field('id')->select();
	}
	
	public static function search_task($key){
		return Task_info::where("start like '%{$key}%' or name like '%{$key}%' or type like '%{$key}%' or mark like '%{$key}%'")->paginate(10,false,['type'=>'app\common\page\Amh']);
	}
	
	public static function task_id($name){
		return Task_info::where("name='{$name}'")->value('id');
	}
	
	public static function task_count($id){
		return Task_info::where("id='{$id}'")->setInc('counts');
	}
	
	public static function task_lasttime($id){
		return Task_info::where("id='{$id}'")->update(array('lasttime'=>date("Y-m-d H:i:s")));
	}
	
	public static function firststart_up($id){
		return Task_info::where("id='{$id}'")->update(array('firststart'=>0));
	}
	
	public static function firststart_down($id){
		return Task_info::where("id='{$id}'")->update(array('firststart'=>1));
	}
	
	public static function task_status_up($id){
		return Task_info::where("id='{$id}'")->update(array('status'=>0));
	}
	
	public static function task_status_down($id){
		return Task_info::where("id='{$id}'")->update(array('status'=>1));
	}
}