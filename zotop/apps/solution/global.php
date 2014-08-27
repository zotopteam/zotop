<?php
/*
* 解决方案 全局文件
*
* @package		solution
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license
*/

// 注册类库到系统中
zotop::register(array(
    'solution_api' => A('solution.path') . DS . 'libraries' . DS . 'api.php',
));

// 列表快捷操作
//zotop::add('content.manage', 'solution_api::manage');


// 注册一个控件
//form::field('solution_test', 'solution_api::test');
?>