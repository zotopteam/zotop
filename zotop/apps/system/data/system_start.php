<?php
return array(
	'mine_info' => array(
		'text'        => '我的资料',
		'href'        => 'system/mine',
		'icon'        => '/icons/mine.png',
		'description' => '编辑我的姓名等个人信息'
	),
	'mine_password' => array(
		'text'        => '我的密码',
		'href'        => 'system/mine',
		'icon'        => '/icons/password.png',
		'description' => '修改我的密码'
	),			
	'system_app' => array(
		'text'        => '应用管理',
		'href'        => 'system/app',
		'icon'        => '/icons/app.png',
		'description' => '管理网站应用，扩展或者删除功能',
		'allow'       => 'system/app'
	),
	'system_attachment' => array(
		'text'        => '附件管理',
		'href'        => 'system/attachment',
		'icon'        => '/icons/attachment.png',
		'description' => '管理网站上传的附件',
		'allow'       => 'system/attachment'
	),
	'system_theme' => array(
		'text'        => '主题和模板',
		'href'        => 'system/theme',
		'icon'        => '/icons/theme.png',
		'description' => '网站主题和模板管理',
		'allow'       => 'system/theme'
	),	
	'system_config' => array(
		'text'        => '系统设置',
		'href'        => 'system/config',
		'icon'        => '/icons/config.png',
		'description' => '上传、水印、邮件、安全等设置',
		'allow'       => 'system/config'
	),
	'system_admin' => array(
		'text'        => '管理员管理',
		'href'        => 'system/administrator',
		'icon'        => '/icons/administrator.png',
		'description' => '系统的管理员管理',
		'allow'       => 'system/administrator'
	),
	'system_role' => array(
		'text'        => '角色管理',
		'href'        => 'system/role',
		'icon'        => '/icons/role.png',
		'description' => '系统的角色管理',
		'allow'       => 'system/role'
	),
	'system_priv' => array(
		'text'        => '权限管理',
		'href'        => 'system/priv',
		'icon'        => '/icons/priv.png',
		'description' => '系统的的权限系统管理',
		'allow'       => 'system/priv'
	),
	'system_ipbanned' => array(
		'text'        => 'ip禁止',
		'href'        => 'system/ipbanned',
		'icon'        => '/icons/ipbanned.png',
		'description' => '禁止某些ip访问',
		'allow'       => 'system/ipbanned'
	),
	'system_badword' => array(
		'text'        => '敏感词管理',
		'href'        => 'system/badword',
		'icon'        => '/icons/badword.png',
		'description' => '过滤用户输入的某些敏感词语',
		'allow'       => 'system/badword'
	),
	'system_log' => array(
		'text'        => '系统操作日志',
		'href'        => 'system/log',
		'icon'        => '/icons/log.png',
		'description' => '查看系统操作日志',
		'allow'       => 'system/log'
	),
	'system_server' => array(
		'text'        => '服务器信息',
		'href'        => 'system/check',
		'icon'        => '/icons/info.png',
		'description' => '服务器信息及文件和目录权限检测'
	),
	'system_about'	=> array(
		'text'        => '关于zotop',
		'href'        => 'system/zotop',
		'icon'        => '/icons/zotop.png',
		'description' => '关于逐涛内容管理系统'
	)
);
?>