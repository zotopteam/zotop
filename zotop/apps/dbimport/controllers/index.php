<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 数据导入 首页控制器
*
* @package		dbimport
* @version		1.0
* @author		
* @copyright	
* @license		
*/
class dbimport_controller_index extends site_controller
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

		$this->assign('title',t('数据导入'));
		$this->assign('data',$data);
		$this->display('dbimport/index.php');
	}
}
?>