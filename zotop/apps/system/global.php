<?php
//注册类库
zotop::register(array(
	'priv'				=> ZOTOP_PATH_APPS.DS.'system'.DS.'libraries'.DS.'priv.php',
	'rewrite'			=> ZOTOP_PATH_APPS.DS.'system'.DS.'libraries'.DS.'rewrite.php',
	'plupload'			=> ZOTOP_PATH_APPS.DS.'system'.DS.'libraries'.DS.'plupload.php',
	'system_api'		=> ZOTOP_PATH_APPS.DS.'system'.DS.'libraries'.DS.'api.php',
	'system_field'		=> ZOTOP_PATH_APPS.DS.'system'.DS.'libraries'.DS.'field.php',
	'admin_controller'	=> ZOTOP_PATH_APPS.DS.'system'.DS.'libraries'.DS.'admin_controller.php',
));


/**
 * 注册控件
 *
 * @param $attrs array 控件参数
 * @return string 控件代码
 */

form::field('date',array('system_field','date'));
form::field('datetime',array('system_field','datetime'));
form::field('image',array('system_field','image'));
form::field('images', 'system_field::images');
form::field('file',array('system_field','file'));
form::field('keywords',array('system_field','keywords'));
form::field('alias',array('system_field','alias'));
form::field('captcha',array('system_field','captcha'));
form::field('template',array('system_field','template'));
form::field('template_editor',array('system_field','template_editor'));
form::field('code',array('system_field','template_editor'));
form::field('icon',array('system_field','icon'));

/**
 * 初始化开始和全局消息
 *
 */
zotop::add('system.global.navbar','system_api::global_start');
zotop::add('system.global.msg','system_api::global_msg');



/**
 * 别名解析, 将别名路由到设定的uri
 *
 * @param $attrs array 控件参数
 * @return string 控件代码
 */
zotop::add('zotop.route','system_alias');

function system_alias()
{
	// 如果是app，则不进行别名解析
	$app = array_shift(explode('/', zotop::$uri));

	if ( !a($app) )
	{
		if ( $uri = alias(zotop::$uri) ) zotop::$uri = $uri;
	}

	unset($app);
}

/**
 * 别名的设置及判断
 *
 * @param array $a 别名
 * @param string $b 原始地址
 * @return mixed
 */
function alias($a, $b='')
{
	$alias = m('system.alias');

	$a = trim(strtolower($a));
	$b = trim(strtolower($b));

	if ( $a )
	{
		// 获取别名
		if ( $b === '' ) return $alias->field('source')->where('alias',$a)->getField();

		// 根据别名删除 alias('test', null)
		if ( $b === null ) 	return $alias->where('alias', $a)->delete();

		// 插入一个别名
		return $alias->insert(array('app' => ZOTOP_APP,'alias' => $a,'source'=> $b), true);
	}

	// 根据源删除
	if ( $b ) return $alias->where('source',$b)->delete();

	return false;
}
?>