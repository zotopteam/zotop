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
class system_model_app extends model
{
	protected $pk = 'id';
	protected $table = 'app';

	// 定义核心应用，核心应用无法禁用删除卸载
	public $cores = array('system');


    // 插入成功后的回调方法
    protected function _after_insert(&$data, $where)
	{
		return $this->cache(true);
	}

    // 更新成功后的回调方法
    protected function _after_update(&$data, $where)
	{
		return $this->cache(true);
	}

    // 删除成功后的回调方法
    protected function _after_delete(&$data,$where)
	{
		return $this->cache(true);
	}

	/**
	 * 根据应用的ID设置应用状态
	 *
	 * @param string $id 应用ID
	 * @return bool
	 */
	public function status($id)
	{
		$data = $this->select('status,type')->getbyid($id);

		$status = ( $data['status'] > 0 ) ? 0 : 1;

		if ( in_array( strtolower($id), $this->cores ) and $status == 0 )
		{
			return $this->error(t('核心应用不能被禁用'));
		}

		return $this->update(array('status'=>$status),$id);
	}

	/**
	 * 根据应用的目录安装应用
	 *
	 * @param string $dir 应用目录
	 * @param string $options 安装选项
	 * @return bool
	 */
	public function install($dir, $options=array())
	{
		//定义安装
		define('ZOTOP_INSTALL', true);

		$install = true;

		// 获取应用的数据信息
		$app = @include(format::path(ZOTOP_PATH_APPS.DS.$dir.DS.'app.php'));

		if ( !is_array($app) OR empty($app['id']) OR empty($app['name']) OR empty($app['version']) )
		{
			return $this->error(t('错误的应用配置文件'));
		}

		// 已经存在相同ID的插件
		if ( $this->where('id',$app['id'])->exists() )
		{
			return $this->error(t('已经存在标识为[%s]的应用，请先卸载该应用后再安装',$app['id']));
		}

		// 检查依赖模块
		if ( !empty($app['dependencies']) )
		{
			foreach( explode(',', $app['dependencies']) as $a )
			{
				if ( A($a) == false ) return $this->error(t('依赖模块 %s 不存在，无法安装', $a));
				if ( A("{$a}.status") < 1 ) return $this->error(t('依赖模块 %s 已被禁用，无法安装', A("{$a}.name")));
			}
		}

		try
		{
			$this->begin();

			// 执行安装文件
			if ( file::exists(ZOTOP_PATH_APPS.DS.$dir.DS.'install.php') )
			{
				$install = include(ZOTOP_PATH_APPS.DS.$dir.DS.'install.php');
			}

			// 安装设置文件
			if ( file::exists(ZOTOP_PATH_APPS.DS.$dir.DS.'config.php') )
			{
				$config = include(ZOTOP_PATH_APPS.DS.$dir.DS.'config.php');
			}

			//写入数据库
			if ( $install !== false  )
			{
				$app['id'] 			= strtolower($app['id']);
				$app['dir'] 		= $dir;
				$app['type'] 		= empty($app['type']) ? 'module' : $app['type'];
				$app['status'] 		= 1;
				$app['listorder'] 	= $this->max('listorder') + 1;
				$app['installtime'] = ZOTOP_TIME;
				$app['updatetime'] 	= ZOTOP_TIME;

				// 将config写入数据库
				if ( $config and is_array($config) )
				{
					$this->config($config, $app['id']);
				}

				// 写入根权限
				$this->db->insert('admin_priv', array('id'=>$app['id'], 'name'=>$app['name'], 'app'=>$app['id']));

				// 写入app
				$this->insert($app);
			}

			return $this->commit();
		}
		catch(Exception $e)
		{
			$this->rollback();
			
			return $this->error($e->getMessage());
		}

		return $this->error(t('安装失败'));
	}

	/**
	 * 根据应用的ID卸载应用
	 *
	 * @param string $id 应用ID
	 * @param string $options 卸载选项
	 * @return bool
	 */
	public function uninstall($id, $options=array())
	{
		//定义安装
		define('ZOTOP_UNINSTALL', true);

		$uninstall =  true;

		if ( in_array( strtolower($id), $this->cores ) )
		{
			return $this->error(t('核心应用不能被卸载'));
		}

		$data = $this->getbyid($id);

		if ( file::exists(ZOTOP_PATH_APPS.DS.$data['dir'].DS.'uninstall.php') )
		{
			$uninstall = include(ZOTOP_PATH_APPS.DS.$data['dir'].DS.'uninstall.php');
		}

		if ( $uninstall !== false )
		{
			// 删除配置文件
			$this->config(null, $id);

			// 删除权限设置
			$this->db->from('admin_priv')->where('app',$id)->delete();

			// 删除app
			return $this->delete($id);
		}

		return false;
	}

