<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 管理员
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_administrator extends admin_controller
{

	protected $user;
	protected $admin;
	protected $role;
	protected $userid;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->user   = m('system.user');
		$this->admin  = m('system.admin');
		$this->role   = m('system.role');
		
		$this->userid = zotop::user('id');
	}


 	/**
	 * 管理员管理
	 *
	 */
	public function action_index()
	{
		$data = $this->admin->alias('a')->join('user as u','u.id','a.id')->field('u.*,a.*')->orderby('u.logintime','desc')->select();

		$roles = $this->role->select();

		$this->assign('title',t('管理员设置'));
		$this->assign('data',$data);
		$this->assign('roles',$roles);
		$this->display();
	}

    /**
     * 设置状态，禁用或者启用
     *
	 * @param string $id 应用标识(ID)
     * @return void
     */
	public function action_disabled($id, $disabled)
	{
		if ( $this->user->disabled($id, $disabled) )
		{
			return $this->success(t('操作成功'),u('system/administrator'));
		}
		return $this->error($this->user->error());
	}

    /**
     * 检查用户名、邮箱是否被占用
     *
     * @return bool
     */
	public function action_check($key,$ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->user->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->user->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}

	/**
	 * 添加管理员
	 *
	 */
	public function action_add()
	{
		if ( $post = $this->post() )
		{
			$post['modelid'] = 'admin';

			if ( $insertid = $this->user->add($post) )
			{
				$post['id'] = $insertid;

				if ( $this->admin->data($post)->insert() )
				{
					return $this->success(t('保存成功'),u('system/administrator'));
				}
				return $this->error($this->admin->error());
			}

			return $this->error($this->user->error());
		}

		$data = array('groupid'=>1);

		$roles = $this->role->getOptions(true);

		$this->assign('title',t('添加管理员'));
		$this->assign('roles',$roles);
		$this->assign('data',$data);
		$this->display('system/administrator_post.php');
	}

 	/**
	 * 编辑管理员
	 *
	 */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->user->edit($post,$id) )
			{
				if ( $this->admin->where('id',$id)->data($post)->update() )
				{
					return $this->success(t('保存成功'),u('system/administrator'));
				}
				return $this->error($this->admin->error());
			}
			return $this->error($this->user->error());
		}
		
		$datauser  = $this->user->getbyid($id);
		$dataadmin = $this->admin->getbyid($id);
		$roles     = $this->role->getOptions(true);

		$this->assign('title',t('编辑管理员'));
		$this->assign('roles',$roles);
		$this->assign('data',array_merge($datauser, $dataadmin));
		$this->display('system/administrator_post.php');
	}

 	/**
	 * 删除管理员
	 *
	 */
	public function action_delete($id)
	{
		if ( $id == 1 ) return $this->error(t('不能被删除'));

		if ( $this->user->delete($id) )
		{
			if ( $this->admin->delete($id) )
			{
				return $this->success(t('删除成功'),u('system/administrator'));
			}
			return $this->error($this->admin->error());
		}
		return $this->error($this->user->error());
	}
}
?>