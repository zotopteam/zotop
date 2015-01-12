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
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {

		$this->assign('title',t('[name]'));
		$this->assign('data',$data);
		$this->display();
	}
	
}
?>