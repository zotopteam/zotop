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
	protected $content = null; 
	
	/*
	 * 初始化模型 
	 */
	public function init(&$content)
	{
		$this->content = &$content;
		
		return $this;
	}

	public function get($id)
	{
		$data = $this->getbyid($id);

		return is_array($data) ? $data : array();
	}

    // 删除前的回调方法
    public function before_get(&$data) {}

    // 删除后的回调方法
    public function after_get(&$data) {}

	/*
	 * 添加数据，返回数据编号 
	 */
	public function add(&$data)
	{
		return $this->insert($data);
	}

    // 保存数据前的回调方法
    public function before_add(&$data) {}

    // 保存数据后的回调方法
    public function after_add(&$data) {}

	/*
	 * 修改数据，返回数据编号 
	 */	
	public function edit(&$data)
	{
		return $this->update($data);
	}

    // 保存数据前的回调方法
    public function before_edit(&$data) {}

    // 保存数据后的回调方法
    public function after_edit(&$data) {}

	/*
	 * 删除数据
	 */
	public function delete($id)
	{
		return parent::delete($id);
	}

    // 删除前的回调方法
    public function before_delete(&$data) {}

    // 删除后的回调方法
    public function after_delete(&$data) {}
}
?>