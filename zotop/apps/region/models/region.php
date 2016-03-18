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
class region_model_region extends model
{
	protected $pk = 'id';
	protected $table = 'region';


	/*
	 *  获取数据
	 */
	public function select()
	{
		return $this->db()->orderby('listorder','asc')->orderby('id','asc')->select();
	}

	/*
	 *  获取全部当前节点和当前节点父节点数据
	 */
	public function getParents($id)
	{
		$parents = array();

		if ( $parentids = $this->where('id',$id)->getField('parentids') )
		{
			$parents = $this->where('id','in',explode(',',$region['parentids']))->select();
		}

		return $parents;
	}

	/*
	 * 获取下级子节点
	 *
	 */
	public function getChild($id)
	{
		$result = $this->field('*')->where('parentid','=',$id)->select();

		return is_array($result) ? $result : array();
	}

	/*
	 *  获取数据
	 */
	public function add($data)
	{
		if ( empty($data['id']) ) return $this->error(t('编码不能为空'));
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));

		$data['parentid'] = $data['parentid'] ? $data['parentid'] : 0;

		if ( $data['parentid'] )
		{
			$data['parentids'] = $this->where('id',$data['parentid'])->getField('parentids').','.$data['id'];
			$data['level'] = count(explode(',',$data['parentids']));
		}
		else
		{
			$data['parentids'] = $data['id'];
			$data['level'] = 1;
		}

		$data['letter'] = substr(pinyin::get($data['name']),0,1);

		return $this->insert($data);
	}

	/*
	 *  编辑数据
	 */
	public function edit($data,$id)
	{
		if ( empty($data['id']) ) return $this->error(t('编码不能为空'));
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));

		$data['letter'] = substr(pinyin::get($data['name']),0,1);

		return $this->where('id',$id)->data($data)->update();
	}

	/*
	 *  删除数据
	 */
	public function delete($id)
	{
		// 计算子节点
		if ( $this->where('parentid', $id)->exists()  )
		{
			return $this->error(t('无法删除，下级区域不为空'));
		}

		return parent::delete($id);
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
}
?>