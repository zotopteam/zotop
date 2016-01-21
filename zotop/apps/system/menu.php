<?php
return array(
	'system_app' => array(
		'text'   => t('应用管理'),
		'href'   => u('system/app'),
		'icon'   => 'fa fa-puzzle-piece',
		'active' => request::is('system/app')
	),
	'system_config' => array(
		'text'   => t('全局设置'),
		'href'   => u('system/config'),
		'icon'   => 'fa fa-gears',
		'active' => request::is('system/config')
	),
	'system_theme' => array(
		'text'   => t('主题和模板'),
		'href'   => u('system/theme'),
		'icon'   => 'fa fa-magic',
		'active' => request::is('system/theme')
	),
	'system_ipbanned' => array(
		'text'   => t('IP禁止'),
		'href'   => u('system/ipbanned'),
		'icon'   => 'fa fa-ban',
		'active' => request::is('system/ipbanned')
	),
	'system_badword' => array(
		'text'   => t('敏感词管理'),
		'href'   => u('system/badword'),
		'icon'   => 'fa fa-filter',
		'active' => request::is('system/badword')
	),
	'system_administrator' => array(
		'text'   => t('管理员管理'),
		'href'   => u('system/administrator'),
		'icon'   => 'fa fa-users',
		'active' => request::is('system/administrator')
	),
	'system_role' => array(
		'text'   => t('角色管理'),
		'href'   => u('system/role'),
		'icon'   => 'fa fa-user',
		'active' => request::is('system/role')
	),
	'system_priv' => array(
		'text'   => t('权限管理'),
		'href'   => u('system/priv'),
		'icon'   => 'fa fa-sitemap',
		'active' => request::is('system/priv')
	),
	'system_log' => array(
		'text'   => t('系统操作日志'),
		'href'   => u('system/log'),
		'icon'   => 'fa fa-flag',
		'active' => request::is('system/log')
	),
	'system_check' => array(
		'text'   => t('系统检测'),
		'href'   => u('system/check'),
		'icon'   => 'fa fa-check',
		'active' => request::is('system/check')
	),
	'system_zotop' => array(
		'text'   => t('关于zotop'),
		'href'   => u('system/zotop'),
		'icon'   => 'fa fa-info-circle',
		'active' => request::is('system/zotop')
	),											
);
?>