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
zotop::add('system.globalnavbar','content_api::globalnavbar');


/**
 * 全局消息
 */
zotop::add('system.globalmsg','content_api::globalmsg');


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
 * 模版标签 {content action="list"}……{/content}
 *
 * 标签说明
 *
 *	cid|categoryid	指定要查询的栏目ID，调用多个ID用英文逗号”,”隔开
 *	pid|parentid	指定要调用内容的父ID，默认为 0
 *	mid|modelid		指定要调用的内容模型ID，调用多个ID用英文逗号”,”隔开，同一个model的内容都会被选择出来,如“article”
 *	keywords		指定内容包含的关键词,多个关键词用英文逗号“,”隔开，例如:tags="测试,test"
 *	image			指定查询结果是否必须含有或者不含缩略图，默认为不筛选 ，image="true" 筛选缩略图不为空的内容，image="false" 筛选缩略图为空的内容
 * 	orderby			指定排序条件，多个排序条件用英文逗号“,”隔开,例如：orderby="createtime desc"
 *	size			内容表查询结果返回条数限制(必须是正整数)，默认为 10
 *	page			当前页码，设置后自动开启分页, 为数字的时候自动获取当前页码，其它为指定页码
 *	cache			缓存时间，单位：秒，true = 系统缓存设置 false = 永久缓存 0 不缓存
 *	return			返回值变量 return：返回结果变量名，默认为 r
 *
 *	内部标签
 *
 *	{$r.id},{$r.title},{$r.style},{$r.url},{$r.createtime},{$r.updatetime}
 *
 *	扩展标签
 *
 *	{$r.newflag} {$r.new}
 *
 */
zotop::add('zotop.ready','content_tags');

function content_tags()
{
	template::tag('content');

	function tag_content($attrs)
	{
		$result = array();

		switch(strtolower($attrs['action']))
		{
			case 'category':
				$result = m('content.category')->tag_category($attrs);
				break;
			case 'position':
				$result = m('content.category')->tag_position($attrs);
				break;
			default:
				$result = m('content.content')->tag_content($attrs);
				break;
		}

		return $result;
	}
}
?>