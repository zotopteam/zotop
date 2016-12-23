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
    'form_hook' => A('form.path') . DS . 'libraries' . DS . 'hook.php',
));

// 在开始页面注册一个快捷方式
zotop::add('global.start', 'form_hook::global_start');


// 注册一个控件
//form::field('form_test', 'form_hook::test');

// 模板标签
template::tag('formdata','tag_formdata');

function tag_formdata($attrs)
{
	return form_hook::data($attrs);
}
?>