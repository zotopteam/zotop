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
class test_controller_index extends admin_controller
{
	public function navlist()
	{
		return array(
			'test_dialog' => array(
				'text'=>t('对话框测试'),
				'href'=>u('test/index/dialog'),
				'icon'=>zotop::app('test.url').'/icons/dialog.png',
				'description'=>t('测试网页对话框展现及兼容性')
			),
			'test_url' => array(
				'text'=>t('URL测试'),
				'href'=>u('test/index/url'),
				'icon'=>zotop::app('test.url').'/icons/url.png',
				'description'=>t('测试URL的组合及路由')
			),
			'test_image' => array(
				'text'=>t('图片驱动测试'),
				'href'=>u('test/index/image'),
				'icon'=>zotop::app('test.url').'/icons/tree.png',
				'description'=>t('图片驱动测试')
			),
			'test_upload' => array(
				'text'=>t('上传测试'),
				'href'=>u('test/index/upload'),
				'icon'=>zotop::app('test.url').'/icons/tree.png',
				'description'=>t('测试上传程序')
			),
			'test_dialog6' => array(
				'text'=>t('对话框v6测试'),
				'href'=>u('test/index/dialog6'),
				'icon'=>zotop::app('test.url').'/icons/dialog.png',
				'description'=>t('测试网页对话框展现及兼容性')
			),
		);
	}

    public function action_index($action="index")
    {
		$this->assign('title','单元测试');
		$this->assign('navlist',$this->navlist());
		$this->display("test/{$action}.php");
    }

	/**
	 * treeview test
	 *
	 */
    public function action_Tree()
    {
		$tree = array(
			array('id'=>'system_setting','parentid'=>'system','title'=>'系统设置','icon'=>'settings'),

			array('id'=>'system','parentid'=>'root','title'=>'控制中心','icon'=>'system'),
			array('id'=>'system_app','parentid'=>'system','title'=>'应用管理','icon'=>'app'),
			array('id'=>'system_user','parentid'=>'system','title'=>'用户管理','icon'=>'user'),
			array('id'=>'system_usergroup','parentid'=>'system_user','title'=>'用户组管理','icon'=>'usergroup'),

			array('id'=>'file','parentid'=>'root','title'=>'文件管理','icon'=>'file'),
			array('id'=>'file_img','parentid'=>'file','title'=>'图片','icon'=>'img'),
			array('id'=>'file_doc','parentid'=>'file','title'=>'文档','icon'=>'doc'),

			array('id'=>'system_useradd','parentid'=>'system_user','title'=>'添加用户','icon'=>'add'),



		);

		for($i=0; $i<50; $i++)
		{
			$tree[] = array('id'=>'system_user'.$i,'parentid'=>'system','title'=>'用户管理','icon'=>'user');
			$tree[] = array('id'=>'file_doc'.$i,'parentid'=>'file','title'=>'文档','icon'=>'doc');
		}

		$this->assign('title',t('树形测试'));
		$this->assign('navlist',$this->navlist());
		$this->assign('tree',$tree);
		$this->display();
    }

	/**
	 * dialog test
	 *
	 */
	public function action_dialog()
	{
		$this->assign('title','对话框测试');
		$this->assign('navlist',$this->navlist());
		$this->display();
	}

	/**
	 * dialog open page
	 *
	 */
	public function action_dialogOpen()
	{
		$this->assign('title','对话框测试');
		$this->display();
	}
	/**
	 * dialog open page
	 *
	 */
	public function action_dialogOpen2()
	{
		$this->assign('title','对话框测试');
		$this->display();
	}

	/**
	 * dialog ajax page
	 *
	 */
	public function action_dialogLoad()
	{
		$this->assign('title','对话框测试');
		$this->display();
	}

	/**
	 * dialog confirm page
	 *
	 */
	public function action_confirm()
	{
		$this->success(t('操作成功，正在返回测试单元首页'),u('test/index'));
	}

	/**
	 * dialog test
	 *
	 */
	public function action_url()
	{
		$this->assign('title','URL测试');
		$this->assign('navlist',$this->navlist());
		$this->display();
	}

	/**
	 * dialog test
	 *
	 */
	public function action_image()
	{
		$file = A('test.path').DS.'common'.DS.'test.jpg';
		$file = '/apps/test/common/test.jpg';
		$url = A('test.url').'/common/test.jpg';
		$result = A('test.url').'/common/test1.jpg';

		$info = image::info($file);

		image::factory($file)->watermark('/uploads/watermark.png','bottom right',60,2,2)->save('%dir/%name1.%ext');

		$this->assign('title','图像处理驱动');
		$this->assign('navlist',$this->navlist());
		$this->assign('file',$file);
		$this->assign('url',$url);
		$this->assign('result',$result);
		$this->assign('info',$info);
		$this->display();
	}

	/**
	 * upload
	 *
	 */
	public function action_upload()
	{
		$this->assign('title','上传测试');
		$this->assign('navlist',$this->navlist());
		$this->display();
	}

	/**
	 * upload process
	 *
	 */
	public function action_uploadProcess()
	{
		// 强制声明为AJAX状态
		$_REQUEST['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

		// 用于测试进度
		usleep(50000);

		$plupload = new plupload();
		$plupload->savepath = ZOTOP_PATH_UPLOADS.DS.'test';
		$plupload->allowexts = 'jpg,jpeg,png,gif';

		if (  $file = $plupload->save() )
		{
			$this->success(t('上传成功').' '.$file);
		}

		return $this->error($plupload->error);
	}

	/**
	 * upload
	 *
	 */
	public function action_office()
	{
		$this->assign('title','上传office测试');
		$this->assign('navlist',$this->navlist());
		$this->display();
	}
}
?>