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
    '[app.id]_api' => A('[app.id].path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册快捷方式
zotop::add('system.start', '[app.id]_api::start');


// 注册一个控件
// form::field('[app.id]_test', '[app.id]_api::test');
?>