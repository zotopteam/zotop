<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 管理员、角色、权限
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_role extends admin_controller
{

	protected $user;
	protected $admin;
	protected $role;
	protected $priv;
	protected $rolepriv;
	protected $userid;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->user = m('system.user');
		$this->admin = m('system.admin');
		$this->role = m('system.role');
		$this->priv = m('system.priv');
		$this->rolepriv = m('system.rolepriv');
		$this->userid = zotop::user('id');
	}

 	/**
	 * 角色
	 *
	 */
	public function action_index()
	{
		$dataset = $this->role->getall();

		foreach( $dataset as &$data )
		{
			$data['count'] = $this->user->where('groupid',$data['id'])->count();
		}

		$this->assign('title',t('角色管理'));
		$this->assign('dataset',$dataset);
		$this->display();
	}

 	/**
	 * 新建角色
	 *
	 */
	public function action_add()
	{
		if ( $post = $this->post() )
		{
			if ( $this->role->add($post) )
			{
				return $this->success(t('保存成功'),u('system/role'));
			}

			return $this->error($this->role->error());
		}
		$this->assign('title',t('添加角色'));
		$this->assign('data',$data);
		$this->display('system/role_post.php');
	}

 	/**
	 * 编辑角色
	 *
	 */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->role->edit($post,$id) )
			{
				return $this->success(t('保存成功'),u('system/role'));
			}

			return $this->error($this->role->error());
		}

		$data = $this->role->getbyid($id);

		$this->assign('title',t('编辑角色'));
		$this->assign('data',$data);
		$this->display('system/role_post.php');
	}

 	/**
	 * 角色对应权限设置
	 *
	 */
	public function action_priv($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->rolepriv->set($id, $post['id']) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error($this->rolepriv->error());
		}

		// 获取全部权限数据
		$dataset = $this->priv->getTree();

		// 获取角色数据
		$role = $this->role->getbyid($id);

		// 获取角色的全部权限
		$privs = $this->rolepriv->where('groupid',$id)->getall();
		$privs = arr::column($privs,'privid');

		foreach( $privs as $priv )
		{
			foreach ( $dataset as &$data )
			{
				if ( $data['id'] == $priv )
				{
					$data['status'] = 'checked="checked"';
				}
				elseif ( stripos($data['id'],$priv) !== false )
				{
					$data['status'] = 'disabled="disabled"';
				}
			}
		}

		$this->assign('title',t('权限设置'));
		$this->assign('dataset',$dataset);
		$this->assign('role',$role);
		$this->assign('privs',$privs);
		$this->display();
	}

 	/**
	 * 删除角色
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->role->delete($id) )
		{
			return $this->success(t('删除成功'),u('system/role'));
		}
		return $this->error($this->role->error());
	}
}
?>