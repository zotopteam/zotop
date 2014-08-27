<?php
return array(
	'title' => t('留言本'),
	'description' => t('欢迎给我们留言，或者电话咨询，我们收到留言后将及时回复'),
	'image' => '',

	'adminname' => t('管理员'),
	'showlist' => true,
	'showaudited' => true,
	'showreplied' => true,
	'pagesize' => 10,
	'minlength' => 10,
	'maxlength' => 500,
	'captcha' => false,
	'memberallowed' => true,
	'maxlinks'=>1,
	
	// 当有新的留言的时候，发送邮件通知
	'addmail' => true,
	'addmail_sendto' => '',
	'addmail_title' => t('{site}：您有新的留言'),
	'addmail_content' => t('留言人：<b>{name}</b><br/>电子邮件：{email}<br/>留言内容：{content}'),
	
	// 当留言被回复时候，发送邮件通知留言者
	'replymail' => true,
	'replymail_title' => t('{site}：您在{site}的留言已经有了回复'),
	'replymail_content' => t('您好：{name}，您在 <b>{site}</b> 的留言 {content} 已经有了新的回复: {reply} 请访问我们的网站 {url} 查看'),
);
?>