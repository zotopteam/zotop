<?php
/**
 * zotop install
 *
 * @package zotop
 * @author zotop team
 * @copyright(c)2009-2012 zotop team
 * @license http://zotop.com/license
 */

// 设置安装时候需要用到的常量
define('C('system.debug')',			true);
define('C('system.trace')',			true);
define('ZOTOP_INSTALL',			true);
define('ZOTOP_PATH_INSTALL', 	dirname(__FILE__));

// 设置超时时间
@set_time_limit(1000);

//加载主文件
require dirname(ZOTOP_PATH_INSTALL).DIRECTORY_SEPARATOR.'zotop'.DIRECTORY_SEPARATOR.'zotop.php';

zotop::init();
zotop::register(include(ZOTOP_PATH_CMS.DS.'preload.php'));

$install = new install();

class install
{
	public $db = null;
	public $action = null;
	public $completed = array();
	public $steps = array();

	public function __construct()
	{
		//安装判断
		if( is_file(ZOTOP_PATH_DATA.DS.'install.lock') )
		{
			$installed = true;
			include ZOTOP_PATH_INSTALL.DS.'template'.DS.'start.php';
			exit;
		}

		$this->action = empty($_GET['action']) ? 'start' : $_GET['action'];

		$this->steps = array(
			'start'		=> t('欢迎'),
			'check'		=> t('环境检测'),
			'data'		=> t('网站设置'),
			'app'		=> t('选择应用'),
			'install'	=> t('安装进程'),
			'success'	=> t('完成'),
		);

		$this->completed = array_slice(array_keys($this->steps),0,array_search($this->action,array_keys($this->steps)));

		$path = trim(str_replace('install/index.php', '', $_SERVER['PHP_SELF']), '/');

		define('WWW_URL', ($this->ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . ($path ? '/'.$path : ''));

		call_user_func(array($this, $this->action));
	}

	public function ssl()
	{
		return (strtolower($this->env('HTTPS')) === 'on' || strtolower($this->env('HTTP_SSL_HTTPS')) === 'on' || $this->env('HTTP_X_FORWARDED_PROTO') == 'https');
	}

	public function env($key)
	{
		return isset($_SERVER[$key]) ? $_SERVER[$key] : (isset($_ENV[$key]) ? $_ENV[$key] : FALSE);
	}

	public function db()
	{
		return $this->db;
	}

	public function start()
	{
		$license = file::get(ZOTOP_PATH_INSTALL.DS.'license.txt');
		$license = format::textarea($license);

		include ZOTOP_PATH_INSTALL.DS.'template'.DS.'start.php';
	}

	public function check()
	{
		$success = true;

		// 检查运行环境
	    $check_env = array(
			'php'               => array('item'=>t('PHP版本'),'need'=>'PHP 5.2','checked'=>true,'message'=>PHP_VERSION),
			'gd'                => array('item'=>t('GD库'),'need'=>'GD 2.0','checked'=>true),
			'pdo'               => array('item'=>t('pdo'),'need'=>'','checked'=>true),
			'mysql'             => array('item'=>t('pdo_mysql'),'need'=>'','checked'=>true),
			'file_get_contents' => array('item'=>t('file_get_contents'),'need'=>'','checked'=>true),
			'mb_strlen'         => array('item'=>t('mb_strlen'),'need'=>'','checked'=>true)
	    );

	    // 检测PHP版本
	    if ( version_compare(PHP_VERSION, '5.2.0', '<') )
	    {
			$check_env['php']['checked'] = false;
			$success                     = false;
	    }

	    // 检测GD库
	    $gd = function_exists('gd_info') ? gd_info() : array();

	    if ( empty($gd['GD Version']) )
	    {
			$check_env['gd']['checked'] = false;
			$check_env['gd']['message'] = t('未安装');
			$success                    = false;
	    }
	    else
	    {
	    	$check_env['gd']['message'] = $gd['GD Version'];
	    }

	    unset($gd);

	    // 数据库
	    if ( !class_exists('pdo') )
	    {
			$check_env['pdo']['checked'] = false;
			$check_env['pdo']['message'] = t('未安装');
			$success                     = false;
	    }

	    if ( !extension_loaded('pdo_mysql')  )
	    {
			$check_env['mysql']['checked'] = false;
			$check_env['mysql']['message'] = t('未安装');
			$success                       = false;
	    }

	    // 函数检查
	    foreach (array('file_get_contents','mb_strlen') as $f)
	    {
	    	if ( !function_exists($f) )
	    	{
	        	$check_env[$f]['checked'] = false;
				$check_env[$f]['message'] = t('不支持');
	        	$success                  = false;
	    	}
	    }

		// 目录和文件权限
    	$check_dir = array(
    		ZOTOP_PATH 			=> array('name'=>t('根目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_APPS 	=> array('name'=>t('应用目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_UPLOADS 	=> array('name'=>t('文件上传目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_DATA 	=> array('name'=>t('数据目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_CONFIG 	=> array('name'=>t('设置目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_RUNTIME 	=> array('name'=>t('运行时目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_THEMES 	=> array('name'=>t('主题和模板目录'), 'type'=>'folder', 'writable'=>true),
    	);

    	foreach ($check_dir as $f => &$r)
    	{
    		if ( !file_exists($f))
    		{
    			$is_writable = ($r['type'] == 'file') ? (false !== @file_put_contents($f, '')) : folder::create($f, 0777);
    		}
    		else
    		{
    			$is_writable = ($r['type'] == 'file') ? is_writable($f) : folder::writeable($f);
    		}

			$r['is_writable'] = $is_writable;

			$r['position']    = '/'.str_replace(DS,'/', trim(str_replace(ZOTOP_PATH, '', $f), DS));

    		if ( !$is_writable AND $r['writable'] ) $success = false;
    	}

		folder::create(ZOTOP_PATH_RUNTIME.DS.'caches',0777);
		folder::create(ZOTOP_PATH_RUNTIME.DS.'session',0777);
		folder::create(ZOTOP_PATH_RUNTIME.DS.'templates',0777);
		folder::create(ZOTOP_PATH_RUNTIME.DS.'backup',0777);
		folder::create(ZOTOP_PATH_RUNTIME.DS.'temp',0777);
		folder::create(ZOTOP_PATH_RUNTIME.DS.'block',0777);


		include ZOTOP_PATH_INSTALL.DS.'template'.DS.'check.php';
	}

	/*
	 * 数据库配置
	 *
	 */
	public function data()
	{
		$data = zotop::cookie('data');
		$data = is_array($data) ? $data : array();
		$data = $data + array(
			'driver'			=> 'mysql',
			'prefix'			=> 'zotop_',
			'charset'			=> 'utf8',
			'pconnect'			=> false,
			'mysql_hostname'	=> 'localhost',
			'mysql_hostport'	=> 3306,
			'mysql_username'	=> 'root',
			'mysql_database'	=> 'zotop',

			'sqlite_database'	=> 'zotop.db3',

			'site_name' 		=> t('逐涛网'),

			'admin_username' 	=> 'admin',
			'admin_password' 	=> 'admin999',
			'admin_email' 		=> 'admin@zotop.com',
		);

		include ZOTOP_PATH_INSTALL.DS.'template'.DS.'data.php';
	}

	/*
	 * 创建数据库
	 *
	 */
	public function dbcreate()
	{
		extract($_POST);

		// 创建mysql
		if ( $driver == 'mysql' )
		{
			if ( empty($mysql_hostname) OR empty($mysql_hostport) OR empty($mysql_username) OR empty($mysql_database) OR empty($prefix) )
			{
				$msg = array('code'=>2, 'message'=>t('您提交的信息不完整'.$mysql_hostname));
			}
			else
			{
				$config = array(
					'driver' 	=> $driver,
					'hostname' 	=> $mysql_hostname,
					'hostport' 	=> $mysql_hostport,
					'username' 	=> $mysql_username,
					'password' 	=> $mysql_password,
					'database' 	=> $mysql_database,
					'prefix' 	=> $prefix,
					'pconnect' 	=> $pconnect,
				);

				try
				{
					// 尝试创建数据库
					zotop::db($config)->create();
									
					if ( zotop::db($config)->exists() )
					{
						if ( zotop::db($config)->tables() )
						{
							$msg = array('code'=>0, 'message'=>t('数据库 $1 已经存在，是否继续？如果继续系统将会删除原有数据', $mysql_database));
						}
						else
						{
							$msg = array('code'=>1, 'message'=>t('数据库 $1 已经准备成功', $mysql_database));
						}
					}
					else
					{
						$msg = array('code'=>2, 'message'=>t('数据库 $1 不存在并且无法自动创建，请先创建数据库！', $mysql_database));
					}
				}
				catch(Exception $e)
				{
					$msg = array('code'=>2, 'message'=>t('连接数据库失败，请检查数据库设置！$1', $e->getMessage()));
				}


			}
		}

		// 创建sqlite
		if ( $driver == 'sqlite' )
		{
			if ( empty($sqlite_database) OR empty($prefix) )
			{
				$msg = array('code'=>2, 'message'=>t('您提交的信息不完整'));
			}
			else
			{
				$config = array(
					'driver' 	=> $driver,
					'database' 	=> $sqlite_database,
					'prefix' 	=> $prefix,
					'pconnect' 	=> $pconnect,
				);

				try
				{
					if ( zotop::db($config)->exists() )
					{
						$msg = array('code'=>0, 'message'=>t('数据库 $1 已经存在，是否继续？如果继续系统将会删除原有数据', $sqlite_database));
					}
					elseif ( zotop::db($config)->create() )
					{
						$msg = array('code'=>1, 'message'=>t('数据库创建成功'));
					}
					else
					{
						$msg = array('code'=>2, 'message'=>t('数据库 $1 不存在并且无法自动创建，请先创建数据库！', $sqlite_database));
					}
				}
				catch(Exception $e)
				{
					$msg = array('code'=>2, 'message'=>t('无法创建数据库，请检查数据库目录权限！'));
				}
			}
		}

		ob_clean();

		if ( $msg['code'] <=1 )
		{
			$site = array(
				'name' 			=> $site_name,
				'url' 			=> WWW_URL,
				'title' 		=> $site_name,
				'description' 	=> $site_name,
				'keywords' 		=> $site_name,
			);

			$admin = array (
				'username' => $admin_username,
				'password' => $admin_password,
				'email'    => $admin_email,
			);


			$router = array();

			//写入默认数据库配置文件
			file::put(ZOTOP_PATH_CONFIG.DS.'database.php', "<?php\nreturn ".var_export($config,true).";\n?>");

			// 写入router配置
			file::put(ZOTOP_PATH_CONFIG.DS.'router.php', "<?php\nreturn ".var_export($router,true).";\n?>");

			// 缓存站点配置
			file::put(ZOTOP_PATH_RUNTIME.DS.'site.php', "<?php\nreturn ".var_export($site,true).";\n?>");

			// 缓存记录创始人信息，用于写入数据库
			file::put(ZOTOP_PATH_RUNTIME.DS.'admin.php', "<?php\nreturn ".var_export($admin,true).";\n?>");
		}

		exit(json_encode($msg));
	}


	/*
	 * 应用安装
	 *
	 */
	public function app()
	{

		// 获取apps目录下全部已存在应用
		$folders = folder::folders(ZOTOP_PATH_APPS,false);

		foreach( $folders as $dir )
		{
			//取得应用配置
			$m = @include(ZOTOP_PATH_APPS.DS.$dir.DS.'app.php');

			if ( is_array($m) AND isset($m['id']) )
			{
				$m['dir'] = $dir;
				$m['icon'] = '../'.basename(ZOTOP_PATH_CMS).'/apps/'.$m['dir'].'/app.png';

				$apps[$m['id']] = $m;
			}
		}

		$_apps 		= @include(ZOTOP_PATH_INSTALL.DS.'apps.php'); //必装的app
		$systems	= array();

		foreach ($_apps as $id)
		{
			if ( in_array($id, array_keys($apps)) )
			{
				$systems[$id] = $apps[$id];

				unset($apps[$id]);
			}
		}

		include ZOTOP_PATH_INSTALL.DS.'template'.DS.'app.php';
	}

	public function install()
	{
		$app = $_POST['app'];

		//存储数据
		zotop::cookie('app', $_POST['app']);


		include ZOTOP_PATH_INSTALL.DS.'template'.DS.'install.php';
	}

	/*
	 * 安装应用
	 */
	public function installing()
	{
		$this->db = zotop::db();

		// 安装当前应用
		try
		{
			$this->db->begin();

			$install = true;

			//获取待安装的应用目录
			$dir = $_REQUEST['app'];

			// 获取应用的数据信息
			$app = include(ZOTOP_PATH_APPS.DS.$dir.DS.'app.php');

			if ( !is_array($app) OR empty($app['id']) OR empty($app['name']) OR empty($app['version']) )
			{
				$msg = array('code'=>2, 'message'=>t('错误的应用文件'), 'detail'=>debug::path(ZOTOP_PATH_APPS.DS.$dir.DS.'app.php'));
			}
			else
			{
				// 执行安装文件 TODO 如果安装文件有错误，如：Parse error，无法捕获异常
				if ( file::exists(ZOTOP_PATH_APPS.DS.$dir.DS.'install.php') )
				{
					$install = include(ZOTOP_PATH_APPS.DS.$dir.DS.'install.php');
				}

				// 安装设置文件
				if ( file::exists(ZOTOP_PATH_APPS.DS.$dir.DS.'config.php') )
				{
					$config = @include(ZOTOP_PATH_APPS.DS.$dir.DS.'config.php');
				}

				//写入数据库
				if ( $install )
				{
					$app['id'] 			= strtolower($app['id']);
					$app['dir'] 		= $dir;
					$app['type'] 		= empty($app['type']) ? 'com' : $app['type'];
					$app['status'] 		= 1;
					$app['listorder'] 	= (isset($app['listorder']) and is_int($app['listorder'])) ? $app['listorder'] : $_REQUEST['listorder'];
					$app['installtime'] = ZOTOP_TIME;
					$app['updatetime'] 	= ZOTOP_TIME;

					// 将config写入数据库
					if ( $config and is_array($config) )
					{
						if ( $app['id'] == 'site' )
						{
							$config = array_merge($config, include(ZOTOP_PATH_RUNTIME.DS."site.php"));
						}

						// 写入全部配置数据
						foreach ( $config as $key=>$value )
						{
							$this->db->table('config')->data(array('app'=>$app['id'],'key'=>$key,'value'=>$value))->insert(true);
						}

						// 写入配置文件
						$results = $this->db->table('config')->field('key,value')->where('app',$app['id'])->select();

						if ( is_array($results) )
						{
							foreach( $results as $r )
							{
								$data[$r['key']] = $r['value'];
							}

							zotop::data(ZOTOP_PATH_CONFIG.DS."{$app['id']}.php", $data);
						}
					}

					// 写入根权限
					$this->db->table('admin_priv')->data(array('id'=>$app['id'], 'name'=>$app['name'], 'app'=>$app['id']))->insert(true);

					//写入应用数据
					if ( $this->db->table('app')->data($app)->insert(true) )
					{
						$msg = array('code'=>0, 'message'=>t('应用“$1”安装成功', $app['name'], $app['id']));
					}
				}
			}

			$this->db->commit();
		}
		catch( Exception $e )
		{
			$this->db->rollback();

			$msg = array('code'=>2, 'message'=>t('应用“$1”安装失败',$app['name']), 'detail'=>$e->getMessage().' '.debug::path($e->getFile()).' '.$e->getLine());
		}

		ob_clean();
		exit(json_encode($msg));
	}



	public function success()
	{
		//写入默认管理员
		$admin = include(ZOTOP_PATH_RUNTIME.DS.'admin.php');

		file::delete(ZOTOP_PATH_RUNTIME.DS.'admin.php');

		// 生成密码盐
		$salt = substr(uniqid(rand()), -6);

		$user_data = array(
			'id'         => 1,
			'username'   => $admin['username'],
			'password'   => md5(md5($admin['password']).$salt), //加密密码 对应system.user中的加密方法
			'salt'       => $salt,
			'email'      => $admin['email'],
			'groupid'    => 1, //角色为超级管理员
			'modelid'    => 'admin', //用户类型为系统用户
			'loginip'    => request::ip(),
			'logintime'  => ZOTOP_TIME,
			'logintimes' => 0,
			'disabled'   => 0,
			'updatetime' => ZOTOP_TIME,
			'regtime'    => ZOTOP_TIME,
			'regip'      => request::ip(),
			'nickname'   => $admin['username']
		);

		$admin_data = array(
			'id'		=> 1,
			'realname'	=> $admin['username'],
		);

		$this->db = zotop::db();
		$this->db->table('user')->data($user_data)->insert(true);
		$this->db->table('admin')->data($admin_data)->insert(true);

		//写入锁定文件
		file::put(ZOTOP_PATH_DATA.DS.'install.lock', t('如果需要重装系统请删除此文件'));

		//重新写入应用信息
		$data = $this->db->field('*')->table('app')->orderby('listorder','asc')->select();

		$app = array();

		foreach($data as $d)
		{
			$app[$d['id']] = $d;
		}

		//写入数据
		file::put(ZOTOP_PATH_CONFIG.DS.'app.php', "<?php\nreturn ".var_export($app,true).";\n?>");

		//重启系统
		zotop::reboot();

		include ZOTOP_PATH_INSTALL.DS.'template'.DS.'success.php';
	}
}
?>