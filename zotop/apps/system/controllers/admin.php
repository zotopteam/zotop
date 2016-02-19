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
		$this->assign('title',t('开始'));
		$this->display();
    }

}
?>