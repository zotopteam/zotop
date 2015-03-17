<?php
/*
* 微信 全局文件
*
* @package		wechat
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		
*/

// 注册类库到系统中
zotop::register(array(
    'wechat_api' => A('wechat.path') . DS . 'libraries' . DS . 'api.php',
    'wechat' => A('wechat.path') . DS . 'libraries' . DS . 'wechat.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'wechat_api::start');


// 注册一个控件
form::field('wechat_test', 'wechat_api::test');
?>