<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 设置控制器
 *
 * @package		guestbook
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class guestbook_controller_config extends admin_controller
{
	/**
	 * 系统设置
	 *
	 */
	public function action_index($action='base')
    {
		$this->assign('title',A('guestbook.name'));
		$this->display("guestbook/config_{$action}.php");
    }

	/*
	 * 保存设置
	 *
	 * @return array
	 */
	public function action_save()
	{
		if ( $post = $this->post() )
		{
			if ( is_array($post) and !empty($post) )
			{
				if ( m('system.app')->config($post) )
				{
					return $this->success(t('保存成功'));
				}

				return $this->error(m('system.app')->error());
			}

			// 如果没有传入任何值
			return $this->error(t('参数错误'));
		}
	}
}
?>