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
class content_model_gallery extends content_model
{
	protected $pk = 'id';
	protected $table = 'content_model_gallery';

	public function after_get(&$data)
	{
		// 将gallery数据还原为数组
		$data['gallery'] = unserialize($data['gallery']);
		$data['gallery'] = is_array($data['gallery']) ? $data['gallery'] : array();

	}

    // 保存数据前的回调方法
    public function before_add(&$data)
	{
		$data['gallery'] = is_array($data['gallery']) ? array_values($data['gallery']) : array();
		$data['total'] = count($data['gallery']);

		if ( empty($data['thumb']) ) return $this->error(t('缩略图不能为空'));
		if ( empty($data['gallery']) )  return $this->error(t('组图不能为空'));
	}

    // 保存数据前的回调方法
    public function before_edit(&$data)
	{
		$data['gallery'] = is_array($data['gallery']) ? array_values($data['gallery']) : array();
		$data['total'] = count($data['gallery']);

		if ( empty($data['thumb']) ) return $this->error(t('缩略图不能为空'));
		if ( empty($data['gallery']) )  return $this->error(t('组图不能为空'));
	}

}
?>