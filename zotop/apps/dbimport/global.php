<?php
/*
* 数据导入 全局文件
*
* @package		dbimport
* @version		1.0
* @author		
* @copyright	
* @license		
*/

// 注册类库到系统中
zotop::register(array(
    'dbimport_api' => A('dbimport.path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'dbimport_api::start');


// 注册一个控件
form::field('dbimport_test', 'dbimport_api::test');
?>