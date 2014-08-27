<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* [name] 后台控制器
*
* @package		[id]
* @version		[version]
* @author		[author]
* @copyright	[author]
* @license		[homepage]
*/
class [id]_controller_admin extends admin_controller
{
	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

	}

	/**
	 * index 动作
	 *
	 */
	public function action_index()
    {

		$this->assign('title',t('[name]'));
		$this->assign('data',$data);
		$this->display();
	}
}
?>