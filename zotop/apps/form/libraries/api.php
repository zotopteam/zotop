<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 自定义表单 api
*
* @package		form
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class form_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['form'] = array(
			'text' => A('form.name'),
			'href' => U('form/admin'),
			'icon' => A('form.url') . '/app.png',
			'description' => A('form.description')
		);

		foreach ( m('form.form.cache') as $id => $form)
		{
			
			if ( $form['disabled'] ) continue;

			$start['form_'.$form['table']] = array(
				'text' => $form['name'],
				'href' => U('form/admin'),
				'icon' => A('form.url') . '/app.png',
				'description' => $form['description']
			);
		}

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