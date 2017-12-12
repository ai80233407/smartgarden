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
	 
	 public function role_add(){
		 if(islogined()){
			 if(!queryroot(array('角色管理'=>array('查看'=>1,"新增"=>1)))){
				return redirect('/Admin/Index/Index');
			 }
			 $roleinfo['name']=input('post.name');
			 $roleinfo['mark']=input('post.mark');
			 if(empty($roleinfo['name'])){
				$msg['roleaddstatus']=false;
				$msg['out']='角色名为空！';
				exit(json_encode($msg));
			 }
			 $roleinfo['ctime']=date("Y-m-d H:i:s");
			 Roots::insert_one_role($roleinfo);
			 $msg['roleaddstatus']=true;
			 $msg['out']='更新成功！';
			 exit(json_encode($msg));
		 }else{
			 return needlogined();
		 }
	 }
	 
	 public function role_update(){
		 if(islogined()){
			 if(!queryroot(array('角色管理'=>array('查看'=>1,"修改"=>1)))){
				 return redirect('/Admin/Index/Index');
			 }
			 if(request()->isPost()){
				 $alldata=input('post.');
				 if(empty($alldata)){
					$msg['roleupdatestatus']=false;
					$msg['out']='提交信息为空！';
					exit(json_encode($msg));
				 }
				 if(empty($alldata['root'])){
					 $rootinfo=null;
				 }else{
					 $rootinfo=unicode(json_encode($alldata['root']));
				 }
				 Roots::update_role_byid($alldata['id'],array('name'=>$alldata['name'],'mark'=>$alldata['mark'],'rootlist'=>$rootinfo));
				 $msg['roleupdatestatus']=true;
				 $msg['out']='更新成功';
				 exit(json_encode($msg));
			 }else{
				 $id=input('get.id');
				 if(empty($id)){
					 return redirect('/Admin/Root/Root_list');
				 }
				 $role=Roots::get_one_role($id);
				 //print_r($role);
				 $this->assign('role',$role);
				 return $this->fetch('/root/roleupdate');
			 }
		 }else{
			 return needlogined();
		 }
	 }
	 
	 public function role_del(){
		 if(islogined()){
			 if(!queryroot(array('角色管理'=>array('查看'=>1,"删除"=>1)))){
				 return redirect('/Admin/Index/Index');
			 }
			 $id=input('post.id');
			 if(empty($id)){
				 $msg['roledelstatus']=false;
				 $msg['out']='id为空';
				 exit(json_encode($msg));
			 }
			 $role=Roots::del_one_role($id);
			 //print_r($role);
			 if(!$role){
				 $msg['roledelstatus']=false;
				 $msg['out']='id不存在';
				 exit(json_encode($msg));
			 }
			 $msg['roledelstatus']=true;
			 $msg['out']='删除成功';
			 exit(json_encode($msg));
		 }else{
			 return needlogined();
		 }
	 }
	 
	 public static function test(){
		 $rootlist=json_decode(session('rootlist'),true);
		 foreach($rootlist as $key=>$value){
			 unset($rootlist[$key]['块级']);
		 }
		 //Roots::update_role_byid(2,array('rootlist'=>json_encode($rootlist)));
		 echo json_encode($rootlist,JSON_UNESCAPED_UNICODE);
	 }
	 
 }