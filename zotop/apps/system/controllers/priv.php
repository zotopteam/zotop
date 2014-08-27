<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 权限
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_priv extends admin_controller
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
	 * 权限
	 *
	 */
	public function action_index($id='')
	{
		$dataset = $this->priv->getTree();

		$this->assign('title',t('权限管理'));
		$this->assign('dataset',$dataset);
		$this->assign('id',$id);
		$this->display();
	}

 	/**
	 * 添加权限
	 *
	 */
	public function action_add($id='')
	{
		if ( $post = $this->post() )
		{
			if ( $this->priv->add($post) )
			{
				return $this->success(t('%s成功',t('添加')),u('system/priv/index/'.$id));
			}

			return $this->error($this->priv->error());
		}

		if ( !empty($id) and $parent = $this->priv->getbyid($id) )
		{
			$data['parentid'] = $parent['id'];
			$data['app'] = $parent['app'];
			$data['controller'] = $parent['controller'];
		}

		$this->assign('title',t('添加权限'));
		$this->assign('data',$data);
		$this->display();
	}

 	/**
	 * 编辑权限
	 *
	 */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->priv->edit($post,$id) )
			{
				return $this->success(t('%s成功',t('编辑')),u('system/priv/index/'.$id));
			}

			return $this->error($this->priv->error());
		}

		$data = $this->priv->getbyid($id);

		$this->assign('title',t('%s权限',t('编辑')));
		$this->assign('data',$data);
		$this->display();
	}

 	/**
	 * 删除权限
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->priv->where('parentid',$id)->count() ) return $this->error(t('该权限下尚有子权限，不能被删除'));

		if ( $this->priv->delete($id) )
		{
			return $this->success(t('删除成功'),u('system/priv/index'));
		}
		return $this->error($this->priv->error());
	}
}
?>