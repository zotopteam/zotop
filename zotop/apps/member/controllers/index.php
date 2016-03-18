<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员 首页控制器
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class member_controller_index extends member_controller
{
	/**
	 * index 动作
	 *
	 */
	public function action_index()
    {
    	$user = m('system.user.get', zotop::user('id'));

		$this->assign('title',t('会员中心'));
		$this->assign('user',$user);
		$this->display('member/index.php');
	}
}
?>