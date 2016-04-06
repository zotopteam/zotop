<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 系统 api
*
* @package		test
* @version		1.0
* @author		hankx
* @copyright	hankx
* @license		http://www.hankx.com
*/
class system_hook
{
	/**
	 * 开始菜单按钮导航
	 * 
	 * @return array
	 */
	public static function start()
	{
		$start = array();

		// 图标HOOK
		$start = zotop::filter('system.start',$start);

		//站点和系统图标放在最后
		$start = $start + zotop::data(A('site.path').DS.'shortcut.php');
		$start = $start + zotop::data(A('system.path').DS.'shortcut.php');

		foreach( $start as $id => $nav )
		{
			if ( $nav['allow'] === false ) unset($start[$id]);
		}

		return $start;		
	}

	/**
	 * HOOK globalnavbar
	 * 
	 * @param  array $nav 快捷导航数组
	 * @return array
	 */
	public static function global_start($nav)
	{
		$nav['start'] = array(
			'text'        => t('主页'),
			'href'        => U('system/admin'),
			'active'      => request::is('system/admin'),
			'class'		  => ''
		);

		return $nav;
	}

	/**
	 * 全局消息提醒
	 * 
	 * @param  array $msg 消息数组
	 * @return array
	 */
	public static function global_msg($msg)
	{
		if ( C('system.debug') )
		{
			$msg[] = array(
				'text' => t('调试模式开启中，网站上线后请关闭'),
				'href' => U('system/config/safety'),
				'type' => 'warning',
			);
		}

		if ( C('system.trace') )
		{
			$msg[] = array(
				'text' => t('页面追踪开启中，网站上线后请关闭'),
				'href' => U('system/config/safety'),
				'type' => 'warning',
			);
		}	

		if ( folder::exists(ZOTOP_PATH.DS.'install') )
		{
			$msg[] = array(
				'text' => t('安装目录尚未删除，为确保安全请删除安装目录'),
				'href' => U('system/config/safety'),
				'type' => 'warning',
			);
		}

		return $msg;
	}

	/**
	 * 上传对话框侧边导航
	 * 
	 * @param  string $type 上传类型
	 * @return array
	 */
	public static function upload_navbar($type='image')
	{
		$nav = array();

		$nav['upload'] = array(
			'text'   => t('本地上传'),
			'href'   => u('system/upload/'.$type, $_GET),
			'icon'   => 'fa fa-upload',
			'active' => request::is('system/upload/'.$type)
		);

		$nav['library'] = array(
			'text'   => t('从库中选择'),
			'href'   => u('system/upload/library/'.$type, $_GET),
			'icon'   => 'fa fa-server',
			'active' => request::is('system/upload/library')
		);

		$nav['dirview'] = array(
			'text'   => t('从目录中选择'),
			'href'   => u('system/upload/dirview/'.$type, $_GET),
			'icon'   => 'fa fa-folder',
			'active' => request::is('system/upload/dirview')
		);

		// $nav['remote'] = array(
		// 	'text'   => t('远程文件'),
		// 	'href'   => u('system/upload/remote'.$type, $_GET),
		// 	'icon'   => 'fa fa-link',
		// 	'active' => request::is('system/upload/remote')
		// );

		return zotop::filter('system.upload.navbar', $nav, $type);		
	}			
}
?>