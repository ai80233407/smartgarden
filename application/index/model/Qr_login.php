<?php
namespace app\index\model;

use think\Model;

class Qr_login extends Model{
	
	public static function insert_qrcode($arr){
		return Qr_login::insert($arr);
	}
	
	public static function has_qrcode($keyword){
		return empty(Qr_login::where("keyword='{$keyword}'")->find())?0:1;
	}
	
	public static function timeout_qrcode($keyword){
		$qr_time=Qr_login::where("keyword='{$keyword}'")->field('time')->find()['time'];
		return strtotime(date("Y-m-d H:i:s"))-strtotime($qr_time)<120 ? 0:1;
	}
	
	public static function safety_qrcode($keyword){
		$status=Qr_login::where("keyword='{$keyword}'")->field('status')->find()['status'];
		return $status?0:1;
	}
	
	public static function get_status($keyword){
		return Qr_login::where("keyword='{$keyword}'")->field('status')->find()['status'];
	}
	
	public static function update_qrcode($keyword,$arr){
		return Qr_login::where("keyword='{$keyword}'")->update($arr)?1:0;
	}
	
	public static function del_qrcode($keyword){
		return Qr_login::where("keyword='{$keyword}'")->delete();
	}
}