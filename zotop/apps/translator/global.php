<?php
//注册类库
//zotop::register(array());

//system_start
/*
zotop::add('system.start','translator_system_start');

function translator_system_start($start)
{
	$start['translator'] = array(
		'text' => A('translator.name'),
		'href' => u('translator/config'),
		'icon' => A('translator.url').'/app.png',
		'description' => A('translator.description'),
		'allow' => priv::allow('translator')
	);

	return $start;
}
*/

/**
 * 别名解析
 *
 * @param $attrs array 控件参数
 * @return string 控件代码
 */
zotop::after('zotop.boot', array('application','finduri'), 'translator_alias');

function translator_alias()
{
	if ( zotop::$uri == 'system/alias/get' )
	{
		zotop::$uri = 'translator/alias/get';
	}
}

?>