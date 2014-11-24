<?php
/*
* 商城 全局文件
*
* @package		shop
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
    'shop_api' => A('shop.path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'shop_api::start');

//在导航栏注册一个按钮
zotop::add('system.globalnavbar','shop_api::globalnavbar');

// 注册一个控件
form::field('shop_test', 'shop_api::test');


/**
 * 模版标签 {content action="list"}……{/content}
 *
 * 标签说明
 *
 *	cid|categoryid	指定要查询的栏目ID，调用多个ID用英文逗号”,”隔开
 *	tag				指定内容包含的关键词,多个关键词用英文逗号“,”隔开，例如:tags="测试,test"
 *	thumb			指定查询结果是否必须含有或者不含缩略图，默认为不筛选 ，thumb="true" 筛选缩略图不为空的内容，thumb="false" 筛选缩略图为空的内容
 * 	orderby			指定排序条件，多个排序条件用英文逗号“,”隔开,例如：orderby="createtime desc,weight desc"
 *	weight			权重筛选，默认不筛选:
 *							weight="10"表示筛选权重值等于10的内容
 *							weight="10,"表示筛选权重值大于等于10的内容
 *							weight=",10"表示筛选权重值小于等于100的内容
 *							weight="10,100"表示筛选权重值介于[10-100]之间的内容
 *	size			内容表查询结果返回条数限制(必须是正整数)，默认为 10
 *	model			指定要调用的内容模型ID，调用多个ID用英文逗号”,”隔开，同一个model的内容都会被选择出来,如“article”
  *	modeldata		附加表数据字段，多个字段用逗号隔开，必须指定model才有效，如调用模型“article”附加表中的作者和来源：“author,source”
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
zotop::add('zotop.ready','shop_tags');

function shop_tags()
{
	template::tag('shop');

	function tag_shop($attrs)
	{
		$result = array();

		switch(strtolower($attrs['action']))
		{
			case 'category':
				$result = m('shop.category')->tag_category($attrs);
				break;
			case 'position':
				$result = m('shop.category')->tag_position($attrs);
				break;
			default:
				$result = m('shop.goods')->tag_list($attrs);
				break;
		}

		return $result;
	}
}
?>