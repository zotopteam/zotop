<?php
//注册类库
//zotop::register(array());

//system_start
zotop::add('system.start','test_system_start');
function test_system_start($start)
{
	$start['test'] = array(
		'text' => A('test.name'),
		'href' => u('test'),
		'icon' => A('test.url').'/app.png',
		'description' => A('test.description'),
		'allow' => priv::allow('test')
	);

	return $start;
}

//system_globalnavbar
zotop::add('system.globalnavbar','test_system_globalnavbar');
function test_system_globalnavbar($nav)
{
	$nav['test'] = array(
		'text' => t('测试'),
		'href' => u('test'),
		'icon' => A('test.url').'/app.png',
		'description' => A('test.description'),
		'allow' => priv::allow('test'),
		'current' => (ZOTOP_APP == 'test')
	);

	return $nav;
}

?>