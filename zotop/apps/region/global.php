<?php
/*
* 地区 全局文件
*
* @package		region
* @version		1.0
* @author
* @copyright
* @license
*/

// 注册类库到系统中
zotop::register(array(
    'region_hook' => A('region.path') . DS . 'libraries' . DS . 'hook.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'region_hook::start');

// 注册一个控件
form::field('region', 'region_hook::select');

// 注册自定义控件
zotop::add('field.controls', 'region_hook::controls');
?>