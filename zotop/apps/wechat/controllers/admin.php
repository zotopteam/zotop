<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 微信 后台控制器
*
* @package		wechat
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		
*/
class wechat_controller_admin extends admin_controller
{
	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {

		$this->assign('title',t('微信'));
		$this->assign('data',$data);
		$this->display();
	}
	
}
?>