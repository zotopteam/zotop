<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 解决方案 api
*
* @package		solution
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license
*/
class solution_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['solution'] = array(
			'text' => A('solution.name'),
			'href' => U('solution/admin'),
			'icon' => A('solution.url') . '/app.png',
			'description' => A('solution.description'));

		return $start;
	}

	/**
	 * 列表处增加管理
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function manage($m, $r)
	{
		if ( $r['app'] == 'solution' )
		{
			$m['solution'] = array('text'=>t('子页面'), 'href'=>U('solution/subpage/index/'.$r['id']) );
		}

		return $m;
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