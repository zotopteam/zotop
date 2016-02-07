<?php
// 定义源目录
define('DEVELOPER_SOURCE_PATH', ZOTOP_PATH_APPS.DS.'developer'.DS.'source');

//注册类库
zotop::register(array(
	'developer'		=>	ZOTOP_PATH_APPS.DS.'developer'.DS.'libraries'.DS.'developer.php',
));

//system_start
zotop::add('system.start','developer_system_start');
function developer_system_start($start)
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
zotop::add('system.globalnavbar','developer_globalnavbar');
function developer_globalnavbar($nav)
{
	$nav['developer'] = array(
		'text'        => A('developer.name'),
		'href'        => U('developer'),
		'icon'        => A('developer.url').'/app.png',
		'description' => A('developer.description'),
		'allow'       => priv::allow('developer'),
		'current'     => (ZOTOP_APP == 'developer')
	);

	return $nav;
}

/**
 * 页面导航
 * 
 * @param  string $table 表名称
 * @return array
 */
function developer_schema_navbar($table)
{
	return zotop::filter('developer.schema.navbar',array(
		'index' => array('text'=>t('表结构'),'href'=>U('developer/schema/index/'.$table),'class'=>''),
		'show'  => array('text'=>t('导出'),'href'=>U('developer/schema/show/'.$table),'class'=>'')
	));
}
?>