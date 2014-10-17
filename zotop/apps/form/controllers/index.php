<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 自定义表单 首页控制器
*
* @package		form
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class form_controller_index extends site_controller
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

		$this->assign('title',t('自定义表单'));
		$this->assign('data',$data);
		$this->display('form/index.php');
	}
}
?>