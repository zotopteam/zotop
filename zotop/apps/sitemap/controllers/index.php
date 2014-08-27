<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 网站地图 首页控制器
*
* @package		sitemap
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class sitemap_controller_index extends site_controller
{
	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

	}

	/**
	 * index 动作
	 *
	 */
	public function action_index()
    {

		$this->assign('title',t('网站地图'));
		$this->assign('data',$data);
		$this->display('sitemap/index.php');
	}
}
?>