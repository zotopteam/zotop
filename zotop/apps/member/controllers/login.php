<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员 登录控制器
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class member_controller_login extends site_controller
{
	private $member;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->member = m('member.member');
	}

	/**
	 * 会员登录
	 *
	 */
	public function action_index()
    {
		if ( $post = $this->post() )
		{
			if ( c('member.login_captcha') and !captcha::check() )
			{
				return $this->error(t('验证码错误，请重试'));
			}

			if ( $this->member->login($post) )
			{
				zotop::cookie('member.username',$post['username'], 365*24*3600);
				zotop::cookie('cookietime',$post['cookietime'], 365*24*3600);

				return $this->success(t('登陆成功，页面跳转中……'), u('member/index'));
			}

			return $this->error($this->member->error());
		}

		$models = m('member.model.cache');

		foreach( $models as $i=>$m )
		{
			if ( !$m['settings']['register'] or $m['disabled'] ) unset($models[$i]);
		}

		$this->assign('title',t('会员登录'));
		$this->assign('models',$models);
		$this->display('member/login.php');
	}

	/**
	 * 迷你登录条
	 *
	 */
	public function action_bar()
    {
		$this->display('member/loginbar.php');
	}

	/**
	 * 登出
	 *
	 */
	public function action_loginout()
    {
		if ( $this->member->logout() )
		{
			return $this->success(t('登出成功'), request::referer(), 1);
		}

		return $this->error(t('操作失败'));
	}

	/**
	 * 忘记密码
	 *
	 */
	public function action_getpassword()
    {
		$step = 1;

		if ( $post = $this->post() )
		{
			if ( $post['username'] and $post['captcha'] )
			{
				if ( !captcha::check() ) return $this->error(t('验证码错误，请重试'));

				$user = m('system.user')->where(array('username','=',$post['username']),'or', array('email','=',$post['username']))->getRow();

				if( empty($user) )
				{
					return $this->error(t('账号不存在'));
				}

				if ( !$this->sendMail($user['email']) )
				{
					return $this->error(t('邮件发送失败，请联系网站管理员'));
				}

				$step = 2;
			}

			if ( $post['email'] and $post['code'] )
			{
				if ( $post['code'] != zotop::cache(md5($post['email']))  )
				{
					return $this->error(t('邮件验证码错误，请重试'));
				}

				$step = 3;
			}

			if ( $post['id'] and $post['email'] and $post['code'] and $post['password'] and $post['password2'] )
			{

				if (  $post['password'] != $post['password2']  )
				{
					return $this->error(t('两次输入密码不一致'));
				}

				$post['password'] = m('system.user.password', $post['password']);

				if ( !m('system.user')->data('password', $post['password'])->where('id', $post['id'])->update() )
				{
					return $this->error(m('system.user')->error());
				}

				$step = 4;

			}
		}

		$this->assign('title',t('找回密码'));
		$this->assign('step',$step);
		$this->assign('user',$user);
		$this->display('member/getpassword.php');
	}

	/**
	 * JSON方式验证账户是否存在
	 *
	 */
	public function action_checkuser()
    {
		if ( m('system.user')->where(array('username','=',$_GET['username']),'or', array('email','=',$_GET['username']))->exists() )
		{
			exit('true');
		}

		exit('"'.t('账号不存在').'"');
	}


	/**
	 * 发送邮件验证码
	 *
	 */
	protected function sendMail($to, $data=array())
	{
		if ( $to and intval(C('system.mail')) )
		{
			// 邮件验证码
			$data['code'] = str::rand(6);

			// 存储验证码
			zotop::cache(md5($to), $data['code'], intval(c('member.getpasswordmail_expire'))*3600);

			//解析邮件内容
			$title	 = $this->parseMail(c('member.getpasswordmail_title'),$data);
			$content = $this->parseMail(c('member.getpasswordmail_content'),$data);

			$mail = new mail();
			$mail->sender = c('site.name');
			$mail->send($to, $title, $content);

			return true;
		}

		return false;
	}

    /**
     * 解析邮件内容,支持{site} {code} 等参数以及自定义的用户参数
     *
     */
	protected function parseMail($str, $data)
	{
		$str = zotop::filter('member.parsemail', $str, $data);

		$str = str_replace('{sitename}',' '.c('site.name').' ',$str);

		foreach ( $data as $key=>$val )
		{
			$str = str_replace('{'.$key.'}', $val ,$str);
		}

		return $str;
	}
}
?>