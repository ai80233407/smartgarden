<?php
namespace app\user\controller;

use app\user\model\Weathers;
use Task\TimedTask;

class Info extends \think\Controller{
	
	protected function _initialize(){
		if(!islogined()){
			$request= \think\Request::instance();
			$arr=array('timed_task','start_task');
			if(in_array($request->action(),$arr)){
				//dump($request->module());
				//dump($request->controller());
			}else{
				return $this->redirect('/index.php/Index/Index/Login');
			}
			
		}
	}
	
	public function weather_info(){
		if(!queryroot(array('天气资讯'=>array('查看'=>1)))){
			 return redirect('/User/Index/Index');
		 }
		 $imweather=json_decode(Weathers::get_im_weather()->value,true);
		 //print_r($imweather);
		 $this->assign('imweather',$imweather);
		 //print_r(Weathers::update_weather('wthrcdn.etouch.cn/WeatherApi',array('city'=>'哈尔滨'),'xml'));
		 //print_r(Weathers::update_weather('wthrcdn.etouch.cn/weather_mini',array('city'=>'哈尔滨'),'json'));
		 return $this->fetch('/info/weather');
	}
	
	public function weather_update(){
		if(!queryroot(array('天气资讯'=>array('新增'=>1)))){
			 return redirect('/User/Index/Index');
		 }
		 $info=Weathers::update_weather('wthrcdn.etouch.cn/WeatherApi',array('city'=>'哈尔滨'),'xml');
		 exit(json_encode($info));
		 //print_r(Weathers::update_weather('wthrcdn.etouch.cn/weather_mini',array('city'=>'哈尔滨'),'json'));
	}
	
	public function task_info(){
		if(!queryroot(array('定时任务'=>array('查看'=>1)))){
			return redirect('/User/Index/Index');
		}
		$key=input('get.key');
		if(empty($key)){
			$key='';
		}
		$this->assign('key',$key);
		return $this->fetch('/task/index');
	}
	
	public function del_task($id=0){
		if($id<=0){
			
		}else{
			if(\app\user\model\Task_info::del_task($id)){
				return $this->success('删除成功！',url('User/Info/Task_info'));
			}
		}
		return $this->error('任务id不正确！',url('User/Info/Task_info'));
	}
	
	public function insert_task(){
		if(request()->isPost()){
			$task=input('post.task/a');
			if(!empty($task)){
				$task['counts']=0;
				$task['firststart']=0;
				$task['status']=1;
				$task['lasttime']=date("Y-m-d");
				$p=new TimedTask();
				if(!$p->create_task($task)){
					die(json_encode(array('error'=>'0','msg'=>'新建任务成功！')));
				}
			}
			die(json_encode(array('error'=>'1','msg'=>'新建任务失败！')));
		}else{
			return $this->fetch('task/add');
		}
	}
	
	public function change_task($id=0){
		if(request()->isPost()){
			$task=input('post.task/a');
			if(!empty($task)){
				$p=new TimedTask($task['id']);
				if(!$p->change_task($task)){
					die(json_encode(array('error'=>'0','msg'=>'修改任务成功！')));
				}
			}
			die(json_encode(array('error'=>'1','msg'=>'修改任务失败！')));
		}else{
			if($id==0){
				
			}else{
				$task=new TimedTask($id);
				$this->assign('task',$task->get_info_array());
				return $this->fetch('/task/change');
			}
		}
	}
	
	public function stop_task($id=0){
		if($id<=0){
			
		}else{
			$p=new TimedTask($id);
			if(!$p->stop_task($id)){
				return $this->success('暂停成功！',url('User/Info/Task_info'));
			}
		}
		return $this->error('暂停失败！',url('User/Info/Task_info'));
	}

	public function restart_task($id=0){
		if($id<=0){
			
		}else{
			$p=new TimedTask($id);
			if(!$p->restart_task($id)){
				return $this->success('重启成功！',url('User/Info/Task_info'));
			}
		}
		return $this->error('重启失败！',url('User/Info/Task_info'));
	}

