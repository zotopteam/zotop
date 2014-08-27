<?php
return array(
	'newflag' => 'createtime',
	'newflag_expires' => 72, // 单位小时

	'autosummary' => 250, // 自动提取摘要长度，0关闭，大于0为摘要长度
	'autothumb' => 1, // 自动提取缩略图，0关闭，大于0为图片在内容区域的编号
	'autokeywords'=>1, // 自动提取关键词

	'attachment.delete' => 1, // 同步删除附件

	'thumb_resize'	=> 1, // 缩略图尺寸
	'thumb_width'	=> 680,
	'thumb_height'	=> 600,
	'thumb_quality'	=> 98,

	'image_resize'	=> 1, // 内容尺寸
	'image_width'	=> 680,
	'image_height'	=> 600,
	'image_quality'	=> 98,

	'category_image_resize'	 => 2, // 栏目图片缩放，并强制宽高
	'category_image_width'	 => 1200,
	'category_image_height'	 => 600,
	'category_image_quality' => 98,
);
?>