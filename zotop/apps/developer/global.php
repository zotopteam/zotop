<?php
// 定义源目录
define('DEVELOPER_SOURCE_PATH', ZOTOP_PATH_APPS.DS.'developer'.DS.'source');

//注册类库
zotop::register(array(
	'developer'		=>	ZOTOP_PATH_APPS.DS.'developer'.DS.'libraries'.DS.'developer.php',
));

//system_start
zotop::add('global.start','developer_global_start');

function developer_global_start($start)
{
	$start = arr::before($start,'system_info','developer',array(
		'text'        =>	A('developer.name'),
		'href'        =>	U('developer'),
		'icon'        =>	A('developer.url').'/app.png',
		'description' =>	A('developer.description')
	));

	return $start;
}

//system_globalnavbar
zotop::add('global.navbar','developer_global_navbar');

function developer_global_navbar($nav)
{
	$nav['developer'] = array(
		'text'        => A('developer.name'),
		'href'        => U('developer'),
		'icon'        => A('developer.url').'/app.png',
		'description' => A('developer.description'),
		'allow'       => priv::allow('developer'),
		'active'      => (ZOTOP_APP == 'developer')
	);

	return $nav;
}
?>