	public function start_task($id=0){
		
		TimedTask::start_task($id);
		
	}
	
	public function timed_task($id=0){
		if($id<=0){
			
		}else{
			$t=new TimedTask($id);
			$t->run();
		}
	}
	
	function getRealIp()
	{
		$ip=false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
			for ($i = 0; $i < count($ips); $i++) {
			  if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
				$ip = $ips[$i];
				break;
			  }
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
		/*--------------------------------------------------------*/
		echo request()->ip().'<br>';
		echo $_SERVER["REMOTE_ADDR"].'<br>';
		$user_IP = isset($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
		$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
		echo $user_IP.'<br>';
		echo $this->getRealIp().'<br>';
		if (isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]))
		{
		  $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}
		elseif (isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]))
		{
		  $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}
		elseif (isset($HTTP_SERVER_VARS["REMOTE_ADDR"]))
		{
		  $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}
		elseif (getenv("HTTP_X_FORWARDED_FOR"))
		{
		  $ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv("HTTP_CLIENT_IP"))
		{
		  $ip = getenv("HTTP_CLIENT_IP");
		}
		elseif (getenv("REMOTE_ADDR"))
		{
		  $ip = getenv("REMOTE_ADDR");
		}
		else
		{
		  $ip = "Unknown";
		}
		echo $ip.'<br>';
		if(getenv('HTTP_CLIENT_IP')) {
		  $onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
		  $onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR')) {
		  $onlineip = getenv('REMOTE_ADDR');
		} else {
		  $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
		}
		echo $onlineip.'<br>';
	}
	
	public function test(){
		/*
		unset($_COOKIE['account']);
		unset($_COOKIE['password']);
		unset($_COOKIE['remeber']);
		*/
		//cookie(null,'think_');
		//echo $_COOKIE['think_account'];
		//echo cookie('account','','think_');
		//cookie('fack','you');
		//cookie('fack',null);
		//cookie(null,'think_');
		print_r($_COOKIE);
		
		//echo $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'];
		//echo date("h:i:sa");
		//echo $_SERVER['HTTP_USER_AGENT'];
		/*
		if($id==0){
			foreach(\app\user\model\Task_info::all_task() as $value){
				file_get_contents('http://127.0.0.1/User/Info/Test/'.$value->id);
			}
		}else{
			$task=new TimedTask($id);
			$task->run();
		}
		ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
		set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
		*/
		//echo dirname(__FILE__);
		//echo dirname(__DIR__);
		
		//file_get_contents('http://127.0.0.1/User/Info/Test');
		/* 
		//方法1：
		$ip = $_SERVER["REMOTE_ADDR"];
		echo $ip;
		 
		//方法2：
		$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
		$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
		echo $user_IP;
		 
		//方法3：
		function getRealIp()
		{
		  $ip=false;
		  if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		  }
		  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
			for ($i = 0; $i < count($ips); $i++) {
			  if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
				$ip = $ips[$i];
				break;
			  }
			}
		  }
		  return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
		}
		echo getRealIp();
		 
		//方法4：
		if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])
		{
		  $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}
		elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])
		{
		  $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}
		elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"])
		{
		  $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
		}
		elseif (getenv("HTTP_X_FORWARDED_FOR"))
		{
		  $ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv("HTTP_CLIENT_IP"))
		{
		  $ip = getenv("HTTP_CLIENT_IP");
		}
		elseif (getenv("REMOTE_ADDR"))
		{
		  $ip = getenv("REMOTE_ADDR");
		}
		else
		{
		  $ip = "Unknown";
		}
		echo $ip ;
		 
		//方法5：
		if(getenv('HTTP_CLIENT_IP')) {
		  $onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
		  $onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR')) {
		  $onlineip = getenv('REMOTE_ADDR');
		} else {
		  $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
		}
		echo $onlineip;
		*/
	}
}