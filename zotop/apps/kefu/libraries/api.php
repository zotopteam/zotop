<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 在线客服 api
*
* @package		kefu
* @version		1.8
* @author		
* @copyright	
* @license		
*/
class kefu_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['kefu'] = array(
			'text' => A('kefu.name'),
			'href' => U('kefu/admin'),
			'icon' => A('kefu.url') . '/app.png',
			'description' => A('kefu.description'));

		return $start;
	}
}
?>