	/**
	 * 应用升级
	 * 
	 * @param string $id 应用ID
	 * @param  array  $options [description]
	 * @return bool
	 */
	public function upgrade($id, $options=array())
	{
		//定义安装
		define('ZOTOP_UPGRADE', true);		

		$data = $this->getbyid($id);

		$app  = include(ZOTOP_PATH_APPS.DS.$data['dir'].DS.'app.php');

		if ( $app['version'] > $data['version'] )
		{
			$upgrade =  true;

			if ( file::exists(ZOTOP_PATH_APPS.DS.$data['dir'].DS.'upgrade.php') )
			{
				$upgrade = include(ZOTOP_PATH_APPS.DS.$data['dir'].DS.'upgrade.php');
			}

			$app['updatetime'] 	= ZOTOP_TIME;

			if ( $upgrade !== false and $this->update($app,$id))
			{
				// 删除升级标记
				file::delete(ZOTOP_PATH_APPS.DS.$data['dir'].DS.'upgrade.lock');
				
				return true;
			}

			return false;	
		}

		return false;
	}

	/**
	 * 将当前模块的config写入数据库并生成模块config文件
	 *
	 * @param array $config 配置组
	 * @param string $app 未设置此项则设置为当前模块
	 * @return bool
	 */
	public function config($config, $app=null)
	{
		if ( empty($app) ) $app = ZOTOP_APP;

		// 删除应用的全部config
		if ( $config === null )
		{
			// 删除全部设置数据
			$this->db->from('config')->where('app',$app)->delete();

			// 删除设置文件
			zotop::data(ZOTOP_PATH_CONFIG.DS."{$app}.php", null);
			zotop::reboot();
			return true;
		}

		if( is_array($config) )
		{
			// 写入全部配置数据
			foreach ( $config as $key=>$value )
			{
				// 如果值相同跳过
				if ( C("{$app}.{$key}") == $value and C("{$app}.{$key}") == null ) continue;

				// 保存不相同的设置
				$this->db->from('config')->set(array('app'=>$app,'key'=>$key,'value'=>$value))->insert(true);
			}

			// 获取全部配置并写入配置文件
			$results = $this->db->from('config')->select('key,value')->where('app',$app)->getAll();

			foreach( $results as $r )
			{
				$data[$r['key']] = $r['value'];
			}

			zotop::data(ZOTOP_PATH_CONFIG.DS."{$app}.php", $data);
			zotop::reboot();
			return true;
		}

		return false;
	}

	/**
	 * 覆盖获取全部数据方法
	 *
     * @param string|array $sql
     * @return mixed
	 */
    public function getAll()
    {
		$data = array();

		$result = $this->db()->orderby('listorder','asc')->getAll();
		
		foreach($result as $r)
		{
			$data[strtolower($r['id'])] = $r;
		}

		return $data;
    }

	/**
	 * 获取全部未安装的应用
	 *
	 * @param string $str 原密码
	 * @return string 加密后的密码
	 */
	public function getUninstalled()
	{
		$apps = array();

		//获取全部已经安装的应用
		$installed = $this->getAll();

		//获取目录
		$installed_dirs = arr::column($installed,'dir');

		//获取apps目录下全部已存在应用
		$folders = folder::folders(ZOTOP_PATH_APPS,false);

		foreach( $folders as $dir )
		{
			//判断是否已经存在该应用
			if ( in_array($dir, $installed_dirs)  ) continue;

			//取得应用配置
			$a = @include(ZOTOP_PATH_APPS.DS.$dir.DS.'app.php');

			if ( is_array($a) AND isset($a['id']) )
			{
				//将应用存入未安装应用的$apps数组,以目录作为主键
				$apps[$dir] = $a;
			}
		}

		return $apps;
	}

    /**
     * 根据传入的 id 数组顺序进行排序
     *
     * @param array $ids
     * @return bool
     */
    public function order($ids)
    {
        foreach ((array )$ids as $i => $id)
        {
            $this->update(array('listorder' => $i + 1), $id);
        }

		$this->cache(true);
        return true;
    }

	/**
	 * 缓存app数据
	 *
	 * @param bool $refresh 是否强制刷新缓存
	 * @return bool
	 */
	public function cache($refresh=false)
	{
	    //应用缓存文件
		$file = ZOTOP_PATH_CONFIG.DS.$this->table.'.php';

		//获取缓存信息
		$cache = zotop::data($file);

		if ( $refresh or !is_array($cache) )
		{
			//读取数据
			$cache = $this->getAll();

			//写入数据
    		zotop::data($file, $cache);

    		//重启系统
    		zotop::reboot();

			unset($data);
		}

		return $cache;
	}
}
?>