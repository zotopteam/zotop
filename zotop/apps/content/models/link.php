<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * content
 *
 * @package		content
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_model_link extends content_model
{
	protected $pk = 'id';
	protected $table = '';

	public function get($id, $field='')
	{
		return array();
	}

	/*
	 * 添加数据，返回数据编号
	 */
	public function add(&$data)
	{
		return true;
	}

	/*
	 * 修改数据，返回数据编号
	 */
	public function edit(&$data)
	{
		return true;
	}

	/*
	 * 删除数据
	 */
	public function delete($id)
	{
		return true;
	}
}
?>