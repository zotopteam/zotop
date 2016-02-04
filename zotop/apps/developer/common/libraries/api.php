<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* [name] api
*
* @package		[id]
* @version		[version]
* @author		[author]
* @copyright	[author]
* @license		[homepage]
*/
class [id]_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['[id]'] = array(
			'text' => A('[id].name'),
			'href' => U('[id]/admin'),
			'icon' => A('[id].url') . '/app.png',
			'description' => A('[id].description'));

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