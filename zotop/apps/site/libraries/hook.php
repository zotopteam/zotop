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
class site_hook
{
	/**
	 * HOOK system_navbar
	 * 
	 * @param  array $nav 快捷导航数组
	 * @return array
	 */
	public static function global_navbar($nav)
	{
		$nav = arr::unshift($nav,'sitename', array(
			'text'   => C('site.name'),
			'href'   => U('site/config/index'),
			'allow'  => Priv::allow('site'),
			'active' => Request::is('site/*,mobile/*'),
			'class'  => 'sitename hidden-sm'
		));

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
            'config_search' => array(
                'text'   => t('搜索优化'),
                'href'   => U('site/config/search'),
                'icon'   => 'fa fa-search',
                'active' => request::is('site/config/search')
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