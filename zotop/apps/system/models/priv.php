<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 用户模型
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_priv extends model
{
	protected $pk = 'id';
	protected $table = 'admin_priv';

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('权限名称不能为空'));
		if ( empty($data['app']) ) return $this->error(t('应用不能为空'));

		$data['id'] = $data['app'].'_'.$data['controller'].'_'.$data['action'];
		$data['id'] = str_replace(',','_',$data['id']);
		$data['id'] = trim($data['id'],'_');

		return $this->insert($data,true);
	}

    /**
     * 编辑
     *
     */
	public function edit($data, $id)
	{
		if ( empty($data['name']) ) return $this->error(t('权限名称不能为空'));
		if ( empty($data['app']) ) return $this->error(t('应用不能为空'));

		return $this->update($data, $id);
	}

    /**
     * 获取树形数组
     *
     */
	public function getTree()
	{
		$data = $this->getAll();

		if ( is_array($data) )
		{
			return tree::instance($data)->getTree(null);
		}

		return array();
	}

}
?>