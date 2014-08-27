<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 网站地图 后台控制器
*
* @package		sitemap
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class sitemap_controller_admin extends admin_controller
{
	/**
	 * 网站地图首页
	 *
	 */
	public function action_index()
    {
    	$sitemap = zotop::instance('sitemap');

    	// 第一次自动生成
    	if ( !$sitemap->exists() )
    	{
    		$sitemap->create();
    	}

    	$url 	= $sitemap->url();
    	$path 	= $sitemap->path();
    	$time 	= file::time($path);  // 最后生成时间
    	$size	= file::size($path);

		$this->assign('title', t('网站地图'));
		$this->assign('url', $url);
		$this->assign('path', $path);
		$this->assign('time', $time);
		$this->assign('size', $size);
		$this->display();
	}

	/**
	 * 创建网站地图，一般用于ajax调用
	 *
	 */
	public function action_create()
	{
		if( zotop::instance('sitemap')->create() )
		{
			return $this->success(t('操作成功'),request::referer());
		}
		
		return $this->error(t('操作失败'));
	}
}
?>