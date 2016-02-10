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

// 快捷导航
zotop::before('system.global.navbar','system_api::global_start','site_api::global_navbar');
?>