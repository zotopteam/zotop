<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 品牌
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class shop_model_brand extends model
{
	protected $pk = 'id';
	protected $table = 'shop_brand';

	public function __construct()
	{
		parent::__construct();
	}


	/*
	 *  获取数据集
	 */
	public function getall()
	{
		$data = array();

		$rows = $this->db()->orderby('listorder','asc')->getAll();

		foreach( $rows as &$r )
		{
			$r['dataid'] = "shop-brand-{$r['id']}";

			$data[$r['id']] = $r;
		}

		return $data;
	}

	/*
	 *  未被禁用的数据
	 */
	public function active()
	{
		$data = $this->cache();

		foreach ($data as $k => $v)
		{
			if ( $v['disabled'] ) unset($data[$k]);
		}

		return $data;
	}


	/*
	 *  hashmap
	 */
	public function hashmap()
	{
		return arr::hashmap($this->active(), 'id', 'name');
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

		return isset($data[$id]) ? $data[$id][$field] : null;
	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));

		$data['listorder'] = $this->max('listorder') + 1;


		if ( $id = $this->insert($data) )
		{
            // 保存关联附件
            m('system.attachment')->setRelated("shop-brand-{$id}");

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
            // 保存关联附件
            m('system.attachment')->setRelated("shop-brand-{$id}");


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
            // 删除关联附件
            m('system.attachment')->delRelated("shop-brand-{$id}");

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

	/*
	 * 缓存数据和设置缓存
	 *
	 * @param string $refresh 强制刷新
	 * @return string 缓存数据
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache('shop.brand');

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->getAll();

			zotop::cache('shop.brand', $cache, false);
		}

		return $cache;
	}
}
?>