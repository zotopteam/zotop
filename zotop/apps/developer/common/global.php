<?php
/*
* [name] 全局文件
*
* @package		[id]
* @version		[version]
* @author		[author]
* @copyright	[author]
* @license		[homepage]
*/

// 注册类库到系统中
zotop::register(array(
    '[id]_api' => A('[id].path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', '[id]_api::start');


// 注册一个控件
form::field('[id]_test', '[id]_api::test');
?>