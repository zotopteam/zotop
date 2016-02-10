<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 系统首页控制器 //TODO 改作其它用途
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_index extends site_controller
{
	/**
	 * 网站首页
	 *
	 */
	public function action_index()
    {
		$this->display('index.php');
    }
}
?>