<?php
defined('ZOTOP') OR die('No direct access allowed.');

/*
* [name] 首页控制器
*
* @package		[id]
* @version		[version]
* @author		[author]
* @copyright	[author]
* @license		[homepage]
*/
class [id]_controller_index extends site_controller
{
	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {
    	$data = array();

		$this->assign('title',t('[name]'));
		$this->assign('data',$data);
		$this->display('[id]/index.php');
	}
}
?>