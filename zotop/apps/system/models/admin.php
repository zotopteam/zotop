<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 管理员模型
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_admin extends model
{
	protected $pk    = 'id';
	protected $table = 'admin';


	/**
	 * 登录
	 *
	 * @param array $data 用户名，密码等信息
	 * @return bool
	 */
	public function login(array $data)
	{
		// 登录锁定
		$maxfailed = c('system.login_maxfailed');
		$locktime  = c('system.login_locktime'); //单位分钟

		if ( $maxfailed and $locktime )
		{
			$log = m('system.log');
			$log->where('state','error')->where('app','system')->where('controller','login')->where('username',$data['username']);
			$log->where('createip',request::ip())->where('createtime','>',ZOTOP_TIME - $locktime * 60);

			if ( $log->count() >= $maxfailed )
			{
				return $this->error(t('登录失败次数过多，已被系统锁定，请$1分钟后再试', $locktime));
			}
		}

		// 用户登录
		$user = m('system.user');

		if ( $u = $user->login($data) )
		{
			$admin = $this->getbyid($u['id']);

			if ( empty($admin) or $u['modelid'] != 'admin' )
			{
				return $this->error(t('对不起，您不是管理员', $u['username']));
			}

			zotop::run('admin.login', array_merge($u,$admin));

			return array_merge($u,$admin);
		}

		return $this->error($user->error());
	}

	/**
	 * 系统用户登出
	 *
	 * @return bool
	 */
	public function logout()
	{
		if ( m('system.user')->logout() )
		{
			zotop::run('admin.logout');
			return true;
		}

		return false;
	}

	/**
	 * 编辑管理员信息
	 *
	 * @param  array $post 管理员信息
	 * @param  int $id   管理员编号
	 * @return bool
	 */
	public function edit($post, $id)
	{

	}
}
?>