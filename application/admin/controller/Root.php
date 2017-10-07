<?php
namespace app\admin\controller;
 
use think\Controller;
use app\admin\model\Roots;
use app\user\model\Users;
 
class Root extends Controller{
	 
	 public function root_list(){
		 if(Users::is_login()){
			 if(!queryroot(array('权限列表'=>array('查看'=>1)))){
				 return redirect('/Admin/Index/Index');
			 }
			 $key=input('get.key');
			 if(empty($key)){
				 $rootlist=Roots::get_all_root();
			 }else{
				 $rootlist=Roots::search_root($key);
			 }
			 $this->assign('key',$key);
			 $this->assign('rootlist',$rootlist);
			 return $this->fetch('/root/rootlist');
		 }else{
			 return Users::need_login();
		 }
	 }
	 
	 public function block_list(){
		 if(Users::is_login()){
			 if(!queryroot(array('模块管理'=>array('查看'=>1)))){
				 return redirect('/Admin/Index/Index');
			 }
			 $key=input('get.key');
			 if(empty($key)){
				 $blocklist=Roots::get_all_block();
			 }else{
				 $blocklist=Roots::search_block($key);
			 }
			 //dump($blocklist);
			 $this->assign('key',$key);
			 $this->assign('blocklist',$blocklist);
			 return $this->fetch('/root/blocklist');
		 }else{
			 return Users::need_login();
		 }
	 }
	 
	 public function role_list(){
		 if(Users::is_login()){
			 if(!queryroot(array('角色管理'=>array('查看'=>1)))){
				 return redirect('/Admin/Index/Index');
			 }
			 $key=input('get.key');
			 if(empty($key)){
				 $rolelist=Roots::get_all_role();
			 }else{
				 $rolelist=Roots::search_role($key);
			 }
			 //dump($rolelist);
			 $this->assign('key',$key);
			 $this->assign('rolelist',$rolelist);
			 return $this->fetch('/root/rolelist');
		 }else{
			 return Users::need_login();
		 }
	 }
	 
	 public function role_look(){
		 if(islogined()){
			 if(!queryroot(array('角色管理'=>array('查看'=>1)))){
				 return redirect('/Admin/Index/Index');
			 }
			 $id=input('get.id');
			 if(empty($id)){
				 return redirect('/Admin/Root/Root_list');
			 }
			 $role=Roots::get_one_role($id);
			 //print_r($role);
			 $this->assign('role',$role);
			 return $this->fetch('/root/rolelook');
		 }else{
			 return needlogined();
		 }
	 }
 }