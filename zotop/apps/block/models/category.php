<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * block_model
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class block_model_category extends model
{
	protected $pk = 'id';
	protected $table = 'block_category';

    /**
     * 插入
     *
     */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('分类名称不能为空'));

		$data['id'] = $this->max('id') + 1;
		$data['listorder'] = $this->max('listorder') + 1;

		if ( $this->insert($data) )
		{
			return true;
		}

		return false;
	}

    /**
     * 编辑
     *
     */
	public function edit($data, $id)
	{
		if ( empty($data['name']) ) return $this->error(t('分类名称不能为空'));

		if ( $this->where('id',$id)->data($data)->update() )
		{
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
		static $result = array();

		if ( empty($result) )
		{
			$result[0] = array('id'=>0, 'name'=>t('全局区块'));

			$data =  $this->db()->orderby('listorder','asc')->getAll();

			foreach( $data as &$d )
			{
				$result[$d['id']] = $d;
			}
		}

		return $result;
	}

    /**
     * 从缓存中获取数据
	 *
	 * @param string $id
	 * @return array
     */
	public function get($id)
	{
		if ( $id )
		{
			return $this->getbyid($id);
		}

		return array();
	}

	/**
	 * 排序
	 *
	 */
	public function order($ids)
	{
		foreach( (array)$ids as $i=>$id )
		{
			$this->where('id',$id)->data('listorder',$i+1)->update();
		}

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

		if ( $id == 0 )
		{
			return $this->error(t('该分类不能被禁用'));
		}

		if ( $this->where('id',$id)->data('disabled',$disabled)->update() )
		{
			return true;
		}

		return false;
	}
}
?>