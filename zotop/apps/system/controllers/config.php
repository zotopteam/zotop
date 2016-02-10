<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 设置控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_config extends admin_controller
{

	public function navbar()
	{
		$navbar = array(
			'index'  => array('text'=>t('附件上传'),'href'=>u('system/config/upload'),'icon'=>'fa fa-upload'),
			'mail'   => array('text'=>t('邮件发送'),'href'=>u('system/config/mail'),'icon'=>'fa fa-envelope'),
			'cache'  => array('text'=>t('缓存设置'),'href'=>u('system/config/cache'),'icon'=>'fa fa-rocket'),
			'locale' => array('text'=>t('区域和语言'),'href'=>u('system/config/locale'),'icon'=>'fa fa-map'),
			'url'    => array('text'=>t('链接和路由'),'href'=>u('system/config/url'),'icon'=>'fa fa-link'),
			'safety' => array('text'=>t('系统安全'),'href'=>u('system/config/safety'),'icon'=>'fa fa-shield'),
		);

		$navbar = zotop::filter('system.config.navbar',$navbar);

		return $navbar;
	}

	/**
	 * 上传设置
	 */
    public function action_index()
    {
    	if( $post = $this->post() )
    	{
			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
    	}

 		$this->assign('title',t('附件上传'));
		$this->assign('navbar',$this->navbar());
		$this->display();
    }

	/**
	 * 邮件设置
	 */
    public function action_mail()
    {
    	if( $post = $this->post() )
    	{
			if ( zotop::cookie('mailvalid') != $post['mailvalid'] )
			{
				return $this->error(t('验证码错误'));
			}

			$post['mail'] = true;

			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
    	}

 		$this->assign('title',t('邮件发送'));
		$this->assign('navbar',$this->navbar());
		$this->display();
    }

 	/**
	 * 缓存设置
	 */
    public function action_cache()
    {
    	if( $post = $this->post() )
    	{
			// 检查缓存设置是否正确
			if ( isset($post['cache_driver']) and $this->checkcache($post) == false )
			{
				return $this->error(t('系统不支持 “%s” 缓存,请选择其它缓存方式', $post['cache_driver']));
			}

			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
    	}

 		$this->assign('title',t('缓存设置'));
		$this->assign('navbar',$this->navbar());
		$this->display();
    }

 	/**
	 * 区域和语言
	 */
    public function action_locale()
    {
    	if( $post = $this->post() )
    	{
			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
    	}

 		$this->assign('title',t('区域和语言'));
		$this->assign('navbar',$this->navbar());
		$this->display();
    }

  	/**
	 * 链接和路由
	 */
    public function action_url()
    {
    	if( $post = $this->post() )
    	{
			// 路由设置
			if ( isset($post['url_pattern']) and isset($post['url_route']) )
			{
				// 缓存数据
				$router = array_combine($post['url_pattern'], $post['url_route']);

				// 销毁额外数据
				unset($post['url_pattern'], $post['url_route']);

				// 存储路由规则
				zotop::data(ZOTOP_PATH_CONFIG.DS.'router.php', $router);

				// 销毁路由缓存
				zotop::cache('zotop.router', null);

				// 重启系统
				zotop::reboot();
			}

			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
    	}

 		$this->assign('title',t('链接和路由'));
		$this->assign('navbar',$this->navbar());
		$this->display();
    }

  	/**
	 * 系统安全
	 */
    public function action_safety()
    {
    	if( $post = $this->post() )
    	{
			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
    	}

 		$this->assign('title',t('系统安全'));
		$this->assign('navbar',$this->navbar());
		$this->display();
    }

	/*
	 * 邮件测试
	 *
	 * @return array
	 */
	public function action_mailvalid()
	{
		if ( $post = $this->post() )
		{
			if ( empty($post['sendto']) ) return $this->error(t('请输入接收验证邮箱'));

			//发送测试邮件
			if ( class_exists('mail') )
			{
				$mail = new mail();
				//name中的点符号会自动被转化为_
				$mail->mailer = $post['mail_mailer'];
				$mail->delimiter = $post['mail_delimiter'];
				$mail->sender = empty($post['mail_sender']) ? c('site.name') : $post['mail_sender'];
				$mail->from = $post['mail_from'];
				$mail->sign = $post['mail_sign'];
				$mail->smtp_host = $post['mail_smtp_host'];
				$mail->smtp_port = $post['mail_smtp_port'];
				$mail->smtp_auth = $post['mail_smtp_auth'];
				$mail->smtp_username = $post['mail_smtp_username'];
				$mail->smtp_password = $post['mail_smtp_password'];

				// 验证码
				$code = str::rand(4,0);

				//发送邮件给发件人，发送成功则自己会受到一封邮件
				if ( $mail->send($post['sendto'], t('邮件发送验证'), t('您好，邮件发送设置正确，验证码：<b>%s</b>', $code)) )
				{
					zotop::cookie('mailvalid', $code);

					return $this->success(t('请访问邮箱[%s]查看是否收到验证邮件和验证码',$post['sendto']),null,10);
				}

				return $this->error(t('发送失败，请检查您的设置').' : '.$mail->error());
			}

			return $this->error(t('系统不支持邮件发送功能'));
		}
	}

	/*
	 * 测试水印
	 *
	 * @return array
	 */
	public function action_testWatermark()
	{
		if ( $post = $this->post() )
		{
			$image = new image(ZOTOP_PATH_CMS.DS.'common'.DS.'test.jpg');
			$image->watermark(ZOTOP_PATH_CMS.DS.'common'.DS.$post['watermark_image'], $post['watermark_position'], $post['watermark_opacity']);
			$image->quality($post['watermark_quality']);
			$image->save(ZOTOP_PATH_CMS.DS.'common'.DS.'test-watermark.jpg');

			return $this->success(ZOTOP_URL_CMS.'/common/test-watermark.jpg');
		}
	}

	/**
	 * upload process
	 *
	 */
	public function action_uploadwatermark()
	{
		// 将文件上传到水印目录下
		$filepath = ZOTOP_PATH_CMS.DS.'common'.DS.'watermark.png';

		// 文件上传
		$upload = new plupload();
		$upload->allowexts = 'jpg,jpeg,png,gif';
		$upload->maxsize = 0;

		if ( $file = $upload->save($filepath) )
		{
			return $this->success(t('上传成功'));
		}

		return $this->error($upload->error);
	}

	/*
	 * 检查是否支持缓存驱动
	 *
	 * @return array
	 */
	protected function checkcache($post)
	{
		$support = true;
		$driver	= $post['cache_driver'];

		switch($driver)
		{
			case 'file':
				$support = true;
				break;
			case 'memcache':
				$support = (extension_loaded('memcache') && class_exists('Memcache'));
				break;
			case 'eaccelerator':
				$support = (extension_loaded('eaccelerator') && function_exists('eaccelerator_get'));
				break;
			case 'apc':
				$support = function_exists('apc_cache_info');
				break;
			default:
				$support = false;
				break;
		}

		return $support;
	}
}
?>