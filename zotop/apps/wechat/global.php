<?php
/*
* 微信 全局文件
*
* @package		wechat
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
	'wechat'     => A('wechat.path') . DS . 'libraries' . DS . 'wechat.php',
	'wechat_api' => A('wechat.path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'wechat_api::start');


/**
 * 全局导航
 */
zotop::add('system.globalnavbar','wechat_api::globalnavbar');
?>