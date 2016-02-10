<?php
defined('ZOTOP') OR die('No direct access allowed.');

/*
* 站点首页
*
* @package		site
* @version		1.0
* @author		zotop
* @copyright	copyright zotop
* @license		http://www.zotop.com
*/
class site_controller_index extends site_controller
{
	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {
		$this->assign('title',t('首页'));
		$this->display();
	}
}
?>