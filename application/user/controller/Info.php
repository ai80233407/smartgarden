<?php
namespace app\user\controller;

use app\user\model\Weathers;

class Info extends \think\Controller{
	
	public function weather_info(){
		if(islogined()){
			if(!queryroot(array('天气资讯'=>array('查看'=>1)))){
				 return redirect('/User/Index/Index');
			 }
			 $imweather=json_decode(Weathers::get_im_weather()->value,true);
			 //print_r($imweather);
			 $this->assign('imweather',$imweather);
			 //print_r(Weathers::update_weather('wthrcdn.etouch.cn/WeatherApi',array('city'=>'哈尔滨'),'xml'));
			 //print_r(Weathers::update_weather('wthrcdn.etouch.cn/weather_mini',array('city'=>'哈尔滨'),'json'));
			 return $this->fetch('/info/weather');
		}else{
			return needlogined();
		}
	}
	
	public function weather_update(){
		if(islogined()){
			if(!queryroot(array('天气资讯'=>array('新增'=>1)))){
				 return redirect('/User/Index/Index');
			 }
			 $info=Weathers::update_weather('wthrcdn.etouch.cn/WeatherApi',array('city'=>'哈尔滨'),'xml');
			 exit(json_encode($info));
			 //print_r(Weathers::update_weather('wthrcdn.etouch.cn/weather_mini',array('city'=>'哈尔滨'),'json'));
		}else{
			return needlogined();
		}
	}
}