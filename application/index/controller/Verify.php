<?php
/*
$msg['status'] = false; // 是否需要重新请求验证码，默认否
$msg['Err'] = 1004;     // 错误编码
$msg['out'] ;           // 编码对应输出
*/
namespace app\index\controller;

use Mine\Slide;     // 引入 Slide 类

class Verify extends \think\Controller{

    /**
    * 初始获取 验证码
    */
    public function init(){
        echo Slide::instance();
    }
    /**
    * 获取验证码的html
    * @param GET   : (source)
    * @echo Html | String
    */
    public function captchar(){
        echo Slide::instance(1);
    }

    /**
    * 验证码，校验
    * @param POST  : (int)x_value 横坐标，用户滑动结果
    * @echo String : {"Err":"","out":""}
    */
    public function check(){
        // 参数过滤
        Slide::instance(2);
    }
}