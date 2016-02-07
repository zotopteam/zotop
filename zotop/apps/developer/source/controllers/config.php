<?php
defined('ZOTOP') OR die('No direct access allowed.');

/**
 * 应用设置
 *
 * @package		[app.id]
 * @author		[app.author] [app.email]
 * @copyright	copyright [app.author]
 * @license		[app.homepage]
 */
class [app.id]_controller_config extends admin_controller
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

		$this->assign('title',t('[app.name]设置'));
		$this->display();
    }
}
?>