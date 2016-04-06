<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 地域管理
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class region_controller_hook extends site_controller
{


	/**
     * 下拉选择框获取节点
     *
     * @return void
     */
	public function action_index()
    {
		$parentid = isset($_GET['parentid']) ? $_GET['parentid'] : 0;

		// 获取子节点
		$childs = m('region.region')->getChild($parentid);

		// 输出json
		exit(json_encode($childs));
    }
}
?>