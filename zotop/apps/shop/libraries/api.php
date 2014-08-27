<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 商城 api
*
* @package		shop
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class shop_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['shop'] = array(
			'text' => A('shop.name'),
			'href' => U('shop/goods'),
			'icon' => A('shop.url') . '/app.png',
			'description' => A('shop.description'));

		return $start;
	}

	/**
	 * 注册全局导航
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function globalnavbar($nav)
	{
		$nav['shop'] = array(
			'text' => A('shop.name'),
			'href' => u('shop/goods'),
			'icon' => A('shop.url').'/app.png',
			'description' => A('shop.description'),
			'allow' => priv::allow('shop'),
			'current' => (ZOTOP_APP == 'shop')
		);

		return $nav;
	}

	/**
	 * 测试控件，请修改或者删除此处代码，详细修改方式请参见文档
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function test($attrs)
	{
		// 控件属性
		$html['field'] = form::field_text($attrs);

		return implode("\n",$html);
	}
}
?>