<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * shop 设置控制器
 *
 * @package		shop
 * @author		zotop team hankx_chen@qq.com
 * @copyright	zotop team
 * @license		http://www.zotop.com
 */
class shop_controller_config extends admin_controller
{
	/**
	 * 设置
	 *
	 */
	public function action_index()
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

			return $this->error(t('参数错误'));
		}

		$this->assign('title',t('商城'));
		$this->display();
    }
}
?>