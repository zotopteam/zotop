<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * form_model
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class form_model_fields extends model
{
	protected $pk 		= 'id';
	protected $table 	= 'form_fields';


	/**
	 * 初始化对应的table类
	 * 
	 * @param  string $table 数据表名称，不含前缀
	 * @return $object 返回类对象
	 */
	public function cache($table)
	{

	}
}
?>