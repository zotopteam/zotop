<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * zotop
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_zotop extends admin_controller
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
     * 关于
     *
     * @return void
     */
	public function action_index()
	{
		$license = file::get(A('system.path').DS.'license.txt');
		$license = format::textarea($license);

		$this->assign('title',t('关于zotop'));
		$this->assign('license',$license);
		$this->display();
	}
}
?>