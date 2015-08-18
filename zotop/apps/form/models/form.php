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
class form_model_form extends model
{
	protected $pk 		= 'id';
	protected $table 	= 'form';

	/*
	 *  获取数据集
	 */
	public function select()
	{
		$data = array();

		$rows = $this->db()->orderby('listorder','asc')->select();

		foreach( $rows as &$r )
		{
			$r['settings'] = unserialize($r['settings']);

			$data[$r['id']] = $r;
		}

		return $data;
	}

	/*
	 *  获取数据
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
	 *  添加数据
	 */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));
		if ( empty($data['table']) ) return $this->error(t('数据表名不能为空'));

		// 检查数据表是否存在
		if ( $this->db->schema($data['table'])->exists() )
		{
			return $this->error(t('数据表名已经存在'));
		}

		$data['listorder'] = $this->max('listorder') + 1;

		if ( $id = $this->insert($data) )
		{
			// 表结构
			$table = array(
				'fields'	=> array(
					'id' => array( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true,'autoinc'=>true, 'comment' => t('编号') ),
				),
				'index'		=> array(),
				'unique'	=> array(),
				'primary'	=> array('id'),
				'comment' 	=> $data['name']
			);

			// 创建并缓存
			if ( $this->db->schema($data['table'])->create($table) )
			{
				$this->cache(true);
				return $id;
			}

			// 创建失败并删除数据
			parent::delete($id);
			return $this->error(t('创建数据表失败'));
		}

		return false;
	}

	/*
	 *  编辑数据
	 */
	public function edit($data, $id)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));
		if ( empty($data['table']) ) return $this->error(t('数据表名不能为空'));

		// 尝试修改表注释
		$this->db->schema($data['table'])->comment($data['name']);

		// 更改数据表名称
		if ( $this->get($id,'table') != $data['table'] )
		{
			// 检查数据表是否存在
			if ( $this->db->schema($data['table'])->exists() ) return $this->error(t('数据表名已经存在'));

			if ( $this->db->schema($this->get($id,'table'))->rename($data['table']) == false )
			{				
				unset($data['table']);
			}
		}

		// 更新数据
		if ( $this->update($data, $id) )
		{
			$this->cache(true);

			return $id;
		}

		return false;
	}

	/*
	 *  删除数据
	 *
	 * @param int $id ID
	 * @return bool
	 */ 
	public function delete($id)
	{
		// 对应数据表下有数据
		if ( m('form.data.init', $id)->count() ) return $this->error(t('该表单下尚有数据，无法被删除'));	

		// 删除数据表及数据
		if ( $table = $this->get($id,'table') and parent::delete($id) )
		{
			// 删除字段表中的相关数据
			m('form.field')->deletebyformid($id);

			// 删除数据表
			$this->db->schema($table)->drop();

			// 重建缓存
			$this->cache(true);
			return true;
		}

		return false;
	}

	/**
	 * 排序
	 *
	 * @param array $ids ID数组
	 * @return bool
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
		$disabled = $this->get($id, 'disabled') ? 0 : 1;

		if ( $this->update(array('disabled'=>$disabled), $id) )
		{
			$this->cache(true);
			return true;
		}

		return false;
	}	

	/**
	 * 缓存数据
	 *
	 * @param bool $refresh 是否强制刷新缓存
	 * @return bool
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache("form.form");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->select();

			zotop::cache("form.form", $cache, false);
		}

		return $cache;
	}
}
?>