<?php
return array(
	'mine_info' => array(
		'text'=>t('我的资料'),
		'href'=>u('system/mine'),
		'icon'=>A('system.url').'/icons/mine.png',
		'description'=>t('编辑我的姓名等个人信息')
	),
	'mine_password' => array(
		'text'=>t('我的密码'),
		'href'=>u('system/mine'),
		'icon'=>A('system.url').'/icons/password.png',
		'description'=>t('修改我的密码')
	),			
	'system_app' => array(
		'text'=>t('应用管理'),
		'href'=>u('system/app'),
		'icon'=>A('system.url').'/icons/app.png',
		'description'=>t('管理网站应用，扩展或者删除功能'),
		'allow' => priv::allow('system','app')
	),
	'system_attachment' => array(
		'text'=>t('附件管理'),
		'href'=>u('system/attachment'),
		'icon'=>A('system.url').'/icons/attachment.png',
		'description'=>t('管理网站上传的附件'),
		'allow' => priv::allow('system','attachment')
	),
	'system_theme' => array(
		'text'=>t('主题和模板'),
		'href'=>u('system/theme'),
		'icon'=>A('system.url').'/icons/theme.png',
		'description'=>t('网站主题和模板管理'),
		'allow' => priv::allow('system','theme')
	),	
	'system_config' => array(
		'text'=>t('全局设置'),
		'href'=>u('system/config'),
		'icon'=>A('system.url').'/icons/config.png',
		'description'=>t('网站、上传、水印、邮件、安全等设置'),
		'allow' => priv::allow('system','config')
	),
	'system_admin' => array(
		'text'=>t('管理员管理'),
		'href'=>u('system/administrator'),
		'icon'=>A('system.url').'/icons/administrator.png',
		'description'=>t('系统的管理员管理'),
		'allow' => priv::allow('system','administrator')
	),
	'system_role' => array(
		'text'=>t('角色管理'),
		'href'=>u('system/role'),
		'icon'=>A('system.url').'/icons/role.png',
		'description'=>t('系统的角色管理'),
		'allow' => priv::allow('system','role')
	),
	'system_priv' => array(
		'text'=>t('权限管理'),
		'href'=>u('system/priv'),
		'icon'=>A('system.url').'/icons/priv.png',
		'description'=>t('系统的的权限系统管理'),
		'allow' => priv::allow('system','priv')
	),
	'system_ipbanned' => array(
		'text'=>t('ip禁止'),
		'href'=>u('system/ipbanned'),
		'icon'=>A('system.url').'/icons/ipbanned.png',
		'description'=>t('禁止某些ip访问'),
		'allow' => priv::allow('system','ipbanned')
	),
	'system_badword' => array(
		'text'=>t('敏感词管理'),
		'href'=>u('system/badword'),
		'icon'=>A('system.url').'/icons/badword.png',
		'description'=>t('过滤用户输入的某些敏感词语'),
		'allow' => priv::allow('system','badword')
	),
	'system_log' => array(
		'text'=>t('系统操作日志'),
		'href'=>u('system/log'),
		'icon'=>A('system.url').'/icons/log.png',
		'description'=>t('查看系统操作日志'),
		'allow' => priv::allow('system','log')
	),
	'system_server' => array(
		'text'=>t('服务器信息'),
		'href'=>u('system/info/index'),
		'icon'=>A('system.url').'/icons/info.png',
		'description'=>t('查看系统及服务器信息')
	),
	'system_about'	=> array(
		'text'=>t('关于zotop'),
		'href'=>u('system/info/about'),
		'icon'=>A('system.url').'/icons/zotop.png',
		'description'=>t('关于逐涛内容管理系统')
	)
);
?>