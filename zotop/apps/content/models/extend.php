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
class content_model_extend extends model
{
	protected $pk 		= 'id';
	protected $table 	= '';

	protected $content 	= null;
	
	/*
	 * 初始化模型 
	 */
	public function init(&$content, $modelid)
	{
		$this->content 	= &$content;
		$this->table 	= "content_model_{$modelid}";
		
		return $this;
	}

	/**
	 * 根据数据编号获取数据
	 * 
	 * @param  int $id 数据编号
	 * @return array   数据
	 */
	public function get($id)
	{
		$data = $this->getbyid($id);

		foreach($data as $k=>$v)
		{
			$data[$k] = str::is_serialized($v) ? unserialize($v) : $v;
		}

		return $data;
	}


	/**
	 * 扩展菜单
	 * 
	 * @param  array $menu    菜单数组
	 * @param  array $content 内容基本数据
	 * @return array
	 */
	public function manage_menu($menu, $content)
	{
		return $menu;
	}	
}
?>