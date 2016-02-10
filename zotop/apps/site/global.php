<?php
/*
* 站点 全局文件
*
* @package		site
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
	'site_api'        => A('site.path') . DS . 'libraries' . DS . 'api.php',
	'site_controller' => A('site.path') . DS . 'libraries' . DS . 'site_controller.php',    
));

// 在开始页面注册快捷方式
zotop::add('system.start', 'site_api::start');


// 注册一个控件
// form::field('site_test', 'site_api::test');
?>