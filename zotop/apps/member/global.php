<?php
/*
* 会员 全局文件
*
* @package		member
* @version		2.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
    'member_hook'        => A('member.path') . DS . 'libraries' . DS . 'hook.php',
    'member_controller' => A('member.path') . DS . 'libraries' . DS . 'member_controller.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'member_hook::start');

// system_globalnavbar
zotop::add('system.global.navbar','member_hook::globalnavbar');

// 获取表单
zotop::add('member.field.getfields','member_hook::edit_password');
zotop::add('member.field.getfields','member_hook::ajax_remotecheck');

//添加用户
zotop::add('member.add','member_hook::send_validmail');

//会员中心编辑用户界面

?>