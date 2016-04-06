<?php
/*
* 网站地图 全局文件
*
* @package		sitemap
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
    'sitemap' 		=> A('sitemap.path') . DS . 'libraries' . DS . 'sitemap.php',
    'sitemap_hook' 	=> A('sitemap.path') . DS . 'libraries' . DS . 'hook.php',    
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'sitemap_hook::start');

/**
 * 站点地图
 */
zotop::add('sitemap.items','sitemap_hook::content');

?>