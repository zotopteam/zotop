<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * member_model
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class member_model_model extends model
{
	protected $pk = 'id';
	protected $table = 'user_model';


	/*
	 *  获取数据集
	 */
	public function select()
	{
		$data = array();

		$rows = $this->db()->where('app','member')->orderby('listorder','asc')->select();

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

		return isset($data[$id]) ? $data[$id][$field] : '';
	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		if ( empty($data['id']) ) return $this->error(t('标识不能为空'));
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));
		if ( empty($data['tablename']) ) return $this->error(t('数据表名不能为空'));
		if ( empty($data['groupname']) ) return $this->error(t('默认用户组不能为空'));

		// 检查模型标识是否存在
		if ( $this->where('id',$data['id'])->exists() )
		{
			return $this->error(t('标识已经存在'));
		}

		// 检查数据表是否存在
		if ( $this->where('tablename',$data['tablename'])->exists() or $this->db->existsTable($data['tablename']) )
		{
			return $this->error(t('数据表名已经存在'));
		}

		// 新建默认用户组
		$groupid = m('member.group')->add(array('name'=>$data['groupname'],'modelid'=>$data['id']));

		if ( empty($groupid) )
		{
			 return $this->error(t('新建用户组失败'));
		}

		$data['app']                 = 'member';		
		$data['listorder']           = $this->max('listorder') + 1;
		$data['settings']['groupid'] = $groupid;

		if ( $id = $this->insert($data) )
		{
			// 插入系统字段
			$this->field = m('member.field');

			$listorder = 1;

			foreach ( $this->field->system_fields as $field)
			{
				$field = $field + array(
					'system'    => 1,
					'modelid'   => $data['id'],
					'listorder' => $listorder,
				);

				$this->field->insert($field);

				$listorder++;
			}

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
		// 默认会员模型禁止删除
		if ( $id == 'member' ) return $this->error(t('禁止删除系统默认模型'));

		// 用户组下有用户禁止删除
		if ( m('system.user')->where('modelid',$id)->count() ) return $this->error(t('该模型下尚有会员，不能被删除'));

		// 获取模型信息
		$data = $this->get($id);

		if ( parent::delete($id) )
		{
			// 删除模型表
			$this->db->dropTable($data['tablename']);

			// 删除用户及用户组中的相关数据
			m('member.group')->deletebymodelid($id);
			m('member.group')->cache(true);
			m('member.field')->deletebymodelid($id);
			m('member.field')->cache(true);

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
		$cache = zotop::cache("member.model");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->select();

			zotop::cache("member.model", $cache, false);
		}

		return $cache;
	}
}
?>