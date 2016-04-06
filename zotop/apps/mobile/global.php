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
    'mobile_hook' => A('mobile.path') . DS . 'libraries' . DS . 'hook.php',
    'mobile_detect' => A('mobile.path') . DS . 'libraries' . DS . 'Mobile_Detect.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'mobile_hook::start');

// 更改顶栏网站名称的状态
zotop::add('system.global.navbar', 'mobile_hook::global_navbar');

// 在站点管理侧边条加入一个链接
zotop::add('site.admin.sidebar','mobile_hook::admin_sidebar');

// 移动站点检查
zotop::add('site.init','mobile_hook::site_init');
?>