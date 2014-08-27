<?php
/*
* 会员 全局文件
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
    'member_api' => A('member.path') . DS . 'libraries' . DS . 'api.php',
	'member_controller' => A('member.path') . DS . 'libraries' . DS . 'member_controller.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'member_api::start');

//system_globalnavbar
zotop::add('system.globalnavbar','member_api::globalnavbar');

// 注册一个控件
form::field('member_test', 'member_api::test');
?>