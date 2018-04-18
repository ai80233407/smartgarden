<?php
namespace Task;

use app\user\model\Task_info;
use app\user\model\Weathers;

class TimedTask{
	private $id=0;
	private $name='';
	private $start='';
	private $endtime='';
	private $status=0;
	private $counts=0;
	private $type='';
	private $mark='';
	private $lasttime='';
	private $frequency=3600;
	private $firststart=0;
	public function __construct($id=0){
		$onetask=Task_info::one_task($id);
		if(!empty($onetask)){
			$this->id=$onetask['id'];
			$this->name=$onetask['name'];
			$this->start=strtotime($onetask['start']);
			$this->endtime=strtotime($onetask['endtime']);
			$this->status=$onetask['status'];
			$this->type=$onetask['type'];
			$this->mark=$onetask['mark'];
			$this->counts=$onetask['counts'];
			$this->lasttime=$onetask['lasttime'];
			$this->firststart=$onetask['firststart'];
			switch($onetask['frequency']){
				case 1:
					$this->frequency=60;
				break;
				case 2:
					$this->frequency=60*60;
				break;
				case 3:
					$this->frequency=60*60*24;
				break;
				case 4:
					$this->frequency=60*60*24*30;
				break;
				case 5:
					$this->frequency=60*60*24*30*12;
				break;
				default:
					$this->frequency=60*60*24;
			}
		}
	}
	
	public function update_task($id=0){
		if($id>0){
			$this->id=$id;
		}
		$onetask=Task_info::one_task($this->id);
		if(!empty($onetask)){
			$this->name=$onetask['name'];
			$this->start=strtotime($onetask['start']);
			$this->endtime=strtotime($onetask['endtime']);
			$this->status=$onetask['status'];
			$this->type=$onetask['type'];
			$this->mark=$onetask['mark'];
			$this->counts=$onetask['counts'];
			$this->lasttime=strtotime($onetask['lasttime']);
			$this->firststart=$onetask['firststart'];
			switch($onetask['frequency']){
				case 1:
					$this->frequency=60;
				break;
				case 2:
					$this->frequency=60*60;
				break;
				case 3:
					$this->frequency=60*60*24;
				break;
				case 4:
					$this->frequency=60*60*24*30;
				break;
				case 5:
					$this->frequency=60*60*24*30*12;
				break;
				default:
					$this->frequency=60*60*24;
			}
			return 0;
		}else{
			return 1;
		}
	}
	
	public function create_task($onetask=null){
		if(!empty($onetask)){
			$this->name=$onetask['name'];
			$this->start=strtotime($onetask['start']);
			$this->endtime=strtotime($onetask['endtime']);
			$this->status=$onetask['status'];
			$this->type=$onetask['type'];
			$this->mark=$onetask['mark'];
			$this->counts=$onetask['counts'];
			$this->lasttime=strtotime($onetask['lasttime']);
			$this->firststart=$onetask['firststart'];
			switch($onetask['frequency']){
				case 1:
					$this->frequency=60;
				break;
				case 2:
					$this->frequency=60*60;
				break;
				case 3:
					$this->frequency=60*60*24;
				break;
				case 4:
					$this->frequency=60*60*24*30;
				break;
				case 5:
					$this->frequency=60*60*24*30*12;
				break;
				default:
					$this->frequency=60*60*24;
			}
		}else{
			return 1;
		}
		Task_info::insert_task($onetask);
		$this->id=Task_info::task_id($this->name);
		return 0;
	}

