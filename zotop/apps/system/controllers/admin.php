<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 页面控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_admin extends admin_controller
{
	/**
	 * 系统管理首页
	 *
	 */
    public function action_index()
    {
		// 用户信息
		$user = m('system.user')->getbyid(zotop::user('id'));

		// 导航
		$start = zotop::filter('system.start',array());
		$start = $start + @include(A('system.path').DS.'data'.DS.'system.php');

		if ( is_array($start) )
		{
			foreach( $start as $id => $nav )
			{
				if ( $nav['allow'] === false ) unset($start[$id]);
			}
		}


		$this->assign('title',t('开始'));
		$this->assign('start', $start);
		$this->assign('user',$user);
		$this->display();
    }

}
?>