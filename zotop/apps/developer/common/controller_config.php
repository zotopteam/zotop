<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * [id] 应用设置控制器
 *
 * @package		[id]
 * @author		[author] [email]
 * @copyright	[author]
 * @license		[homepage]
 */
class [id]_controller_config extends admin_controller
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

		$this->assign('title',t('[name]设置'));
		$this->display();
    }
}
?>