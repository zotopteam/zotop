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
class solution_model_solution extends content_model
{
	protected $pk = 'id';
	protected $table = 'content_model_solution';

	// 读取之后处理数据
	public function after_get(&$data)
	{

	}

    // 保存数据前的回调方法
    public function before_add(&$data)
	{

	}

    // 保存数据前的回调方法
    public function before_edit(&$data)
	{

	}

}
?>