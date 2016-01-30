<?php
//注册类库
zotop::register('tinymce_field',ZOTOP_PATH_APPS.DS.'tinymce'.DS.'libraries'.DS.'tinymce_field.php');

/**
 * 注册控件
 *
 * @param $attrs array 控件参数
 * @return string 控件代码
 */
form::field('tinymce',array('tinymce_field','editor'));
form::field('editor',array('tinymce_field','editor'));

zotop::add('editor.options',array('tinymce_field','basic'));
zotop::add('editor.options',array('tinymce_field','standard'));
zotop::add('editor.options',array('tinymce_field','full'));
?>