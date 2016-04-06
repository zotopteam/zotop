<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 开发助手
 *
 * @package     system
 * @author      zotop team
 * @copyright   (c)2009-2011 zotop team
 * @license     http://zotop.com/license.html
 */
class developer_controller_project extends admin_controller
{
    protected $attrs;
    protected $dir; // 项目文件夹
    protected $app = array();
    protected $app_path;
    protected $app_file;
    protected $db;

    /**
     * 重载__init函数
     */
    public function __init()
    {
        parent::__init();

        $this->attrs = array(
            'id'            => t('标识'),
            'dir'           => t('目录'),
            'name'          => t('名称'),
            'type'          => t('类型'),
            'description'   => t('描述'),
            'version'       => t('版本号'),
            'tables'        => t('数据表'),
            'dependencies'  => t('依赖'),
            'author'        => t('作者'),
            'email'         => t('电子邮箱'),
            'homepage'      => t('网站'),
        );


        if ( ZOTOP_ACTION != 'add' )
        {
            // 获取工程信息
            if ( $_GET['project'] )
            {
                $this->dir = $_GET['project'];

                zotop::cookie('project_dir', $this->dir, 365*86400);
            }
            else
            {
                $this->dir = zotop::cookie('project_dir');
            }

            if ( empty($this->dir) )
            {
                return $this->redirect(U('developer'));
            }

            $this->app_path = ZOTOP_PATH_APPS . DS . $this->dir;
            $this->app_file = $this->app_path . DS . 'app.php';

            if ( !file::exists($this->app_file) )
            {
                return $this->error(t('$1 不存在', $this->app_file));
            }
            
            $this->app      = @include($this->app_file);

            if ( empty($this->app) or !is_array($this->app) )
            {
                return $this->error(t('$1 不正确', $this->app_file));
            }

            $this->assign('app',$this->app);
            $this->assign('app_file', $this->app_file);
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
        //$this->create_app($this->app);

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
                $this->success(t('创建成功'), U('developer/project/index',array('project'=>$post['dir'])));
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
            if ( $this->dir != $post['dir'] and folder::rename($this->app_path, $post['dir']) )
            {
                $this->app_path = ZOTOP_PATH_APPS . DS . $post['dir'];
            }            

            // 合并数据
            $post = $this->clear_setting(array_merge($this->app, $post));

            // 写入应用配置
            file::put($this->app_path . DS . 'app.php', "<?php\nreturn ".var_export($post, true).";\n?>");

            // 保存并返回
            return $this->success(t('保存成功'),U('developer/project/index',array('project'=>$post['dir'])));
        }

        $this->assign('title', t('编辑应用'));
        $this->assign('app', $this->app);
        $this->assign('attrs',$this->attrs);
        $this->display('developer/project_post.php');
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
                return $this->success(t('删除成功'),u('developer/index'));
            }

