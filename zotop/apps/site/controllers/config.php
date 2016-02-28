<?php
defined('ZOTOP') OR die('No direct access allowed.');

/**
 * 应用设置
 *
 * @package		site
 * @author		zotop hankx_chen@qq.com
 * @copyright	copyright zotop
 * @license		http://www.zotop.com
 */
class site_controller_config extends admin_controller
{
	/**
	 * 应用设置
	 * 
	 * @return mixed
	 */
	public function action_index()
    {
    	if( $post = $this->post() )
    	{
			$post['url'] = trim($post['url'],'/');

			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
    	}

		// 获取全部主题
		$folders = folder::folders(ZOTOP_PATH_THEMES);

		foreach( (array) $folders as $folder )
		{
			$theme = ZOTOP_PATH_THEMES.DS.$folder.DS.'theme.php';

			if ( file::exists($theme) )
			{
				$theme = @include($theme);

				if ( is_array($theme) and $theme['type'] != 'mobile' )
				{
					$themes[$folder] = $theme;
					$themes[$folder]['id'] = $folder;
					foreach ( array( 'png', 'gif', 'jpg', 'jpeg' ) as $ext )
					{
						if ( file::exists(ZOTOP_PATH_THEMES.DS.$folder.DS.'theme.'.$ext) )
						{
							$themes[$folder]['image'] = format::url(ZOTOP_URL_THEMES.'/'.$folder.'/theme.'.$ext);
							break;
						}
					}
				}
			}
		}

 		$this->assign('title',t('站点设置'));
		$this->assign('themes',$themes);
		$this->display();
    }

	/**
	 * 联系方式
	 * 
	 * @return mixed
	 */
	public function action_contact()
    {
		if ( $post = $this->post() )
		{
			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
		}

		$this->assign('title',t('联系方式'));
		$this->display();
    }

	/**
	 * 联系方式
	 * 
	 * @return mixed
	 */
	public function action_search()
    {
		if ( $post = $this->post() )
		{
			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
		}

		$this->assign('title',t('搜索优化'));
		$this->display();
    }    

	/**
	 * 网站状态
	 * 
	 * @return mixed
	 */
	public function action_state()
    {
		if ( $post = $this->post() )
		{
			if ( m('system.app')->config($post) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(m('system.app')->error());
		}

		$this->assign('title',t('网站状态'));
		$this->display();
    }        
}
?>