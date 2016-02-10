<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 站点 api
*
* @package		site
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class site_api
{
	/**
	 * HOOK [system.start] 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['site'] = array(
			'text'        => t('站点设置'),
			'href'        => U('site/config/index'),
			'icon'        => A('site.url') . '/app.png',
			'description' => t('站点名称、搜索优化、联系方式等设置'),
			'allow'       => priv::allow('site'),
		);

		return $start;
	}

	/**
	 * HOOK
	 * 
	 * @param  [type] $nav [description]
	 * @return [type]      [description]
	 */
	public static function globalnavbar($nav)
	{
		$nav['site'] = array(
			'text'        => A('site.name'),
			'href'        => U('site'),
			'icon'        => A('site.url').'/app.png',
			'description' => A('site.description'),
			'allow'       => priv::allow('site'),
			'active'      => (ZOTOP_APP == 'site')
		);

		return $nav;
	}

	/**
	 * 侧边菜单导航
	 * 
	 * @return [type] [description]
	 */
	public function admin_sidebar()
	{
		return zotop::filter('site.admin.sidebar',array(
			'config_index' => array(
				'text'   => t('站点设置'),
				'href'   => U('site/config/index'),
				'icon'   => 'fa fa-cog',
				'active' => request::is('site/config/index')
			),
			'config_contact' => array(
				'text'   => t('联系方式'),
				'href'   => U('site/config/contact'),
				'icon'   => 'fa fa-phone',
				'active' => request::is('site/config/contact')
			),
			'config_close' => array(
				'text'   => t('网站状态'),
				'href'   => U('site/config/state'),
				'icon'   => 'fa fa-power-off',
				'active' => request::is('site/config/state')
			),						
		));
	}	
}
?>