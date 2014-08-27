<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 内容控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_controller_tag extends site_controller
{
	protected $tag;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->tag = m('content.tag');
	}

	/**
	 * tag list
	 *
	 */
	public function action_index($tag)
    {

		$this->assign('title',$tag);
		$this->assign('description',$tag);
		$this->assign('tag', $tag);
		$this->display('content/tag.php');
    }



}
?>