<?php
return array(

	// 禁止使用的用户名和昵称，一行一个，可以使用通配符*和?
	'register_banned'	=>	'admin*',

	// 新会员注册邮件验证
	'register_validmail'			=> true,
	'register_validmail_title'		=> t('{sitename}：欢迎注册成为我们的会员，请验证您的邮箱'),
	'register_validmail_content'	=> t('<h2>欢迎您注册成为{sitename}用户</h2><p>请点击下面链接进行认证：</p><p>{click}</p> <p>或者将网址复制到浏览器：</p><p>{url}</p>'),
	'register_validmail_expire'		=> 24,

	// 登录设置
	'login_captcha'		=> 0,
	'login_point'		=> 1,

	// 找回密码设置，默认有效期单位小时
	'getpasswordmail_title'		=> '{sitename}：密码找回验证码',
	'getpasswordmail_content'	=> '本次请求的邮件验证码为：{code} (为了保障您帐号的安全性，请在{expire}小时内完成验证)',
	'getpasswordmail_expire'	=> 1,
);
?>