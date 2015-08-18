<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 规格
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class shop_model_spec extends model
{
	protected $pk = 'id';
	protected $table = 'shop_spec';


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

		if ( isset($data[$id])  )
		{
			return $data[$id];
		}

		return array();
	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));

		$data['id'] = $this->max('id') + 1;
		$data['listorder'] = $data['id'];

		if ( $id = $this->insert($data) )
		{
			$this->cache(true);

			return $id;
		}

		return false;
	}

	/*
	 *  编辑数据
	 */
	public function edit($data,$id)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));


		if ( $this->update($data,$id) )
		{
			$this->cache(true);

			return $id;
		}

		return false;
	}

	/*
	 *  删除数据
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
	 * 缓存数据
	 *
	 * @param bool $refresh 是否强制刷新缓存
	 * @return bool
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache("member.group");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->select();

			zotop::cache("member.group", $cache, false);
		}

		return $cache;
	}
}
?>