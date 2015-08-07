<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * mobile 应用设置控制器
 *
 * @package		mobile
 * @author		zotop team hankx_chen@qq.com
 * @copyright	zotop team
 * @license		http://www.zotop.com
 */
class mobile_controller_config extends admin_controller
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

				if ( is_array($theme) and $theme['mobile'] )
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


		$this->assign('title',t('移动站点'));
		$this->assign('themes',$themes);
		$this->display();
    }
}
?>