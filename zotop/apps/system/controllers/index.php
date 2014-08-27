<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 首页控制器
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