<?php
//注册类库
zotop::register(array(
	//'content_model'		=>	ZOTOP_PATH_APPS.DS.'content'.DS.'libraries'.DS.'model.php',
	'content_hook'		=>	ZOTOP_PATH_APPS.DS.'content'.DS.'libraries'.DS.'hook.php',
));

/**
 * 开始菜单
 */
zotop::add('system.start','content_hook::start');


/**
 * 全局导航
 */
zotop::add('system.global.navbar','content_hook::global_navbar');


/**
 * 全局消息
 */
zotop::add('system.global.msg','content_hook::global_msg');


/**
 * 会员投稿菜单
 */
zotop::add('member.navlist','content_hook::membernavlist');


/**
 * 注册控件
 */
form::field('title', 'content_hook::field_title');
form::field('summary', 'content_hook::field_summary');


/**
 * 模板标签解析
 */
zotop::add('zotop.ready','content_hook::content_template_tags');

/**
 * 内容显示钩子
 */
zotop::add('content.show','content_hook::content_hits');
zotop::add('content.show','content_hook::content_tags');
zotop::add('content.show','content_hook::content_pages');

zotop::add('content.fullcontent','content_hook::content_full');
?>