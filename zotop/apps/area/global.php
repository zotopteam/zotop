<?php
/*
* 地区 全局文件
*
* @package		area
* @version		1.0
* @author
* @copyright
* @license
*/

// 注册类库到系统中
zotop::register(array(
    'area_api' => A('area.path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'area_api::start');

// 注册一个控件
form::field('area', 'area_api::select');

// 注册自定义控件
zotop::add('field.controls', 'area_api::controls');
?>