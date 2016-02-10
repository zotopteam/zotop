<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 移动站点 api
*
* @package		mobile
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class mobile_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 参数
	 * @return array
	 */
	public static function start($start)
	{
		$start['mobile'] = array(
			'text'        => A('mobile.name'),
			'href'        => U('mobile/config'),
			'icon'        => A('mobile.url') . '/app.png',
			'description' => A('mobile.description'),
			'allow'       => priv::allow('mobile')
		);

		return $start;
	}

	/**
	 * HOOK globalnavbar
	 * 
	 * @param  array $nav 快捷导航数组
	 * @return array
	 */
	public static function global_navbar($nav)
	{
		$nav['sitename']['active'] = request::is('site','mobile');

		return $nav;
	}	

	/**
	 * 在站点管理侧边条加入一个链接
	 *
	 * @param $attrs array 参数
	 * @return array
	 */
	public static function admin_sidebar($array)
	{
		// 将链接插入到 站点设置[config_index]之后
		$array = arr::after($array,'config_index','config_mobile',array(
			'text'        => A('mobile.name'),
			'href'        => U('mobile/config'),
			'icon'        => 'fa fa-mobile',
			'description' => A('mobile.description'),
			'allow'       => priv::allow('mobile'),
			'active'      => request::is('mobile/config')
		));

		return $array;
	}

	/**
	 * 检测是否为移动设备访问并自动加载移动主题,TODO 测试该函数在子目录使用情况下的效果
	 *
	 * @param $site obj 站点控制器
	 * @return null
	 */
	public static function site_init($site)
	{
		if ( C('mobile.url') and request::host() == C('mobile.url')  )
		{
			define('ZOTOP_MOBILE',true);
		}
		else
		{
			$detect = new Mobile_Detect();

			// 只侦测手机，不侦测IPAD等平板
			if ( $detect->isMobile() and !$detect->isTablet() )
			{
				define('ZOTOP_MOBILE',true);

				if ( C('mobile.url') and request::host() <> C('mobile.url') )
				{
					$site->redirect(C('mobile.url').$_SERVER["REQUEST_URI"]);
				}
			}			
		}

		if ( defined('ZOTOP_MOBILE') )
		{
			C('site.theme',C('mobile.theme'));
		}
	}
}
?>