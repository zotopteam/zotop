<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 微信 api
*
* @package		wechat
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class wechat_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['wechat'] = array(
			'text'        => A('wechat.name'),
			'href'        => U('wechat/admin'),
			'icon'        => A('wechat.url') . '/app.png',
			'description' => A('wechat.description'),
			'allow'       => priv::allow('wechat'),			
		);

		return $start;
	}


	/**
	 * 全局导航
	 *
	 * @param $nav array 已有数据
	 * @return array
	 */
	public static function globalnavbar($nav)
	{

		$nav['wechat'] = array(
			'text'        => A('wechat.name'),
			'href'        => u('wechat/admin'),
			'icon'        => A('wechat.url').'/app.png',
			'description' => A('wechat.description'),
			'allow'       => priv::allow('wechat'),
			'current'     => (ZOTOP_APP == 'wechat')
		);

		$accounts = m('wechat.account.cache');

		if ( $accounts )
		{

			foreach ($accounts as $k => $v)
			{
				$nav['wechat']['menu'][$k] = array(
					'text' => $v['name'] .' ('. $v['account'].')',
					'icon' => '<i class="icon icon-wechat"></i>',
					'href' => U('wechat/admin',array('accountid'=>$k)) 
				); 
			}

			$nav['wechat']['menu']['account'] = array(
					'text' => t('公众号管理'),
					'icon' => '<i class="icon icon-setting"></i>',
					'href' => U('wechat/account')
			);		
		}
		else
		{
 			$nav['wechat']['url'] = U('wechat/account');
		}

		return $nav;
	}
}
?>