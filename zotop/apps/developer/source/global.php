<?php
/*
* [app.name] 全局文件
*
* @package		[app.id]
* @version		[app.version]
* @author		[app.author]
* @copyright	[app.author]
* @license		[app.homepage]
*/

// 注册类库到系统中
zotop::register(array(
    '[app.id]_hook' => A('[app.id].path') . DS . 'libraries' . DS . 'hook.php',
));

// 在开始页面注册快捷方式
zotop::add('system.start', '[app.id]_hook::start');

// 注册后台顶部的快捷导航
//zotop::add('system.global.navbar','[app.id]::global_navbar');

// 注册一个控件
// form::field('[app.id]_test', '[app.id]_hook::test');
?>