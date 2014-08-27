<?php
return array(

	// 禁止使用的用户名和昵称，一行一个，可以使用通配符*和?
	'register_banned'	=>	'admin*',

	// 新会员注册邮件验证
	'register_validmail'			=> true,
	'register_validmail_title'		=> t('请激活您的{site}账户'),
	'register_validmail_content'	=> t('欢迎您注册成为{site}用户，请点击下面链接进行认证：<br/>{click} <br/>或者将网址复制到浏览器：<br/>{url}'),
	'register_validmail_expire'		=> 24,

	// 登录设置
	'login_captcha'		=> 0,
	'login_point'		=> 1,

	// 找回密码设置，默认有效期单位小时
	'getpasswordmail_expire'	=> 1,
	'getpasswordmail_title'		=> '{site} 密码找回',
	'getpasswordmail_content'	=> '本次请求的邮件验证码为：{code} (为了保障您帐号的安全性，请在1小时内完成验证)',
);
?>