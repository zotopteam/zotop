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
class member_model_group extends model
{
	protected $pk = 'id';
	protected $table = 'user_group';


	/*
	 *  获取数据集
	 */
	public function getall()
	{
		$data = array();

		$rows = $this->db()->orderby('listorder','asc')->getAll();

		foreach( $rows as &$r )
		{
			$r['settings'] = unserialize($r['settings']);

			$data[$r['id']] = $r;
		}

		return $data;
	}

	/*
	 *  获取模型相关会员组
	 */
	public function getModel($modelid)
	{
		$data = array();

		foreach ( $this->cache() as $id=>$group )
		{
			if ( $group['modelid'] == $modelid )  $data[$id] = $group;
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

		return isset($data[$id]) ? $data[$id][$field] : '';
	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));
		if ( empty($data['modelid']) ) return $this->error(t('模型不能为空'));

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
	public function edit($data, $id)
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
		// 用户组下有用户禁止删除
		if ( m('system.user')->where('groupid',$id)->count() ) return $this->error(t('该会员组下尚有会员，不能被删除'));

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
			$cache = $this->getAll();

			zotop::cache("member.group", $cache, false);
		}

		return $cache;
	}
}
?>