	public function run(){
		Task_info::task_lasttime($this->id);
		if(!$this->id)
			return 1;
		if($this->endtime<strtotime(date("Y-m-d H:i:s")))
			return 2;
		if($this->start>strtotime(date("Y-m-d H:i:s"))){
			sleep(30);
			$ch4 = curl_init("http://{$_SERVER['SERVER_ADDR']}:{$_SERVER['SERVER_PORT']}/User/Info/Start_task?id=".$this->id);
			curl_exec($ch4);
			curl_close($ch4);
			return 3;
		}
		if($this->status){
			sleep(30);
			$ch5 = curl_init("http://{$_SERVER['SERVER_ADDR']}:{$_SERVER['SERVER_PORT']}/User/Info/Start_task?id=".$this->id);
			curl_exec($ch5);
			curl_close($ch5);
			return 4;
		}
		//ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
		//set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
		Task_info::task_count($this->id);
		switch($this->type){
			case 'coding':
				$post_data=http_build_query(array('account'=>'ai80233407@163.com','password'=>sha1('201314'),'remember_me'=>false));
				$url='https://coding.net/api/v2/account/login/';
				$ch = curl_init();  
				curl_setopt ($ch, CURLOPT_URL, $url);  
				curl_setopt ($ch, CURLOPT_POST, 1);  
				if($post_data != ''){  
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				}else{
					
				}
				$cookie_jar = tempnam('.\temp','cookie');
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
				//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36'); // 模拟用户使用的浏览器
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //设定返回的数据是否自动显示
				//curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
				curl_setopt($ch, CURLOPT_HEADER, false);
				$file_contents = curl_exec($ch);
				curl_close($ch);
				$res=json_decode($file_contents,true);
				if($res['code']!='0')
					
				Weathers::update_weather('wthrcdn.etouch.cn/WeatherApi',array('city'=>'哈尔滨'),'xml');
				$imweather=json_decode(Weathers::get_im_weather()->value,true);
				$envlist=array('aqi'=>'污染指数','pm25'=>'PM 2.5','suggest'=>'舒适度','quality'=>'空气质量','o3'=>'臭氧','co'=>'一氧化碳','pm10'=>'PM 10','no2'=>'二氧化氮','so2'=>'二氧化硫','time'=>'更新时间','MajorPollutants'=>'');
				$str="#连击 100 天# - 第{$this->counts}天\n\r".$imweather['city'].'-'.date("Y-m-d").'<br>白天 :sunny: '.$imweather['forecast']['weather'][0]['day']['type'].
					'  '.$imweather['forecast']['weather'][0]['day']['fengxiang'].'<br>夜间 :moon: '.$imweather['forecast']['weather'][0]['night']['type'].
					'  '.$imweather['forecast']['weather'][0]['night']['fengxiang'].'<br> 温度 ：'.substr($imweather['forecast']['weather'][0]['high'],6).' / '.
					substr($imweather['forecast']['weather'][0]['low'],6).'<br>湿度 ：'.$imweather['shidu'].'<br>';
				foreach($imweather['environment'] as $key=>$value){
					if($key=='time')
						continue;
					if(!is_array($value)){
						$str.=$envlist[$key].' ：'.$value.'<br>';
					}			
				}
				//生活指数信息
				/*
				foreach($imweather['zhishus']['zhishu'] as $key=>$value){
					$str.="\n\r".$value['name']." -> ".$value['value']."\n\r```".$value['detail'].'```';
				}
				*/
				$send_data=http_build_query(array('content'=>$str));
				$send_url='https://coding.net/api/tweet/';
				$ch2 = curl_init();  
				curl_setopt ($ch2, CURLOPT_URL, $send_url);  
				curl_setopt ($ch2, CURLOPT_POST, 1);  
				if($send_data != ''){  
					curl_setopt($ch2, CURLOPT_POSTFIELDS, $send_data);
				}
				curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_jar);
				curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
				//curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
				curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36'); // 模拟用户使用的浏览器
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
				curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1); //设定返回的数据是否自动显示
				//curl_setopt ($ch2, CURLOPT_CONNECTTIMEOUT, $timeout);  
				curl_setopt($ch2, CURLOPT_HEADER, false);
				$file_contents = curl_exec($ch2);
				curl_close($ch2);
				$res=json_decode($file_contents,true);
				sleep($this->frequency);
				$ch3 = curl_init("http://{$_SERVER['SERVER_ADDR']}:{$_SERVER['SERVER_PORT']}/User/Info/Start_task?id=".$this->id);
				curl_exec($ch3);
				curl_close($ch3);
				
				//file_get_contents('');				
			break;
			case 'bilibili':
			break;
			default:
				return 5;
		}
		return 0;
	}
	
	public function change_task($task){
		if(!$this->id){
			return 1;
		}
		if(!$this->status){
			return 2;
		}
		Task_info::update_task($task['id'],$task);
		return 0;
	}
	
	public function restart_task(){
		if($this->id==0){
			return 1;
		}
		if($this->status==0){
			return 2;
		}
		if($this->endtime<strtotime(date("Y-m-d H:i:s"))){
			return 3;
		}
		if(!$this->firststart){
			Task_info::firststart_down($this->id);
			$this->start_task($this->id);
		}
		Task_info::task_status_up($this->id);
		return 0;
	}
	
	public function stop_task(){
		if($this->id==0){
			return 1;
		}
		if($this->status!=0){
			return 2;
		}
		Task_info::task_status_down($this->id);
		return 0;
	}
	
	public static function start_task($id){
	$url="http://{$_SERVER['SERVER_ADDR']}:{$_SERVER['SERVER_PORT']}/index.php/User/Info/Timed_task?id=".$id;
		$scheme = parse_url($url,PHP_URL_SCHEME);
		if($scheme == 'https'){
			$host = 'ssl://'.$host;  
		}
		$host = parse_url($url,PHP_URL_HOST);  
		$port = parse_url($url,PHP_URL_PORT);  
		$port = $port ? $port : 80; 
		$path = parse_url($url,PHP_URL_PATH);  
		$query = parse_url($url,PHP_URL_QUERY);  
		if($query)
			$path .= '?'.$query;
		$fp = fsockopen($host,$port,$error_code,$error_msg,1);  
		if(!$fp){  
			return json_encode(array('error_code' => $error_code,'error_msg' => $error_msg));  
		}
		else{  
			stream_set_blocking($fp,true);//开启了手册上说的非阻塞模式  
			stream_set_timeout($fp,1);//设置超时  
			$header = "GET $path HTTP/1.1\r\n";  
			$header.="Host: $host\r\n";  
			$header.="Connection: close\r\n\r\n";//长连接关闭  
			fwrite($fp, $header);
			//添加同步等待请去掉注释
			/*
			$data='';
			while(!feof($fp)){
				$data.=fgets($fp,1024);
			}
			echo $data;
			*/
			usleep(1000); // 这一句也是关键，如果没有这延时，可能在nginx服务器上就无法执行成功  
			fclose($fp);   
		}
	}
	
	public function get_info_array(){
		return Task_info::one_task($this->id);;
	}
}