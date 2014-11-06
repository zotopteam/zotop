<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 数据导入 api
*
* @package		dbimport
* @version		1.0
* @author		
* @copyright	
* @license		
*/
class dbimport_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['dbimport'] = array(
			'text' => A('dbimport.name'),
			'href' => U('dbimport/admin'),
			'icon' => A('dbimport.url') . '/app.png',
			'description' => A('dbimport.description'));

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