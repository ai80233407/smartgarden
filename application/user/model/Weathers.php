<?php
namespace app\user\model;

use think\Model;

class Weathers extends Model{
	public static function update_weather($url,$cityarr,$type){
		
		$city=http_build_query($cityarr);
		$curl=curl_init('');
		//http://www.sojson.com/open/api/weather/json.shtml?city=哈尔滨
		//http://wthrcdn.etouch.cn/weather_mini?city=北京//json
		//http://wthrcdn.etouch.cn/WeatherApi?city=北京//xml
		curl_setopt($curl,CURLOPT_URL,$url.'?'.$city);
		curl_setopt($curl,CURLOPT_HEADER,0);
		//curl_setopt($curl,CURLOPT_HTTPHEADER,array('Accept-Chartset:utf-8;q=0.7,*;q=0.3'));
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_ENCODING,'gzip,deflate');
		/*
		curl_setopt_array($curl,[
			'CURLOPT_HEADER'=>0,
			'CURLOPT_RETURNTRANSFER'=>1
		]);
		*/
		$output=curl_exec($curl);
		//print_r(curl_getinfo($curl));
		if(curl_errno($curl)){
			$arr=array('status'=>false,'out'=>'访问错误 -> '.curl_error($curl));
		}else{
			switch($type){
				case 'xml':
				case 'XML':
					
					//echo $output;
					$xml=simplexml_load_string($output);
					//echo unicode(json_encode($xml));
					//echo iconv('USC-2','UTF-8',pack('H4','u54c8'));
					//echo Unicode_decode(json_encode($xml));//
					Weathers::insert(array('value'=>unicode(json_encode($xml)),'time'=>date("Y-m-d H:i:s"),'source'=>$url));
					$arr['status']=true;
					$arr['out']='获取成功';
					//echo json_encode($xml,JSON_UNESCAPED_UNICODE);
				break;
				case 'json':
				case 'JSON':
					Weathers::insert(array('value'=>$output,'time'=>date("Y-m-d H:i:s"),'source'=>$url));
					$arr['status']=true;
					$arr['out']='获取成功';
				break;
				default :
					$arr=array('status'=>false,'out'=>'类型错误');
			}
		}
		curl_close($curl);
		//header("Content-type:text/html;charset:gbk");
		
		//echo $output;
		return $arr;
	}
	
	public static function get_im_weather(){
		return Weathers::where('1=1')->order('time desc')->find();
	}
}