            return $this->error(t('删除失败'));
        }

        return $this->error(t('禁止访问'));
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
            $config = is_array($post['config_key']) ? array_combine($post['config_key'], $post['config_val']) : array();

            // 写入应用配置
            file::put($this->app_path.DS.'config.php', "<?php\nreturn ".var_export($config, true).";\n?>");

            // 如果应用已经安装，写入全局配置
            if ( A($this->app['id']) )
            {   
                m('system.app')->config(array_merge($config, c($this->app['id'])), $this->app['id']);
            }

            // 写入配置控制器
            developer::make_file(DEVELOPER_SOURCE_PATH.DS.'controllers'.DS.'config.php', $this->app_path . DS . 'controllers' .DS. "config.php", $this->app , array(
                'name'        => 'config',
                'title'       => t('$1配置',$app['name']),
                'description' => t('$1配置',$app['name'])
            ));         

            // 写入配置模板
            developer::make_file(DEVELOPER_SOURCE_PATH.DS.'templates'.DS.'config_index.php', $this->app_path . DS . 'templates' .DS. "config_index.php", $this->app , array(
                'name'        => 'config',
                'title'       => t('$1配置',$app['name']),
                'description' => t('$1配置',$app['name'])
            ));

            return $this->success(t('保存成功'));
        }

        $config = @include(ZOTOP_PATH_APPS . DS . $this->dir . DS .'config.php');
        $config = is_array($config) ? $config : array();

        $this->assign('title', t('配置项管理'));
        $this->assign('config',$config);
        $this->display();
    }

    /**
     * 配置表单示例代码
     * @return [type] [description]
     */
    public function action_config_formcode()
    {
        $config = @include($this->app_path . DS .'config.php');
        $config = is_array($config) ? $config : array();
        
        $html   = file::get(DEVELOPER_SOURCE_PATH.DS.'templates'.DS.'config_field.php');
        $code   = '';

        foreach ($config as $key => $value)
        {
            $str = $html;
            $str = str_replace('[name]', $key, $str);
            $str = str_replace('[label]', $key, $str);
            $str = str_replace('[tip]', $key, $str);
            $str = str_replace('[value]', "c('{$this->app['id']}.{$key}')", $str);
            
            $code = $code."\n".$str;
        }

        $this->assign('title', t('表单示例代码'));
        $this->assign('code',$code);
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
    public function action_create_table()
    {
        if ( $post = $this->post() )
        {
            $schema = array(
                'fields'  => array(
                    'id' => array('type' => 'int', 'length' => 10, 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE, 'comment' => t('编号')),
                ),
                'primary' => array('id'),
                'comment' => $post['comment'],
                'engine'  => $post['engine'],
            );

            if (  $this->db->existsTable($post['name']) )
            {
                return $this->error(t('$1已经存在', $post['name']));
            }

            if ( $this->db->createTable($post['name'], $schema) )
            {
                $this->app_tables($post['name']);

                return $this->success(t('$1成功',t('新建数据表')),u('developer/project/table'));
            }

            return $this->error(t('$1失败', t('新建数据表')));
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
    public function action_edit_table($table)
    {
        if ( $post = $this->post() )
        {
            // 数据表更名
            if ( $post['name'] and $post['name'] != $table )
            {
                if ( $this->db->existsTable($post['name']) ) return $this->error(t('%s 已经存在', $post['name']));

                if ( $this->db->renameTable($table, $post['name']) == false )
                {
                    return $this->error(t('更新表名称失败'));
                }

                $this->app_tables($table, $post['name']);
            }

            // TODO 更改表类型

            // 更新表注释
            if ( $this->db->commentTable($post['name'], $post['comment']) == false )
            {
                return $this->error(t('更改表注释失败'));
            }

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
     * 删除数据表
     *
     * @return bool
     */
    public function action_delete_table($table)
    {
        if ( $this->db->dropTable($table) )
        {
            $this->app_tables($table, null);

            return $this->success(t('删除成功'),u('developer/project/table'));
        }

        return $this->error(t('$1失败', t('删除')));
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

        // 应用目录
        $app_path       = ZOTOP_PATH_APPS . DS . $app['dir'];

        if ( folder::exists($app_path) )
        {
           return $this->error(t('目录 $1 已经存在', $app['dir']));
        }

        // 创建应用文件夹
        folder::create($app_path);
        folder::create($app_path . DS . 'controllers');
        folder::create($app_path . DS . 'models');
        folder::create($app_path . DS . 'templates');
        folder::create($app_path . DS . 'libraries');

        // 放置默认的app图标
        file::copy(DEVELOPER_SOURCE_PATH . DS . 'app.png', $app_path . DS . 'app.png');

        // 清理配置文件
        $app = $this->clear_setting($app);

        // 写入应用配置
        file::put($app_path . DS . 'app.php', "<?php\nreturn ".var_export($app, true).";\n?>");

        // 写入项目文件
        file::put($app_path . DS . 'project.lock', t('发布时请删除此文件'));

        // 写入全局文件
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'global.php', $app_path . DS . 'global.php', $app);

        // 写入安装文件
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'install.php', $app_path . DS . 'install.php', $app);

        // 写入卸载文件
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'uninstall.php', $app_path . DS . 'uninstall.php', $app);        

        // 写入api类
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'libraries'.DS.'hook.php', $app_path . DS . 'libraries' .DS. "hook.php", $app);

        // 写入默认首页控制器
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'controllers'.DS.'site.php', $app_path . DS . 'controllers' .DS. "index.php", $app , array(
            'name'        => 'index',
            'title'       => t('$1首页',$app['name']),
            'description' => t('$1首页',$app['name'])
        ));

        // 写入首页模板
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'templates'.DS.'site.php', $app_path . DS . 'templates' .DS. "index.php", $app , array(
            'name'        => 'index',
            'title'       => t('$1首页',$app['name']),
            'description' => t('$1首页',$app['name'])
        ));        

        // 写入后台控制器示例
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'controllers'.DS.'admin.php', $app_path . DS . 'controllers' .DS. "admin.php", $app , array(
            'name'        => 'admin',
            'title'       => t('$1管理',$app['name']),
            'description' => t('$1管理',$app['name'])
        ));

        // 写入后台模板
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'templates'.DS.'admin_index.php', $app_path . DS . 'templates' .DS. "admin_index.php", $app , array(
            'name'        => 'admin',
            'title'       => t('$1管理',$app['name']),
            'description' => t('$1管理',$app['name'])
        ));

        // 写入侧边模板
        developer::make_file(DEVELOPER_SOURCE_PATH.DS.'templates'.DS.'admin_side.php', $app_path . DS . 'templates' .DS. "admin_side.php", $app , array(
            'name'        => 'admin',
            'title'       => t('$1侧边栏',$app['name']),
            'description' => t('$1侧边栏',$app['name'])
        ));

        return true;
    }

    /**
     * 清理模块设置，只保留模块需要的部分
     *
     *
     * @param mixed $file 位于developer开发助手应用source目录下的文件名称
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

    /**
     * 操作app.php中的tables值
     * 
     * @param  string $table 表名称
     * @param  string $value 空=新增 null=删除 其他=修改
     * @return [type]
     */
    private function app_tables($table, $value='')
    {
        $tables = explode(',',$this->app['tables']);

        if ( $value === '' )
        {
            $tables[] = $table;
        }
        elseif ( $value === null )
        {
            foreach ($tables as $key => $val)
            {
                if ( $val == $table ) unset($tables[$key]);
            }
        }
        else
        {
            foreach ($tables as $key => $val)
            {
                if ( $val == $table ) $tables[$key] = $value;
            }            
        }

        $this->app['tables'] = implode(',', $tables);

        // 写入应用配置
        file::put(ZOTOP_PATH_APPS . DS . $this->dir . DS . 'app.php', "<?php\nreturn ".var_export($this->app, true).";\n?>");

        return true;
    }
}
?>