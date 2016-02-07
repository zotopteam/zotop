<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 开发助手
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class developer_controller_make extends admin_controller
{
	private $dir;
	private $app;
	private $app_path;
	private $app_file;

	public function __init()
	{
		parent::__init();

		$this->dir      = zotop::cookie('project_dir');
		$this->app_path = ZOTOP_PATH_APPS . DS . $this->dir;
		$this->app_file = $this->app_path . DS .'app.php';
		$this->app      = @include(ZOTOP_PATH_APPS . DS . $this->dir . DS .'app.php');

		$this->assign('dir',$this->dir);
		$this->assign('app',$this->app);
	}

	/**
	 * 配置中的数据库
	 *
	 */
	public function action_index()
    {
		$this->assign('title',t('开发助手'));
		$this->display();
	}

	/**
	 * 检查控制器、模型、模板是否存在
	 * @return [type] [description]
	 */
	public function action_check($type)
	{
        switch ($type) {
        	case 'controller':
        		$path = $this->app_path . DS .'controllers';
        		break;        	
        	case 'model':
        		$path = $this->app_path . DS .'models';
        		break;
        	case 'template':
        		$path = $this->app_path . DS .'templates';
        		break;
        	case 'class':
        		$path = $this->app_path . DS .'librairies';
        		break;          		        		
        }

        $file = $path . DS . $_GET['name'].'.php';

        if ( file::exists($file) )
        {
            exit('"'.t('已经存在，请重新输入').'"');
        }

        exit('true');		
	}

	/**
	 * 创建控制器
	 * 
	 * @return mixed
	 */
	public function action_controller()
	{
		$path = $this->app_path . DS .'controllers';

		if ( $post = $this->post() )
		{
			if ( file::exists($path. DS . $post['name'] .'.php') )
			{
				return $this->error(t('已经存在'));
			}

			if ( developer::make_file($post['source'], $path. DS . $post['name'] .'.php', $this->app, $post) )
			{
				return $this->success(t('创建成功'));
			}
			
			return $this->error(t('创建失败'));
		}

		$this->assign('title',t('创建控制器'));
		$this->assign('path',$path);
		$this->display();
	}

	/**
	 * 创建模型
	 * 
	 * @return mixed
	 */
	public function action_model()
	{
		$this->assign('title',t('创建模型'));
		$this->assign('projects',$projects);
		$this->display();
	}

	/**
	 * 创建模板
	 * 
	 * @return mixed
	 */
	public function action_template()
	{
		$this->assign('title',t('创建模板'));
		$this->assign('projects',$projects);
		$this->display();
	}		
}
?>