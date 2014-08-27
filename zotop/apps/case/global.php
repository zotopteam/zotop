<?php
/*
* 工程案例 全局文件
*
* @package		case
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license
*/

// 注册类库到系统中
zotop::register(array(
    'case_api' => A('case.path') . DS . 'libraries' . DS . 'api.php',
));

// 列表快捷操作
//zotop::add('content.manage', 'case_api::manage');


// 注册一个控件
//form::field('case_test', 'case_api::test');
?>