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
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['mobile'] = array(
			'text' => A('mobile.name'),
			'href' => U('mobile/config'),
			'icon' => A('mobile.url') . '/app.png',
			'description' => A('mobile.description'));

		return $start;
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

			if ( $detect->isMobile() or $detect->isTablet() )
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