<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 开发助手
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class developer_controller_index extends admin_controller
{
	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		zotop::cookie('project', null);
	}

	/**
	 * 配置中的数据库
	 *
	 */
	public function action_index()
    {
		//获取正在建设的项目
		$projects = array();

		foreach(glob(ZOTOP_PATH_APPS . DS . '*' . DS .'app.php') as $file)
		{
			$projects[] = include(dirname($file).DS.'app.php');
		}

		$this->assign('title',t('开发助手'));
		$this->assign('projects',$projects);
		$this->display();
	}
}
?>