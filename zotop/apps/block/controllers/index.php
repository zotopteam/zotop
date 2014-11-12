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
class block_controller_index extends site_controller
{
	/**
	 * 区块预览
	 * 
	 * @param  int $id 区块编号
	 * @return mixed
	 */
	public function action_preview($id)
	{
		// 当前数据
		$data = m('block.block.get',$id);
		
		$this->assign($data);
		$this->display('block/preview.php');
	}
}
?>