<?php
defined('ZOTOP') OR die('No direct access allowed.');

/*
* [description]
*
* @package		[app.id]
* @version		[app.version]
* @author		[app.author]
* @copyright	copyright [app.author]
* @license		[app.homepage]
*/
class [app.id]_controller_[name] extends admin_controller
{
	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {
    	$data = array();

		$this->assign('title',t('[app.name]管理'));
		$this->assign('data',$data);
		$this->display();
	}
}
?>