<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 开发助手
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class developer_controller_project extends admin_controller
{
	protected $attrs;
	protected $dir; // 项目文件夹
	protected $app = array();
	protected $db;
	protected $navbar = array();

    /**
     * 重载__init函数
     */
    public function __init()
    {
        parent::__init();

		$this->attrs = array(
			'id'			=> t('标识'),
			'dir'			=> t('目录'),
			'name'			=> t('名称'),
			'type'			=> t('类型'),
			'description'	=> t('描述'),
			'version'		=> t('版本号'),
			'tables'		=> t('数据表'),
			'dependencies'	=> t('依赖'),
			'author'		=> t('作者'),
			'email'			=> t('电子邮箱'),
			'homepage'		=> t('网站'),
		);


		if ( ZOTOP_ACTION != 'add' )
		{
			// 获取工程信息
			if ( $_GET['dir'] )
			{
				zotop::cookie('project_dir', $_GET['dir'], 365*86400);
			}

			if ( $this->dir = zotop::cookie('project_dir') )
			{
				$this->app = @include(ZOTOP_PATH_APPS . DS . $this->dir . DS .'app.php');
			}

			if ( empty($this->app) or !is_array($this->app) )
			{
				return $this->error(t('未能在 {1} 目录下找到 app.php', debug::path(ZOTOP_PATH_APPS . DS . $this->dir)));
			}

			$this->assign('app',$this->app);
		}

		$this->assign('attrs',$this->attrs);

		$this->db = zotop::db();
    }



    /**
     * 项目页面
     *
     */
    public function action_index()
    {
        $this->assign('title', t('基本信息'));
        $this->display();
    }


    /**
     * 新建应用
     *
     */
    public function action_add()
    {
        if ($post = $this->post())
        {
			if ( $this->create_app($post) )
			{
				$this->success(t('创建成功'), U('developer/project',array('project'=>$post['dir'])));
			}

			return $this->error($this->error());
        }

        // 新建应用的配置
        $app = array('type'=>'module','version'=>'1.0');

        $this->assign('title', t('新建应用'));
        $this->assign('app', $app);
        $this->display('developer/project_post.php');
    }

    /**
     * 编辑应用 ,TODO 需要重改
     *
     */
    public function action_edit()
    {
		// 保存
        if ($post = $this->post())
        {
			// 重命名文件夹
			if ( $this->dir != $post['dir'] )
			{
				folder::rename(ZOTOP_PATH_APPS . DS . $this->dir, $post['dir']);
			}

			$app_path = ZOTOP_PATH_APPS . DS . $post['dir'];

			// 合并数据
			$post = $this->clear_setting(array_merge($this->app, $post));

			// 写入应用配置
			file::put($app_path . DS . 'app.php', "<?php\nreturn ".var_export($post, true).";\n?>");

			// 写入项目文件
			file::put($app_path . DS . '_project.php', "<?php\nreturn ".var_export($post, true).";\n?>");

			// 保存并返回
			return $this->success(t('保存成功'),U('developer/project'));
        }

        $this->assign('title', t('编辑应用'));
        $this->assign('app', $this->app);
		$this->assign('attrs',$this->attrs);
        $this->display('developer/project_post.php');
    }

    /**
     * 创建数据表
     *
     */
    public function action_config()
    {
		// 保存
        if ($post = $this->post())
        {
			
			$config_path = ZOTOP_PATH_APPS . DS . $this->dir . DS .'config.php';

			$config = is_array($post['config_key']) ? array_combine($post['config_key'], $post['config_val']) : array();

			// 写入应用配置
			file::put($config_path, "<?php\nreturn ".var_export($config, true).";\n?>");
			
			// 如果不存在配置控制器，写入配置控制器
			if ( !file::exists(ZOTOP_PATH_APPS . DS . $this->dir . DS. 'controllers' .DS .'config.php') )
			{
				$this->create_file('controller_config.php', ZOTOP_PATH_APPS . DS . $this->dir . DS . 'controllers' .DS. "config.php", $this->app);
			}

			// 写入配置模板，这里只能写入第一项
			$this->app['config_first'] = $post['config_key'][0];

			if ( !file::exists(ZOTOP_PATH_APPS . DS . $this->dir . DS. 'templates' .DS .'config_index.php') )
			{
				$this->create_file('template_config_index.php', ZOTOP_PATH_APPS . DS . $this->dir . DS . 'templates' .DS. "config_index.php", $this->app);
			}

			return $this->success(t('保存成功'));
		}

		$config = @include(ZOTOP_PATH_APPS . DS . $this->dir . DS .'config.php');
		$config = is_array($config) ? $config : array(''=>'');

        $this->assign('title', t('配置项管理'));
		$this->assign('config',$config);
        $this->display();			
	}

    /**
     * 项目页面
     *
     */
    public function action_table()
    {
		// 获取全部数据表
		$tables    = $this->db->tables();
		
		// 获取当前应用的数据表
		$apptables = explode(',',$this->app['tables']);

		// 获取属于模块的表属性
		foreach( $tables as $k=>$table )
		{
			if ( !in_array($k, $apptables) ) unset($tables[$k]);
		}

        $this->assign('title', t('数据表管理'));
		$this->assign('tables',$tables);
        $this->display();
    }	

    /**
     * 创建数据表
     *
     */
    public function action_createtable()
    {
		if ( $post = $this->post() )
		{
			$schema = array(
				'fields' => array(
					'id'        => array('type' => 'int', 'length' => 10, 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE),
				),
				'primary' => array('id'),
				'comment' => $post['comment'],
			);

			if (  $this->db->existsTable($post['name']) )
			{
				return $this->error(t('{1}已经存在', $post['name']));
			}

			if ( $this->db->createTable($post['name'], $schema) )
			{
				$app = $this->app;

				if ( empty($app['tables']) )
				{
					$app['tables'] = $post['name'];
				}
				elseif ( strpos(','.$app['tables'].',',  ','.$post['name'].',') === false )
				{
					$app['tables'] = $app['tables'].','.$post['name'];
				}

				// 写入应用配置
				file::put(ZOTOP_PATH_APPS . DS . $this->dir . DS . 'app.php', "<?php\nreturn ".var_export($app, true).";\n?>");

				// 写入项目文件
				file::put(ZOTOP_PATH_APPS . DS . $this->dir . DS . '_project.php', "<?php\nreturn ".var_export($app, true).";\n?>");


				return $this->success(t('{1}成功',t('新建数据表')),u('developer/project/table'));
			}

			return $this->error(t('{1}失败',t('新建数据表')));
		}

		$data = array();

        $this->assign('title', t('创建数据表'));
        $this->assign('data', $data);
        $this->display('developer/project_tablepost.php');
	}

    /**
     * 修改数据表
     *
     */
    public function action_edittable($table)
    {

		if ( $post = $this->post() )
		{
			// app 信息
			$app = $this->app;

			// 数据表更名
			if (  $post['name'] != $table )
			{
				if ( $this->db->existsTable($post['name']) ) return $this->error(t('%s 已经存在', $post['name']));

				if ( $this->db->renameTable($table,$post['name']) == false )
				{
					return $this->error(t('更新表名称失败'));
				}

				$app['tables'] = str_replace(','.$table.',', ','.$post['name'].',', ','.$app['tables'].',');
				$app['tables'] = trim($app['tables'], ',');				
			}

			// 更新表注释
			if ( $this->db->commentTable($post['name'],$post['comment']) == false )
			{
				return $this->error(t('更是表注释失败'));
			}

			// 写入应用配置
			file::put(ZOTOP_PATH_APPS . DS . $this->dir . DS . 'app.php', "<?php\nreturn ".var_export($app, true).";\n?>");

			// 写入项目文件
			file::put(ZOTOP_PATH_APPS . DS . $this->dir . DS . '_project.php', "<?php\nreturn ".var_export($app, true).";\n?>");


			return $this->success(t('操作成功'),u('developer/project/table'));
		}

		$tables = $this->db->tables();

		if( $data = $tables[$table] )
		{
			$data['name'] = $table;
		}


        $this->assign('title', t('表设置'));
        $this->assign('data', $data);
        $this->display('developer/project_tablepost.php');
	}


	/**
     * 检查项目编号及文件目录是否被占用
     *
     * @return bool
     */
	public function action_checktable($ignore='')
	{
		$name = $_GET['name'];

		if ( $name != $ignore and $this->db->existsTable($name) )
		{
			exit('"'.t('已经存在，请重新输入').'"');
		}

		exit('true');		
	}

    /**
     * 创建应用
     *
     * @param mixed $app 应用的项目数据
     * @return void
     */
    private function create_app($app)
    {
		if ( empty($app['id']) ) return $this->error(t('标识不允许为空'));
		if ( empty($app['dir']) ) return $this->error(t('目录不允许为空'));

        // 开发助手common目录
        $common_path 	= A('developer.path') . DS . 'common';

        // 应用目录
        $app_path 		= ZOTOP_PATH_APPS . DS . $app['dir'];

        if ( folder::exists($app_path ) )
        {
        	return $this->error(t('目录 {1} 已经存在', $app['dir']));
        }

        // 创建应用文件夹
        folder::create($app_path);
        folder::create($app_path . DS . 'controllers');
        folder::create($app_path . DS . 'models');
        folder::create($app_path . DS . 'templates');
        folder::create($app_path . DS . 'libraries');

        // 放置默认的app图标
        file::copy($common_path . DS . 'app.png', $app_path . DS . 'app.png');

		// 清理配置文件
		$app = $this->clear_setting($app);

		// 写入应用配置
		file::put($app_path . DS . 'app.php', "<?php\nreturn ".var_export($app, true).";\n?>");

		// 写入项目文件
		file::put($app_path . DS . '_project.php', "<?php\nreturn ".var_export($app, true).";\n?>");

        // 写入全局文件
        $this->create_file('global.php', $app_path . DS . 'global.php', $app);

		// 写入api类
        $this->create_file('api.php', $app_path . DS . 'libraries' .DS. "api.php", $app);

		// 写入安装文件
        $this->create_file('install.php', $app_path . DS . 'install.php', $app);

        // 写入卸载文件
        $this->create_file('uninstall.php', $app_path . DS . 'uninstall.php', $app);

		// 写入前台控制器
		$this->create_file('controller_index.php', $app_path . DS . 'controllers' .DS. "index.php", $app);

		// 写入后台控制器示例
		$this->create_file('controller_admin.php', $app_path . DS . 'controllers' .DS. "admin.php", $app);

		// 写入前台模板
		$this->create_file('template_index.php', $app_path . DS . 'templates' .DS. "index.php", $app);

		// 写入后台模板
		$this->create_file('template_admin_index.php', $app_path . DS . 'templates' .DS. "admin_index.php", $app);

		// 写入侧边模板
		$this->create_file('template_admin_side.php', $app_path . DS . 'templates' .DS. "admin_side.php", $app);

		return true;
    }

    /**
     * 读取程序模板文件，替换相应标签后写入项目文件夹中
     *
     *
     * @param mixed $file 位于developer开发助手应用common目录下的文件名称
     * @param mixed $target 应用下的目标文件夹
     * @param mixed $app 项目数据
     * @return void
     */
    private function create_file($template, $target, $app)
    {
        // 开发助手common目录下的文件
        $template = A('developer.path') . DS . 'common' . DS . $template;

        if (file::exists($template))
        {
            $str = file::get($template);

            foreach ($app as $key => $val)
            {
                $str = str_replace('[' . $key . ']', $val, $str);
            }

            file::put($target, $str);
        }

        return false;
    }

    /**
     * 清理模块设置
     *
     *
     * @param mixed $file 位于developer开发助手应用common目录下的文件名称
     * @param mixed $target 应用下的目标文件夹
     * @param mixed $app 项目数据
     * @return void
     */
	private function clear_setting($app)
	{
		$setting = array();

		foreach( $app as $key=>$val )
		{
			if ( in_array($key, array_keys($this->attrs) ) )
			{
				$setting[$key] = $val;
			}
		}

		return $setting;
	}
}
?>