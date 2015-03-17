<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 微信 api
*
* @package		wechat
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		
*/
class wechat_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['wechat'] = array(
			'text' => A('wechat.name'),
			'href' => U('wechat/admin'),
			'icon' => A('wechat.url') . '/app.png',
			'description' => A('wechat.description'));

		return $start;
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