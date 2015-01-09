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
	protected $project; // 项目文件夹名称
	protected $data = array();
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

		// 获取工程信息
		if ( $_GET['project'] )
		{
			$this->project = $_GET['project'];
			zotop::cookie('project', $this->project);
		}
		else
		{
			$this->project = zotop::cookie('project');
		}

		if ( $this->project )
		{
			$this->data = @include(ZOTOP_PATH_APPS . DS . $this->project . DS .'app.php');

			if ( empty($this->data) or !is_array($this->data) )
			{
				return $this->error(t('错误的项目文件'));
			}
		}

		$this->db = zotop::db();
    }



    /**
     * 项目页面
     *
     */
    public function action_index()
    {
        $this->assign('title', t('基本信息'));
		$this->assign('attrs',$this->attrs);
		$this->assign('data',$this->clear_setting($this->data));
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
			if ( $this->create_baseapp($post) )
			{
				$this->success(t('创建成功'), U('developer/developer/project',array('project'=>$post['dir'])));
			}

			return $this->error($this->error());
        }

        $data = array('type'=>'module','version'=>'1.0');

        $this->assign('title', t('新建应用'));
        $this->assign('data', $data);
		$this->assign('attrs',$this->attrs);
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
			if ( $this->project != $post['dir'] )
			{
				folder::rename(ZOTOP_PATH_APPS . DS . $this->project, $post['dir']);
			}

			$app_path = ZOTOP_PATH_APPS . DS . $post['dir'];

			// 合并数据
			$post = $this->clear_setting(array_merge($this->data, $post));

			// 写入应用配置
			file::put($app_path . DS . 'app.php', "<?php\nreturn ".var_export($post, true).";\n?>");

			// 写入项目文件
			file::put($app_path . DS . '_project.php', "<?php\nreturn ".var_export($post, true).";\n?>");

			// 保存并返回
			return $this->success(t('保存成功'),U('developer/developer/project'));
        }

        $this->assign('title', t('编辑应用'));
        $this->assign('data', $this->data);
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
			
			$config_path = ZOTOP_PATH_APPS . DS . $this->project . DS .'config.php';

			$config = is_array($post['config_key']) ? array_combine($post['config_key'], $post['config_val']) : array();

			// 写入应用配置
			file::put($config_path, "<?php\nreturn ".var_export($config, true).";\n?>");

			
			// 写入配置控制器
			if ( !file::exists(ZOTOP_PATH_APPS . DS . $this->project . DS. 'controllers' .DS .'config.php') )
			{
				$this->create_file('controller_config.php', ZOTOP_PATH_APPS . DS . $this->project . DS . 'controllers' .DS. "config.php", $this->data);
			}

			// 写入配置模板，并写入第一项
			$this->data['config_first'] = $post['config_key'][0];

			if ( !file::exists(ZOTOP_PATH_APPS . DS . $this->project . DS. 'templates' .DS .'config_index.php') )
			{
				$this->create_file('template_config_index.php', ZOTOP_PATH_APPS . DS . $this->project . DS . 'templates' .DS. "config_index.php", $this->data);
			}

			return $this->success(t('保存成功'));
		}

		$config = @include(ZOTOP_PATH_APPS . DS . $this->project . DS .'config.php');
		$config = is_array($config) ? $config : array(''=>'');

        $this->assign('title', t('配置管理'));
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
		$tables = $this->db->tables();
		
		$apptables = explode(',',$this->data['tables']);

		// 获取你属于模块的表
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

			if (  $this->db->table($post['name'])->exists() )
			{
				return $this->error(t('%s 已经存在', $post['name']));
			}

			if ( $this->db->table($post['name'])->create($schema) )
			{
				$data = $this->data;

				if ( empty($data['tables']) )
				{
					$data['tables'] = $post['name'];
				}
				elseif ( strpos(','.$data['tables'].',',  ','.$post['name'].',') === false )
				{
					$data['tables'] = $data['tables'].','.$post['name'];
				}

				// 写入应用配置
				file::put(ZOTOP_PATH_APPS . DS . $this->project . DS . 'app.php', "<?php\nreturn ".var_export($data, true).";\n?>");

				// 写入项目文件
				file::put(ZOTOP_PATH_APPS . DS . $this->project . DS . '_project.php', "<?php\nreturn ".var_export($data, true).";\n?>");

				return $this->success(t('%s成功',t('新建数据表')),u('developer/developer/project'));
			}

			return $this->error(t('%s失败',t('新建数据表')));
		}

		$data = array();
		$data['engine'] = 'MyISAM';

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
			$data = $this->data;	

			// 数据表更名
			if (  $post['name'] != $table )
			{
				if ( $this->db->table($post['name'])->exists() ) return $this->error(t('%s 已经存在', $post['name']));

				if ( $this->db->table($table)->rename($post['name']) == false )
				{
					return $this->error(t('更新表名称失败'));
				}

				$data['tables'] = str_replace(','.$table.',', ','.$post['name'].',', ','.$data['tables'].',');
				$data['tables'] = trim($data['tables'], ',');				
			}

			// 更新表注释
			if ( $this->db->table($post['name'])->comment($post['comment']) == false )
			{
				return $this->error(t('更是表注释失败'));
			}

			// 写入应用配置
			file::put(ZOTOP_PATH_APPS . DS . $this->project . DS . 'app.php', "<?php\nreturn ".var_export($data, true).";\n?>");

			// 写入项目文件
			file::put(ZOTOP_PATH_APPS . DS . $this->project . DS . '_project.php', "<?php\nreturn ".var_export($data, true).";\n?>");

			return $this->success(t('操作成功'),u('developer/developer/project'));
		}

		$tables = $this->db->tables();


		$data = $tables[$table];
		$data['name'] = $table;

        $this->assign('title', t('表设置'));
        $this->assign('data', $data);
        $this->display('developer/project_tablepost.php');
	}


	/**
     * 检查项目编号及文件目录是否被占用
     *
     * @return bool
     */
	public function action_check($key,$ignore='')
	{
		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}

    /**
     * 创建应用
     *
     * @param mixed $data 应用的项目数据
     * @return void
     */
    private function create_baseapp($data)
    {
		if ( empty($data['dir']) ) return false;

        // 开发助手common目录
        $common_path = A('developer.path') . DS . 'common';

        // 应用目录
        $app_path = ZOTOP_PATH_APPS . DS . $data['dir'];

        // 创建应用文件夹
        folder::create($app_path);
        folder::create($app_path . DS . 'controllers');
        folder::create($app_path . DS . 'models');
        folder::create($app_path . DS . 'templates');
        folder::create($app_path . DS . 'libraries');

        // 放置默认的app图标
        file::copy($common_path . DS . 'app.png', $app_path . DS . 'app.png');

		// 清理配置文件
		$data = $this->clear_setting($data);

		// 写入应用配置
		file::put($app_path . DS . 'app.php', "<?php\nreturn ".var_export($data, true).";\n?>");

		// 写入项目文件
		file::put($app_path . DS . '_project.php', "<?php\nreturn ".var_export($data, true).";\n?>");

		// TODO 以下写入创建应用中，暂时不要生成app的各种文件，增加应用生成功能，一旦生成就删除_project，不能再次编辑修改了

        // 写入全局文件
        $this->create_file('global.php', $app_path . DS . 'global.php', $data);

		// 写入api类
        $this->create_file('api.php', $app_path . DS . 'libraries' .DS. "api.php", $data);

		// 写入安装卸载文件
        $this->create_file('install.php', $app_path . DS . 'install.php', $data);
        $this->create_file('uninstall.php', $app_path . DS . 'uninstall.php', $data);

		// 写入前台控制器
		$this->create_file('controller_index.php', $app_path . DS . 'controllers' .DS. "index.php", $data);

		// 写入后台控制器示例
		$this->create_file('controller_admin.php', $app_path . DS . 'controllers' .DS. "admin.php", $data);

		// 写入前台模板
		$this->create_file('template_index.php', $app_path . DS . 'templates' .DS. "index.php", $data);

		// 写入后台模板
		$this->create_file('template_admin_index.php', $app_path . DS . 'templates' .DS. "admin_index.php", $data);

		// 写入侧边模板
		$this->create_file('template_admin_side.php', $app_path . DS . 'templates' .DS. "admin_side.php", $data);

		return true;
    }

    /**
     * 读取程序模板文件，替换相应标签后写入项目文件夹中
     *
     *
     * @param mixed $file 位于developer开发助手应用common目录下的文件名称
     * @param mixed $target 应用下的目标文件夹
     * @param mixed $data 项目数据
     * @return void
     */
    private function create_file($template, $target, $data)
    {
        // 开发助手common目录下的文件
        $template = A('developer.path') . DS . 'common' . DS . $template;

        if (file::exists($template))
        {
            $str = file::get($template);

            foreach ($data as $key => $val)
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
     * @param mixed $data 项目数据
     * @return void
     */
	private function clear_setting($data)
	{
		$setting = array();

		foreach( $data as $key=>$val )
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