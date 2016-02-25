<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 系统信息控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_system extends admin_controller
{
	
	/**
	 * 覆盖权限检查，当前页面的功能不检查权限
	 * 
	 * @return true
	 */
	public function __checkPriv()
	{
		return true;
	}

	/**
	 * 系统管理
	 *
	 */
	public function action_index()
    {
		$system = @include(A('system.path').DS.'data'.DS.'system.php');
		$system = zotop::filter('system.system',$system);

		if ( is_array($system) )
		{
			foreach( $system as $id => $nav )
			{
				if ( $nav['allow'] === false ) unset($system[$id]);
			}
		}

		$this->assign('title',t('系统'));
		$this->assign('system', $system);
		$this->display();
	}

	/**
	 * 一键清理清理缓存数据
	 * 
	 * @return mixed
	 */
	public function action_refresh()
    {
		if ( $post = $this->post() )
		{
			@set_time_limit(0);

			foreach( array('block','caches', 'temp','templates','log') as $folder )
			{
				folder::clear(ZOTOP_PATH_RUNTIME.DS.$folder);
			}

			// 清理缓存
			zotop::cache(null);

			// 清理系统运行文件
			zotop::reboot();

			// hook
			zotop::run('system.refresh');

			return $this->success(t('刷新成功'));
		}
	}

	/**
	 * 系统重启，重启将清理系统缓存、运行时等数据
	 *
	 */
	public function action_reboot()
    {
		if ( $this->post() )
		{
			// 清理运行时
			foreach( array('block','caches','temp','templates','sessions') as $folder )
			{
				folder::clear(ZOTOP_PATH_RUNTIME.DS.$folder);
			}

			// 清理缓存
			zotop::cache(null);

			// 清理系统运行文件
			zotop::reboot();

			return $this->success(t('重启成功'));
		}
    }
}
?>