<?php
//注册类库
zotop::register(array(
	//'content_model'		=>	ZOTOP_PATH_APPS.DS.'content'.DS.'libraries'.DS.'model.php',
	'content_api'		=>	ZOTOP_PATH_APPS.DS.'content'.DS.'libraries'.DS.'api.php',
));

/**
 * 开始菜单
 */
zotop::add('system.start','content_api::start');


/**
 * 全局导航
 */
zotop::add('system.global.navbar','content_api::global_navbar');


/**
 * 全局消息
 */
zotop::add('system.global.msg','content_api::global_msg');


/**
 * 会员投稿菜单
 */
zotop::add('member.navlist','content_api::membernavlist');


/**
 * 注册控件
 */
form::field('title', 'content_api::field_title');
form::field('summary', 'content_api::field_summary');


/**
 * 模板标签解析
 */
zotop::add('zotop.ready','content_api::content_template_tags');

/**
 * 内容显示钩子
 */
zotop::add('content.show','content_api::content_hits');
zotop::add('content.show','content_api::content_tags');
zotop::add('content.show','content_api::content_pages');

zotop::add('content.fullcontent','content_api::content_full');
?>