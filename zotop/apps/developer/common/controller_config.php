<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * [id] 设置控制器
 *
 * @package		[id]
 * @author		[author] [email]
 * @copyright	[author]
 * @license		[homepage]
 */
class [id]_controller_config extends admin_controller
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

				return $this->error($app->error());
			}

			return $this->error(t('参数错误'));
		}

		$this->assign('title',t('[name]设置'));
		$this->display();
    }
}
?>