<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * wechat 应用设置控制器
 *
 * @package		wechat
 * @author		zotop hankx_chen@qq.com
 * @copyright	zotop
 * @license		http://www.zotop.com
 */
class wechat_controller_config extends admin_controller
{
	/**
	 * 应用设置
	 * 
	 * @return mixed
	 */
	public function action_index()
    {
		if ( $post = $this->post() )
		{
			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
		}

		$this->assign('title',t('微信设置'));
		$this->display();
    }
}
?>