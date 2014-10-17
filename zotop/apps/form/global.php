<?php
/*
* 自定义表单 全局文件
*
* @package		form
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
    'form_api' => A('form.path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'form_api::start');


// 注册一个控件
form::field('form_test', 'form_api::test');
?>