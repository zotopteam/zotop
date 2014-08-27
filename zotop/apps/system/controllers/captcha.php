<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 页面控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_captcha extends controller
{
    /**
     * 生成验证码
     *
     * @return void
     */
    public function action_index()
    {
		$captcha = new captcha();

		@extract($_GET);

		//长度
		if (isset($length) && intval($length)) $captcha->length = intval($length);
		if ($captcha->length > 8 || $captcha->length < 2)
		{
			$captcha->length = 4;
		}

		//字体大小
		if (isset($font_size) && intval($font_size)) $captcha->font_size = intval($font_size);

		//宽度
		if (isset($width) && intval($width)) $captcha->width = intval($width);
		if ($captcha->width <= 0)
		{
			$captcha->width = 130;
		}

		//高度
		if (isset($height) && intval($height)) $captcha->height = intval($height);
		if ($captcha->height <= 0) {
			$captcha->height = 50;
		}

		//字体颜色
		if (isset($font_color) && trim(urldecode($font_color)) && preg_match('/(^#[a-z0-9]{6}$)/im', trim(urldecode($font_color))))
		{
			$captcha->font_color = trim(urldecode($font_color));
		}

		//背景
		if (isset($background) && trim(urldecode($background)) && preg_match('/(^#[a-z0-9]{6}$)/im', trim(urldecode($background))))
		{
			$captcha->background = trim(urldecode($background));
		}

		$captcha->create();
    }

    /**
     * 检查验证码是否正确
     *
     * @return bool
     */
	public function action_check()
	{
		$name = $_GET['name'] ? $_GET['name'] : 'captcha';

		if( captcha::check($_GET[$name]) )
		{
			exit('true');
		}

		exit('"'.t('验证码错误，请重试').'"');
	}
}
?>