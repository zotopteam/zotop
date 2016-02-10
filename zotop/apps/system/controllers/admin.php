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
	 * 覆盖父类的权限检查,允许全部管理人员访问
	 *
     * @return bool	 
	 */	
	public function __checkPriv()
	{
		return true;
	}
		
	/**
	 * 系统管理首页
	 *
	 */
    public function action_index()
    {
		// 用户信息
		$start = array();

		foreach( a() as $k=>$a)
		{
			if ( in_array($k, m('system.app')->cores) ) continue;

			$shortcut = zotop::data($a['path'].DS.'shortcut.php');

			if ( is_array($shortcut) )
			{
				$start = array_merge($start, $shortcut);
			}			
		}

		$start = zotop::filter('system.start',$start);

		//站点和系统图标放在最后
		$start = $start + zotop::data(A('site.path').DS.'shortcut.php');
		$start = $start + zotop::data(A('system.path').DS.'shortcut.php');

		foreach( $start as $id => $nav )
		{
			if ( $nav['allow'] === false ) unset($start[$id]);
		}

		$this->assign('title',t('开始'));
		$this->assign('start', $start);
		$this->assign('user',$user);
		$this->display();
    }

}
?>