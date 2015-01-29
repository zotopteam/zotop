<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 别名
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team 
 * @license		http://zotop.com/license.html
 */
class content_model extends model
{
	protected $pk = 'id';
	protected $content = null; 
	
	/*
	 * 初始化模型 
	 */
	public function init(&$content, $modelid)
	{
		$this->content = &$content;
		
		return $this;
	}
}
?>