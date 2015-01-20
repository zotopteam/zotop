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
	 * 添加数据，返回数据编号 
	 * 
	 * @param array &$data 待插入数据
	 * @return int   返回数据编号
	 */
	public function add(&$data)
	{
		return $this->insert($data);
	}

	/**
	 * 修改数据，返回数据编号
	 * 
	 * @param array &$data 待插入数据
	 * @return int   返回数据编号
	 */
	public function edit(&$data,$id)
	{
		return $this->update($data,$id);
	}

	/**
	 * 根数数据编号删除数据
	 * 
	 * @param  int $id 数据编号
	 * @return bool   删除成功或者失败
	 */
	public function delete($id)
	{
		return parent::delete($id);
	}
}
?>