<?php
namespace Email;

use Email\Lib\PHPMailer;
use Email\Lib\SMTP;
use Email\Lib\Exception;

class SendEmail{
	// 引入PHPMailer的核心文件
	
	private $mail;
	public function __construct(){
		// 实例化PHPMailer核心类
		$this->mail = new PHPMailer();
		// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
		$this->mail->SMTPDebug = 0;
		// 使用smtp鉴权方式发送邮件
		$this->mail->isSMTP();
		// smtp需要鉴权 这个必须是true
		$this->mail->SMTPAuth = true;
		// 链接qq域名邮箱的服务器地址
		$this->mail->Host = 'smtp.163.com';
		// 设置使用ssl加密方式登录鉴权
		$this->mail->SMTPSecure = 'ssl';
		// 设置ssl连接smtp服务器的远程服务器端口号465
		$this->mail->Port = 465;
		// 设置发送的邮件的编码
		$this->mail->CharSet = 'UTF-8';
		// smtp登录的账号 QQ邮箱即可
		$this->mail->Username = 'hdsmartgarden@163.com';
		// smtp登录的密码 使用生成的授权码
		$this->mail->Password = 'ai201314';
		// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
		//$this->mail->FromName = '发件人昵称';
		// 设置发件人邮箱地址 同登录账号
		//$this->mail->From = 'hdsmartgarden@163.com';
		$this->mail->SetFrom ( 'hdsmartgarden@163.com', '智能花园系统平台' );
		// 邮件正文是否为html编码 注意此处是一个方法
		$this->mail->isHTML(true);
		// 设置收件人邮箱地址
		//$this->mail->addAddress('930197464@qq.com');
		// 添加多个收件人 则多次调用方法即可
		//$this->mail->addAddress('ai80233407@163.com');
		// 添加该邮件的主题
		//$this->mail->Subject = '智能花园系统平台重置密码的邮件验证码';
		// 添加邮件正文
		
		// 为该邮件添加附件
		//$this->mail->addAttachment('./example.pdf');
		// 发送邮件 返回状态
	}
	
	public function send($info){
		$this->mail->addAddress($info['add'],$info['nick']);
		$this->mail->Subject = '智能花园系统平台邮件验证码';
		$this->mail->Body = "尊敬的用户<strong>{$info['nick']}</strong>，您好！您的验证码为 <strong>{$info['number']}</strong> 。如果您没有使用过密码重置功能，请忽略此封邮件。<a href=\"http://www.hdgarden.cn\">智能花园系统平台</a>，祝您生活愉快！";
		return $this->mail->send();
	}
}