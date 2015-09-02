<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 系统登录控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_login extends admin_controller
{
	private $admin;

	public function __init()
	{
		parent::__init();

		$this->admin = m('system.admin');
	}

	public function __check()
	{
		//绑定后台访问地址，禁止其他访问地址，例如：admin.domain.com，绑定后，只能通过该域名登陆
		if( c('system.admin_url') && $_SERVER["SERVER_NAME"] != c('system.admin_url') )
		{
			header("http/1.1 403 Forbidden");
			exit('No direct access allowed.');
		}
				
		return true;
	}

	/**
	 * 系统登录 00ba7ceab606427071d5d755ea99e976
	 *
	 * @package		system
	 * @author		zotop team
	 * @copyright	(c)2009-2011 zotop team
	 * @license		http://zotop.com/license.html
	 */
	public function action_index()
	{
		if ( $post = $this->post() )
		{
			if ( c('system.login_captcha') and !captcha::check() )
			{
				return $this->error(t('验证码错误，请重试'));
			}

			if ( $this->admin->login($post) )
			{
				//记录登录用户名
				if ( $post['remember'] )
				{
					zotop::cookie('remember_username', $post['username'], intval($post['remember'])*24*60*60);//记录用户名一个月
				}
				else
				{
					zotop::cookie('remember_username',null);//清除记录
				}

				return $this->success(t('登陆成功，页面跳转中……'.$post['remember']), u('system/admin'));
			}

			return $this->error($this->admin->error());
		}
		$this->assign('title',t('网站管理登录'));
		$this->assign('remember_username',zotop::cookie('remember_username'));
		$this->display();
	}

    /**
     * 系统登出
     *
     * @param array|string $form 表单参数
     * @return void
     */
	public function action_logout()
	{
		$admin = m('system.admin');

		if ( $this->admin->logout() )
		{
			return $this->success(t('登出成功'), u('system/login'),1);
		}

		return $this->error(t('操作失败'));
	}

	/**
     * 桌面快捷方式
     *
     * @return void
     */
	public function action_shortcut()
	{
		$url 		= u('system/login','',true);
		$title 		= t('网站管理登录');
		$shortcut 	= "[InternetShortcut]\nURL=".$url."\nIDList=\n[{000214A0-0000-0000-C000-000000000046}]\nProp3=19,2";
		
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".urlencode($title).".url;");
		echo $shortcut;
	}
}
?>