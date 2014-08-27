<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 管理系统应用
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_app extends admin_controller
{

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->app = m('system.app');

		$this->navbar = zotop::filter('system.app.navbar',array(
			'index' => array('text'=>t('已安装应用'),'href'=>u('system/app')),
			'uninstalled' => array('text'=>t('未安装应用'),'href'=>u('system/app/uninstalled')),
			'upload' => array('text'=>t('上传新应用'),'href'=>u('system/app/upload')),
			//'online' => array('text'=>t('在线安装'),'href'=>u('system/app/online')),
		));
	}

	/**
	 * 应用管理首页
	 *
	 */
    public function action_index()
    {
		if ( $post = $this->post() )
		{
			if ( $this->app->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->app->error());
		}


		$apps = $this->app->cache();
		$cores = $this->app->cores;

		$this->assign('title',t('应用管理'));
		$this->assign('navbar',$this->navbar);
		$this->assign('apps',$apps);
		$this->assign('cores',$cores);
		$this->display();
    }

    /**
     * 设置应用状态，禁用或者启用
     *
	 * @param string $id 应用标识(ID)
     * @return void
     */
	public function action_status($id)
	{
		if ( $this->app->status($id) )
		{
			return $this->success(t('操作成功'),u('system/app'));
		}
		return $this->error($this->app->error());
	}

    /**
     * 应用卸载
     *
     * @return void
     */
	public function action_uninstall($id)
    {


		if ( $post = $this->post() )
		{
			if ( $this->app->uninstall($id, $post) )
			{
				return $this->success(t('卸载成功'),u('system/app'));
			}

			return $this->error($this->app->error());
		}

		$data = $this->app->getbyid($id);

		// 2012-12-08 增加应用自定义卸载程序
		if ( file::exists(ZOTOP_PATH_APPS.DS.$data['dir'].DS.'templates'.DS.'app_uninstall.php') )
		{
			$template = ZOTOP_PATH_APPS.DS.$data['dir'].DS.'templates'.DS.'app_uninstall.php';
		}

		$this->assign('title',t('卸载'));
		$this->assign('id',$id);
		$this->assign('data',$data);
        $this->display($template);
	}

    /**
     * 未安装的应用
     *
     * @return void
     */
	public function action_uninstalled()
    {
		$apps = m('system.app')->getUninstalled();

		$this->assign('title',t('应用管理'));
		$this->assign('navbar',$this->navbar);
		$this->assign('apps',$apps);
        $this->display();
    }

    /**
     * 应用安装
     *
	 * @param string $dir 待安装应用的目录名称
     * @return void
     */
	public function action_install()
    {
		$dir = rawurldecode($_GET['dir']);

		if ( $post = $this->post() )
		{
			if ( $this->app->install($dir, $post) )
			{
				return $this->success(t('安装成功'),u('system/app'));
			}

			return $this->error($this->app->error());
		}

		$apps = $this->app->getUninstalled();

		// 2012-12-08 增加应用自定义安装程序
		if ( file::exists(ZOTOP_PATH_APPS.DS.$dir.DS.'templates'.DS.'app_install.php') )
		{
			$template = ZOTOP_PATH_APPS.DS.$dir.DS.'templates'.DS.'app_install.php';
		}

		$this->assign('title',t('安装'));
		$this->assign('dir',$dir);
		$this->assign('data',$apps[$dir]);
        $this->display($template);
    }

	/**
	 * 上传新应用
	 *
	 */
    public function action_upload()
    {
		$this->assign('title',t('应用管理'));
		$this->assign('navbar',$this->navbar);
		$this->display();
    }

	/**
	 * upload process
	 *
	 */
	public function action_uploadprocess()
	{
		// 强制声明为AJAX状态
		$_REQUEST['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

		// 将文件上传到应用目录下
		$filepath = ZOTOP_PATH_APPS.DS.$_POST['filename'];

		// 文件上传
		$upload = new plupload();
		$upload->allowexts = 'zip';
		$upload->maxsize = 0;

		if ( $file = $upload->save($filepath) )
		{
			// 解压文件
			if ( $dir = file::unzip($file, null, false) )
			{
				file::delete($file);

				if ( file::exists($dir.DS.'app.php') )
				{
					return $this->success(t('上传成功'),u('system/app/uninstalled'));
				}

				folder::delete($dir,true);
				file::delete($file);
				return $this->error(t('错误的安装包文件'));
			}

			file::delete($file);
			return $this->error(t('解压缩安装包文件失败'));
		}

		$this->error($upload->error);
	}

	/**
	 * 删除未安装应用
	 *
	 */
	public function action_delete()
	{
		$dir = trim(rawurldecode($_GET['dir']));

		if ( $dir  )
		{
			$path = ZOTOP_PATH_APPS.DS.$dir;

			if ( folder::delete($path,true) )
			{
				return $this->success(t('删除成功'),u('system/app/uninstalled'));
			}

			return $this->error(t('删除失败'));
		}

		return $this->error(t('禁止访问'));
	}
}
?>