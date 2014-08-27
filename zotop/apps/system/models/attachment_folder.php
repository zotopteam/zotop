<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * attachment_folder
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team 
 * @license		http://zotop.com/license.html
 */
class system_model_attachment_folder extends model
{
	protected $pk = 'id';
	protected $table = 'attachment_folder';

    /**
     * 更新前处理数据
     * 
     */	
	public function add($data)
	{	
		if ( isset($data['name']) and empty($data['name']) ) return $this->error(t('分类名称不能为空'));

		$data['listorder'] = $this->max('listorder') + 1; 

		return $this->insert($data, true);
	}

    /**
     * 更新前处理数据
     * 
     */	
	public function edit($data, $id)
	{
		if ( isset($data['name']) and empty($data['name']) ) return $this->error(t('分类名称不能为空'));

		return $this->update($data, $id);
	}

	/**
	 * 排序
	 *
	 */	
	public function order($ids)
	{
		foreach( (array)$ids as $i=>$id )
		{
			$this->update(array('listorder'=>$i+1), $id);
		}

		return true;
	}
}
?>