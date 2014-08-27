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
class content_model_download extends content_model
{
	protected $pk = 'id';
	protected $table = 'content_model_download';

	public function after_get(&$data)
	{
		$data['downloadurl'] = u('content/detail/download/'.$data['id']);
	}

    // 保存数据前的回调方法
    public function before_add(&$data)
	{
		if ( empty($data['filepath']) )  return $this->error(t('文件地址不能为空'));
	}

    // 保存数据前的回调方法
    public function before_edit(&$data)
	{
		if ( empty($data['filepath']) )  return $this->error(t('文件地址不能为空'));
	}

	// 下载文件，增加下载次数
	public function downfile($id)
	{
		$this->update(array(
			'download' => array('download','+',1)
		),$id);
	}
}
?>