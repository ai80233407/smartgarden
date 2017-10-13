<?php
namespace app\user\controller;
class Weather extends \think\Controller{
	public function Index(){
		$city=http_build_query(array('city'=>'北京'));
		$curl=curl_init('');
		//http://www.sojson.com/open/api/weather/json.shtml?city=哈尔滨
		//http://wthrcdn.etouch.cn/weather_mini?city=北京
		curl_setopt($curl,CURLOPT_URL,'wthrcdn.etouch.cn/weather_mini?city='.$city);
		curl_setopt($curl,CURLOPT_HEADER,0);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		/*
		curl_setopt_array($curl,[
			'CURLOPT_HEADER'=>0,
			'CURLOPT_RETURNTRANSFER'=>1
		]);
		**/
		$output=curl_exec($curl);
		//print_r(curl_getinfo($curl));
		curl_close($curl);
		header("Content-type:text/html;charset:utf-8");
		print_r(json_decode($output));
	}
}