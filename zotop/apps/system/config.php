<?php
return array(
	// 站点设置
	'site_name' 		=> t('站点名称'),
	'site_url' 			=> '',
	'site_theme' 		=> 'default',
	'site_title' 		=> '',
	'site_keywords'		=> '',
	'site_description'	=> '',
	'site_closed'		=> 0,
	'site_closedreason'	=> '',
	

	// 上传参数
	'attachment_image_exts' => 'jpg,jpeg,gif,bmp,png',
	'attachment_image_size' => '20', //单位MB

	'attachment_file_exts' => 'doc,docx,xls,xlsx,ppt,pptx,pps,pdf,txt,rar,zip',
	'attachment_file_size' => '20', //单位MB

	'attachment_video_exts' => 'flv,swf,mp4,mpeg,rm,rmvb',
	'attachment_video_size' => '20', //单位MB

	'attachment_audio_exts' => 'mp3',
	'attachment_audio_size' => '20', //单位MB

	'upload_dir' => '[YYYY]/[MM]', //存储目录

	// 图片最大设置
	'image_resize' => '1',
	'image_width' => '980',
	'image_height' => '600',
	'image_quality' => '95',

	// 水印设置
	'watermark' => '1',
	'watermark_width' => '300',
	'watermark_height' => '200',
	'watermark_opacity' => '90',
	'watermark_image' => 'watermark.png',
	'watermark_position' => 'bottom right',
	'watermark_quality' => '90',

	// 邮件设置，默认邮件功能为false，后台填写参数并验证通过后为true
	'mail'=>true,
	'mail_mailer' => '2',
	'mail_from' => 'hankx_chen@163.com',
	'mail_sign' => 'zotopcms',
	'mail_delimiter' => '1',
	'mail_smtp_host' => 'smtp.163.com',
	'mail_smtp_port' => '25',
	'mail_smtp_auth' => '1',
	'mail_smtp_username' => 'hankx_chen',
	'mail_smtp_password' => 'chanlaye',

	// 本地设置
	'locale_language' => 'zh-cn',
	'locale_timezone' => '8',
	'locale_date' => 'Y-m-d',
	'locale_time' => 'H:i',

	// url设置
	'url_model'	=>	'pathinfo', //0 : 默认模式[pathinfo], 1 : 兼容模式[normal], 2 : 重写模式[rewrite]
	'url_suffix'	=>	'',
	'url_var'		=>	'r', //兼容模式参数名

	// 缓存设置
	'cache_driver' => 'file',
	'cache_expire' => '3000',
	'cache_memcache' => '127.0.0.1:11211',

	//安全设置
	'log' => 1,
	'log_expire' => 7,

	// 登陆设置
	'login_captcha' => false,
	'login_maxfailed' => 10,
	'login_locktime' => 30,

	// 安全码
	'safekey'=>'R82FPjR4SEEmQ7dck6Wk0PHeCr9pRFn2'
);
?>