<?php
/*
* 移动站点 全局文件
*
* @package		mobile
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
    'mobile_api' => A('mobile.path') . DS . 'libraries' . DS . 'api.php',
    'mobile_detect' => A('mobile.path') . DS . 'libraries' . DS . 'Mobile_Detect.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'mobile_api::start');


// 移动站点检查
zotop::add('site.init','mobile_api::site_init');
?>