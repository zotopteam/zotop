<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * content_model
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_model_model extends model
{
	protected $pk = 'id';
	protected $table = 'content_model';

	protected $field = null; //字段模型

	/**
	 * 添加模型
	 * 
	 * @param array $data 模型数据
	 * @return mixed
	 */
	public function add($data)
	{
		if ( empty($data['id']) ) return $this->error(t('模型编号不能为空'));
		if ( empty($data['name']) ) return $this->error(t('模型名称不能为空'));
		
		$data['app'] 		= 'content';
		$data['model'] 		= 'custom';
		$data['tablename']	= 'content_model_'.$data['id']; 
		$data['listorder']	= $this->max('listorder') + 1;

		$table = array(
			'fields'	=> array('id' => array('type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容编号') )),
			'index'		=> array(),
			'unique'	=> array(),
			'primary'	=> array('id'),
			'comment' 	=> $data['name']
		);

		if ( $this->db->table($data['tablename'])->create($table) == false )
		{
			return $this->error(t('创建附加数据表 {1} 失败', $data['tablename']));
		}

		if ( $this->field = m('content.field') )
		{
			foreach ( $this->field->system_fields as $field)
			{
				$field['modelid'] = $data['id'];
				$this->field->insert($field);
			}
		}

		if ( $this->insert($data, true) )
		{
			$this->cache(true);
			return $id;
		}
		
		$this->field->where('modelid', $data['id'])->delete();
		$this->db->table($data['tablename'])->drop();
		return false;
	}

	/**
	 * 修改模型
	 * 
	 * @param array $data 模型数据
	 * @param  string $id  模型编号
	 * @return mixed
	 */
	public function edit($data, $id)
	{
		if ( empty($data['name']) ) return $this->error(t('模型名称不能为空'));

		if ( $this->update($data, $id) )
		{
			$this->cache(true);
			return $id;
		}

		return false;
	}

    /**
     * 删除
     *
     */
	public function delete($id)
	{
		if ( parent::delete($id) )
		{
			$this->cache(true);
			return true;
		}

		return false;
	}

	/**
	 * 获取排序过的全部数据
	 * 
	 * @return array
	 */
	public function getAll()
	{
		$result = array();

		$data =  $this->db()->orderby('listorder','asc')->getAll();

		foreach( $data as &$d )
		{
			$result[$d['id']] = $d;
		}

		return $result;
	}

    /**
     * 从缓存中获取数据
	 *
	 * @param string $id
	 * @return array
     */
	public function get($id, $field='')
	{
		$data = $this->cache();

		if ( empty($field) )
		{
			return isset($data[$id]) ? $data[$id] : array();
		}

		if ( isset($data[$id]) )
		{
			list($field, $key) = explode('.', $field);

			return ( $key and is_array($data[$id][$field]) ) ? $data[$id][$field][$key] : $data[$id][$field];
		}

		return '';
	}


	/*
	 * 计算模型已有的内容数据
	 *
	 * @param string $id 模型ID
	 * @return int
	 */
	public function datacount($id)
	{
		return m('content.content')->where('modelid','=',$id)->count();
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

		$this->cache(true);
		return true;
	}

	/**
	 * 根据ID设置状态
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function status($id)
	{
		$data = $this->select('disabled')->getbyid($id);

		$disabled = $data['disabled'] ? 0 : 1;

		if ( in_array($id, array('page','link')) )
		{
			return $this->error(t('该模型不能被禁用'));
		}

		if ( $this->update(array('disabled'=>$disabled),$id) )
		{
			$this->cache(true);
			return true;
		}

		return false;
	}

	/*
	 * 缓存数据和设置缓存
	 *
	 * @param string $refresh 强制刷新
	 * @return string 缓存数据
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache("content.model");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->getAll();

			zotop::cache("content.model", $cache, false);
		}

		return $cache;
	}
}
?>