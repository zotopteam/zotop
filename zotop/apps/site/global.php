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
	'site_hook'        => A('site.path') . DS . 'libraries' . DS . 'hook.php',
	'site_controller' => A('site.path') . DS . 'libraries' . DS . 'site_controller.php',    
));

// 快捷导航
zotop::before('system.global.navbar','system_hook::global_start','site_hook::global_navbar');
?>