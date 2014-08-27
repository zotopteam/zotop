<?php
/**
 * zotop install
 *
 * @package zotop
 * @author zotop team
 * @copyright(c)2009-2012 zotop team
 * @license http://zotop.com/license
 */

// TODO 检查是否支持zip

// 设置默认报告的错误类型
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('ZOTOP_INSTALL',true);
define('ZOTOP_DEBUG',true);
define('ZOTOP_PATH_INSTALL', dirname(__FILE__));


@set_time_limit(1000);

if(phpversion() < '5.3.0') set_magic_quotes_runtime(0);

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
			//echo t('程序已经安装，如果要重新安装请删除%s文件','/'.basename(ZOTOP_PATH_CMS).'/data/install.lock');
			//exit;
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
		if(extension_loaded('gd'))
		{
			if(function_exists('imagepng')) $PHP_GD .= 'png';
			if(function_exists('imagejpeg')) $PHP_GD .= ' jpg';
			if(function_exists('imagegif')) $PHP_GD .= ' gif';
		}

		if(extension_loaded('json'))
		{
			if(function_exists('json_decode') && function_exists('json_encode')) $PHP_JSON = true;
		}

        $PHP_DNS = preg_match("/^[0-9.]{7,15}$/", @gethostbyname('www.zotop.com')) ? true : false;

		// 是否满足zotop安装需求
		$success = (phpversion() >= '5.2.0' && ( extension_loaded('mysql') || extension_loaded('PDO_SQLITE') ) && $PHP_JSON && $PHP_GD) ? true : false;

		// 目录和文件权限
    	$list = array(
    		ZOTOP_PATH => array('name'=>t('根目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_APPS => array('name'=>t('应用目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_UPLOADS => array('name'=>t('文件上传目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_DATA => array('name'=>t('数据目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_CONFIG => array('name'=>t('设置目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_RUNTIME => array('name'=>t('运行时目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_THEMES => array('name'=>t('主题和模板目录'), 'type'=>'folder', 'writable'=>true),
    	);

    	foreach ($list as $f => &$r)
    	{
    		if ( !file_exists($f))
    		{
    			$is_writable = ($r['type'] == 'file') ? (false !== @file_put_contents($f, '')) : folder::create($f, 0777);
    		}
    		else
    		{
    			$is_writable = is_writable($f) && (($r['type'] == 'file') ^ is_dir($f));
    		}
    		
    		$r['is_writable'] = $is_writable;
    		
    		$r['position'] = '/'.str_replace(DS,'/', trim(str_replace(ZOTOP_PATH, '', $f), DS));

    		if ( !$is_writable AND $r['writable'] ) $success = false;
    	}

    	try
    	{
    		folder::create(ZOTOP_PATH_RUNTIME.DS.'caches',0777);
    		folder::create(ZOTOP_PATH_RUNTIME.DS.'session',0777);
    		folder::create(ZOTOP_PATH_RUNTIME.DS.'templates',0777);
    		folder::create(ZOTOP_PATH_RUNTIME.DS.'backup',0777);	
    		folder::create(ZOTOP_PATH_RUNTIME.DS.'temp',0777);
    		folder::create(ZOTOP_PATH_RUNTIME.DS.'block',0777);	
		}
		catch (Exception $e)
		{
			$success = false;
		}	

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
			'driver'			=>'mysql',
			'prefix'			=>'zotop_',
			'charset'			=>'utf8',
			'pconnect'			=>false,
			'mysql_hostname'	=>'localhost',
			'mysql_hostport'	=>3306,
			'mysql_username'	=>'root',
			'mysql_database'	=>'zotop',
			'sqlite_database'	=>'zotop.db3',

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
					if ( zotop::db($config)->create() )
					{
						$msg = array('code'=>1, 'message'=>t('数据库创建成功'));
					}
					elseif ( zotop::db($config)->exists() )
					{
						$msg = array('code'=>0, 'message'=>t('数据库 “%s” 已经存在，是否继续？如果继续系统将会删除原有数据',$mysql_database));
					}
					else
					{
						$msg = array('code'=>2, 'message'=>t('数据库 “%s” 不存在并且无法自动创建，请先创建数据库！', $mysql_database));
					}
				}
				catch(Exception $e)
				{
					$msg = array('code'=>2, 'message'=>t('无法连接数据库服务器，请检查配置！'));
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
					'hostname' 	=> ZOTOP_PATH_DATA,
					'database' 	=> $sqlite_database,
					'prefix' 	=> $prefix,
					'pconnect' 	=> $pconnect,
				);

				try
				{
					if ( zotop::db($config)->create() )
					{
						$msg = array('code'=>1, 'message'=>t('数据库创建成功'));
					}
					elseif ( zotop::db($config)->exists() )
					{
						$msg = array('code'=>0, 'message'=>t('数据库 “%s” 已经存在，是否继续？如果继续系统将会删除原有数据',$sqlite_database));
					}
					else
					{
						$msg = array('code'=>2, 'message'=>t('数据库 “%s” 不存在并且无法自动创建，请先创建数据库！', $sqlite_database));
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
				'theme' 		=> 'default',
				'title' 		=> $site_name,
				'description' 	=> $site_name,
				'keywords' 		=> $site_name,				
				'closed' 		=> '0',
				'closedreason' 	=> t('暂时关闭'),								
			);

			$cookie = array (
				'expire'=>3600,
				'prefix'=>'zotop_',
				'path'=>'/',
				'domain'=>'',
			);

			$session = array (
			    'driver' => '',
				'expire' => 1440,
				'autostart' => true,
				'name' => 'sessionid',
				'path'=>'',
			    'cache_limiter' => 'private_no_expire',
			    'cache_expire' => '30',
			    'user_cookie' => true,
				//'cookie_domain' => '',
			    //'cookie_path' => '',
			    //'cookie_expire' => '0',
			);			

			//写入默认数据库配置文件
			file::put(ZOTOP_PATH_CONFIG.DS.'database.php', "<?php\nreturn ".var_export(array('default'=>$config),true).";\n?>");
			
			// 写入站点配置
			file::put(ZOTOP_PATH_CONFIG.DS.'site.php', "<?php\nreturn ".var_export($site,true).";\n?>");

			file::put(ZOTOP_PATH_CONFIG.DS.'cookie.php', "<?php\nreturn ".var_export($cookie,true).";\n?>");

			file::put(ZOTOP_PATH_CONFIG.DS.'session.php', "<?php\nreturn ".var_export($session,true).";\n?>");			

			// 写入账户信息
			zotop::cookie('admin', array(
				'username' => $admin_username,
				'password' => $admin_password,
				'email' => $admin_email,
			));
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

		//==========================================
		//===========安装当前应用===================
		//==========================================
		try
		{
			$this->db->begin();

			//获取待安装的应用目录
			$dir = $_POST['app'];

			$install = true;

			// 获取应用的数据信息
			$app = @include(ZOTOP_PATH_APPS.DS.$dir.DS.'app.php');

			if ( !is_array($app) OR empty($app['id']) OR empty($app['name']) OR empty($app['version']) )
			{
				$msg = array('code'=>2, 'message'=>t('错误的应用文件，请检查:%s',ZOTOP_PATH_APPS.DS.$dir.DS.'app.php'));
			}
			else
			{
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
					$app['type'] 		= empty($app['type']) ? 'com' : $app['type'];
					$app['status'] 		= 1;
					$app['listorder'] 	= (isset($app['listorder']) and is_int($app['listorder'])) ? $app['listorder'] : $_REQUEST['listorder'];
					$app['installtime'] = ZOTOP_TIME;
					$app['updatetime'] 	= ZOTOP_TIME;

					// 将config写入数据库
					if ( $config and is_array($config) )
					{
						// 写入全部配置数据
						foreach ( $config as $key=>$value )
						{
							$this->db->from('config')->set(array('app'=>$app['id'],'key'=>$key,'value'=>$value))->insert(true);
						}

						// 写入配置文件
						$results = $this->db->from('config')->select('key,value')->where('app',$app['id'])->getAll();

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
					$this->db->insert('admin_priv', array('id'=>$app['id'], 'name'=>$app['name'], 'app'=>$app['id']));

					//写入应用数据
					if ( $this->db->insert('app', $app, true) )
					{
						$msg = array('code'=>0, 'message'=>t('应用 “ %s ” 安装成功……',$app['name'],$app['id']));
					}
				}
			}

			$this->db->commit();
		}
		catch( Exception $e )
		{
			$this->db->rollback();

			$msg = array('code'=>2, 'message'=>$e->getMessage());
		}

		ob_clean();

		exit(json_encode($msg));

	}



	public function success()
	{
		//插入默认管理员
		$admin = zotop::cookie('admin');

		$user_data = array(
			'id'		 => 1,
			'username'	 => $admin['username'],
			'password'	 => md5($admin['password']), //admin999
			'email'		 => $admin['email'],
			'groupid'	 => 1, //角色为超级管理员
			'modelid'	 => 'admin', //用户类型为系统用户
			'loginip'	 => request::ip(),
			'logintime'	 => ZOTOP_TIME,
			'logintimes' => 0,
			'disabled'	 => 0,
			'updatetime' => ZOTOP_TIME,
			'regtime'	 => ZOTOP_TIME,
			'regip'		 => request::ip(),
			'nickname'	 => $admin['username']
		);

		$admin_data = array(
			'id'		=> 1,
			'realname'	=> $admin['username'],
		);

		$this->db = zotop::db();
		$this->db->insert('user',$user_data,true);
		$this->db->insert('admin',$admin_data,true);

		//写入锁定文件
		file::put(ZOTOP_PATH_DATA.DS.'install.lock', t('如果需要重装系统请删除此文件'));

		//重新写入应用信息
		$data = $this->db->select('*')->from('app')->orderby('listorder','asc')->getAll();

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