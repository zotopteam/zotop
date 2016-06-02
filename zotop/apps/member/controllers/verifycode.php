<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员注册
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class member_controller_verifycode extends site_controller
{

    /**
     * 验证用户邮箱
     *
     * @return bool
     */
	public function action_sendemail()
	{
		$target = $_POST['target'];

		if ( empty($target) )
		{
			return $this->error(t('请输入邮箱地址'));
		}

		if ( !C('system.mail') )
		{
			return $this->error(t('系统尚未设置邮件发送功能，请联系管理员'));
		}

		if ( $verifycode = $this->sendMail($target) )
		{
			m('system.verifycode')->save($target, $verifycode);

			return $this->success(t('发送成功'));
		}

		return $this->error(t('发送失败，请重试'));		
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

			return $data['code'];
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