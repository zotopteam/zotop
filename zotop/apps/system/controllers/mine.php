<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 个人管理
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_mine extends admin_controller
{
	protected $user;
	protected $admin;
	protected $userid;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->user 	= m('system.user');
		$this->admin 	= m('system.admin');
		$this->userid 	= zotop::user('id');
	}

 	/**
	 * 编辑我的资料
	 *
	 */
	public function action_index()
	{
		if ( $post = $this->post() )
		{
			if ( $this->user->update($post,$this->userid) )
			{
				if ( $this->admin->update($post,$this->userid) )
				{
					return $this->success(t('%s成功',t('保存')));
				}
				return $this->success($this->admin->error());
			}

			return $this->success($this->user->error());
		}

		$datauser = $this->user->getbyid($this->userid);
		$dataadmin = $this->admin->getbyid($this->userid);

		$this->assign('title',t('编辑我的资料'));
		$this->assign('data',array_merge($datauser,$dataadmin));
		$this->display();
	}

 	/**
	 * 修改我的密码
	 *
	 */
	public function action_password()
	{
		if ( $post = $this->post() )
		{
			if ( $this->user->where('id',$this->userid)->getField('password') != $this->user->password($post['password']) )
			{
				return $this->error(t('原密码错误，请重新输入'));
			}

			if ( empty($post['newpassword']) or $post['newpassword'] != $post['newpassword2'] )
			{
				return $this->error(t('新密码不能为空，且两次输入的必须一致'));
			}

			if ( strlen($post['newpassword']) < 6 or strlen($post['newpassword']) > 20 )
			{
				return $this->error(t('密码长度必须在6-20位之间'));
			}

			// 加密
			$post['newpassword'] = $this->user->password($post['newpassword']);

			// 更新密码
			if ( $this->user->update( array('password'=>$post['newpassword']), $this->userid) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error($this->user->error());
		}

		$datauser = $this->user->getbyid($this->userid);
		$dataadmin = $this->admin->getbyid($this->userid);

		$this->assign('title',t('修改我的密码'));
		$this->assign('data',array_merge($datauser,$dataadmin));
		$this->display();
	}

 	/**
	 * 查看我的权限
	 *
	 */
	public function action_priv()
	{
		$groupid = zotop::user('groupid');

		// 获取角色数据
		$role = m('system.role')->getbyid($groupid);

		// 获取全部权限数据
		$dataset = m('system.priv')->getTree();

		// 获取角色的全部权限
		$privs = m('system.rolepriv')->where('groupid',$groupid)->getall();
		$privs = arr::column($privs,'privid');

		foreach( $privs as $priv )
		{
			foreach ( $dataset as &$data )
			{
				if ( $data['id'] == $priv or stripos($data['id'], $priv) !== false )
				{
					$data['status'] = true;
				}
			}
		}

		$this->assign('title',t('查看我的权限'));
		$this->assign('groupid',$groupid);
		$this->assign('role',$role);
		$this->assign('dataset',$dataset);
		$this->assign('privs',$privs);
		$this->display();
	}

 	/**
	 * 查看我的日志
	 *
	 */
	public function action_log()
	{
		// 获取全部数据
		$dataset = m('system.log')->where('username',zotop::user('username'))->orderby('createtime','desc')->getPage(0,20);

		$this->assign('title',t('查看我的日志'));
		$this->assign($dataset);
		$this->display();
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
     * 检查原密码是否正确
     *
     * @return bool
     */
	public function action_checkpassword()
	{
		$password = $this->user->where('id',$this->userid)->getField('password');

		if ( $this->user->password($_GET['password']) == $password )
		{
			echo 'true';
		}
		else
		{
			echo '"'.t('原密码错误，请重新输入').'"';
		}
		exit();
	}
}
?>