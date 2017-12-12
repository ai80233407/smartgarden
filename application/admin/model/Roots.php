<?php
namespace app\admin\model;

use think\Model;

class Roots extends Model{
	
	protected $table='root';
	
	public static function get_all_root(){
		
		return Roots::where('1=1')->paginate(10,false,['type'=>'app\common\page\Amh']);
		
	}
	
	public static function search_root($key){
		
		return Roots::where("name like '%{$key}%' or mark like '%{$key}%'")->paginate(10,false,['type'=>'app\common\page\Amh']);
		
	}
	
	public static function get_all_block(){
		
		return Roots::table('root_block b')->field('b.id,b.name,r.name as pname,b.level,b.mark,b.ctime')->join('root_block r','b.parent=r.id','left')->paginate(10,false,['type'=>'app\common\page\Amh']);
		
	}
	
	public static function get_block_level($name){
		return Roots::table('root_block')->where("name= '{$name}'")->value('level');
	}
	
	public static function get_block_id($name){
		return Roots::table('root_block')->where("name= '{$name}'")->value('id');
	}
	
	public static function get_block_bylevel($level){

		return Roots::table('root_block')->where("level = '{$level}'")->select();
	}
	
	public static function get_block_byparent($parent){
		return Roots::table('root_block')->where("parent = '{$parent}'")->select();
	}
	
	public static function search_block($key){
		
		return Roots::table('root_block b')->where("b.name like '%{$key}%' or b.mark like '%{$key}%' or b.level='{$key}'")->field('b.id,b.name,r.name as pname,b.level,b.mark,b.ctime')->join('root_block r','b.parent=r.id','left')->paginate(10,false,['type'=>'app\common\page\Amh']);
		
	}
	
	public static function get_one_role($id){
		
		return Roots::table('root_role')->where("id='{$id}'")->find();
		
	}
	
	public static function get_all_role(){
		
		return Roots::table('root_role')->where('1=1')->paginate(10,false,['type'=>'app\common\page\Amh']);
		
	}
	
	public static function update_role_byid($id,$data){
		
		return Roots::table('root_role')->where("id = '{$id}'")->update($data);
		
	}
	
	public static function search_role($key){
		
		return Roots::table('root_role')->where("name like '%{$key}%' or mark like '%{$key}%' or rootlist like '%{$key}%'")->paginate(10,false,['type'=>'app\common\page\Amh']);
		
	}
	
	public static function insert_one_role($data){
		
		return Roots::table('root_role')->insert($data);
		
	}
	
	public static function del_one_role($id){
		
		return Roots::table('root_role')->where("id = '{$id}'")->delete();
		
	}
}
