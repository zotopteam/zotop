<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 地区 api
*
* @package		area
* @version		1.0
* @author
* @copyright
* @license
*/
class area_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['area'] = array(
			'text' => A('area.name'),
			'href' => U('area/admin'),
			'icon' => A('area.url') . '/app.png',
			'description' => A('area.description'));

		return $start;
	}


	/**
	 * 地区选择控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function select($attrs)
	{
		$container = 'area_'.$attrs['name'];

		$v = explode(',', $attrs['value']);

		// 默认三级级联
		if ( !is_array($attrs['options']) or empty( $attrs['options'] ) )
		{
			$attrs['options'] = array($attrs['name'].'_province'=>$v[0], $attrs['name'].'_city'=>$v[1], $attrs['name'].'_area'=>$v[2]);
		}

		// 控件属性
		$html['field'] = form::field_hidden($attrs);

		if ( !defined('FIELD_AREA_INIT') )
		{
			define('FIELD_AREA_INIT', true);
			$html['js'] = '<script type="text/javascript" src="'.A('area.url').'/common/area.js"></script>';
		}

		$html[] = '<div id="'. $container .'" for="'.$attrs['id'].'"></div>';
		$html[] = '<script type="text/javascript">';
		$html[] = '$(function(){';
		$html[] = '	$("#'.$container.'").area({';
		$html[] = '		empty:"'. t('--请选择--') .'",';
		$html[] = '		target:"'. $attrs['name'] .'",';
		$html[] = '		source:"'. U('area/api') .'",';
		$html[] = '		params:'. json_encode($attrs['options']);
		$html[] = '	});';
		$html[] = '});';
		$html[] = '</script>';


		return implode("\n",$html);
	}

	/**
	 * 将地区控件注册为控件
	 *
	 * @param $attrs array 已经注册的控件
	 * @return string 控件代码
	 */
	public static function controls($controls)
	{
		$controls['area'] = array('name'=>t('地区联动'),'type'=>'char', 'length'=>'50');

		return $controls;
	}
}
?>