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
	protected $models = null;

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['id']) ) return $this->error(t('模型编号不能为空'));
		if ( empty($data['name']) ) return $this->error(t('模型名称不能为空'));

		$data['listorder'] = $this->max('listorder') + 1;

		if ( $id = $this->insert($data, true) )
		{
			$this->cache(true);

			return $id;
		}

		return false;
	}

    /**
     * 编辑
     *
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
     */
	public function getAll()
	{
		$result = array();

		$data =  $this->db()->orderby('listorder','asc')->getAll();

		foreach( $data as &$d )
		{
			$d['settings'] = unserialize($d['settings']);

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
		if ( $id )
		{
			if ( !is_array($this->models) ) $this->models = $this->cache();

			if ( is_array($this->models) and isset($this->models[$id]) )
			{
				return $this->models[$id];
			}
		}

		return array();
	}


	/*
	 * 重新模型栏目数据
	 *
	 * @param string $id 模型ID
	 * @return string 缓存数据
	 */
	public function datacount($id)
	{
		$count = m('content.content')->where('modelid','=',$id)->count();

		return $count;
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