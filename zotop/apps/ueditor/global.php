<?php
//注册类库
zotop::register('ueditor_field',ZOTOP_PATH_APPS.DS.'ueditor'.DS.'libraries'.DS.'ueditor_field.php');

/**
 * 注册控件
 *
 * @param $attrs array 控件参数
 * @return string 控件代码
 */
form::field('editor',array('ueditor_field','editor'));
form::field('ueditor',array('ueditor_field','editor'));
?>