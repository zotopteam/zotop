<?php
/**
 * zotop core
 *
 * @package zotop
 * @author zotop team
 * @copyright(c)2009-2012 zotop team
 * @license http://zotop.com/license
 */

// PHP版本检测
version_compare(PHP_VERSION, '5.0.0', '<') and exit("Sorry, this version of zotop will only run on PHP version 5 or greater!\n");

// 设置默认报告的错误类型
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//输出页面字符集
header('Content-type: text/html; charset=utf-8');

// 启动数据
define('ZOTOP',           true);
define('DS',              DIRECTORY_SEPARATOR);
define('ZOTOP_TIME',      time());
define('ZOTOP_CHARSET',   'utf-8'); // 网站字符集，默认为utf8

define('ZOTOP_BEGIN_TIME',      microtime(true)); // 开始时间
define('ZOTOP_START_MEMORY',    memory_get_usage()); // 定义启动时占用的内存
define('ZOTOP_MAGIC_QUOTES',    (bool)get_magic_quotes_gpc()); // 是否开启magic_quotes

// 定义系统路径
define('ZOTOP_PATH',            dirname(dirname(__file__)));
define('ZOTOP_PATH_CMS',        dirname(__file__));
define('ZOTOP_PATH_APPS',       ZOTOP_PATH_CMS . DS . 'apps');
define('ZOTOP_PATH_LIBRARIES',  ZOTOP_PATH_CMS . DS . 'libraries');
define('ZOTOP_PATH_DATA',       ZOTOP_PATH_CMS . DS . 'data');
define('ZOTOP_PATH_CONFIG',     ZOTOP_PATH_CMS . DS . 'config');
define('ZOTOP_PATH_THEMES',     ZOTOP_PATH_CMS . DS . 'themes');
define('ZOTOP_PATH_RUNTIME',    ZOTOP_PATH_CMS . DS . 'runtime');
define('ZOTOP_PATH_CACHE',      ZOTOP_PATH_RUNTIME . DS . 'caches');
define('ZOTOP_PATH_UPLOADS',    ZOTOP_PATH . DS . 'uploads');


// 定义URL
define('ZOTOP_URL',             trim(dirname($_SERVER['SCRIPT_NAME']), DS)); //如果为根目录则为空
define('ZOTOP_URL_CMS',         ZOTOP_URL . '/'.basename(dirname(__FILE__)));
define('ZOTOP_URL_APPS',        ZOTOP_URL_CMS . '/apps');
define('ZOTOP_URL_THEMES',      ZOTOP_URL_CMS . '/themes');
define('ZOTOP_URL_UPLOADS',     ZOTOP_URL . '/uploads');

// 调试模式和跟踪模式，默认关闭调试和跟踪模式
defined('ZOTOP_DEBUG') or define('ZOTOP_DEBUG', false);
defined('ZOTOP_TRACE') or define('ZOTOP_TRACE', false);

// 系统启动
zotop::init();


/**
 * 系统主类，包含系统最常用的函数
 *
 * @package zotop
 * @author zotop team
 * @copyright(c)2009-2011 zotop team
 * @license http://zotop.com/license.html
 */
class zotop
{

    /**
     *
     * @var string 当前uri
     */
    public static $uri;

    /**
     *
     * @var array 类映射数组，用于注册和自动加载类
     */
    public static $classes = array();

    /**
     *
     * @var array 已加载类数组
     */
    public static $loads = array();

    /**
     *
     * @var array 事件数组，用于hook
     */
    public static $events = array();

    /**
     *
     * @var array 事件数组，用于hook
     */
    public static $config = array();   

    /**
     * @var boolean 系统是否初始化
     */
    protected static $init = false;

    /**
     * 系统初始化
     *
     * @return void
     */
    public static function init()
    {
        if (zotop::$init) return;

        // 注册自动加载函数
        spl_autoload_register(array('zotop', 'autoload'));

        // Sanitize all request variables
        $_GET    = zotop::sanitize($_GET);
        $_POST   = zotop::sanitize($_POST);
        $_COOKIE = zotop::sanitize($_COOKIE);

        // 设置系统事件
        zotop::add('zotop.boot',    array('application', 'boot'));
        zotop::add('zotop.boot',    array('application', 'finduri'));
        zotop::add('zotop.route',   array('router', 'init'));
        zotop::add('zotop.route',   array('router', 'route'));
        zotop::add('zotop.execute', array('application', 'execute'));
        zotop::add('zotop.render',  array('application', 'render'));

        //加载核心文件
        if ( file_exists(ZOTOP_PATH_RUNTIME . DS . "preload.php") && !ZOTOP_DEBUG )
        {
            // 预加载配置
            zotop::config(include(ZOTOP_PATH_RUNTIME . DS . "config.php"));

            // 预加载文件
            zotop::load(ZOTOP_PATH_RUNTIME . DS . "preload.php");

            // 加载全局文件
            zotop::load(ZOTOP_PATH_RUNTIME . DS . "global.php");
        }
        elseif ( file_exists(ZOTOP_PATH . DS . 'install' . DS . 'index.php') && !file_exists(ZOTOP_PATH_DATA . DS . "install.lock") && !defined('ZOTOP_INSTALL'))
        {
            //进入安装模式
            header('location:'. ZOTOP_URL . '/install/index.php');
        }
        else
        {
            // 如果不存在核心文件或者debug模式，自动生成runtime
            zotop::load(ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . "runtime.php") and runtime::build();
        }    

        zotop::$init = true;        
    }

    /**
     * 系统运行
     *
     * @param string $uri 默认执行的uri，如：home
     * @return void
     */
    public static function boot($uri = 'system/index')
    {
        // 设置全局uri值
        zotop::$uri = trim(trim($uri), '/');

        // 系统运行
        zotop::run('zotop.boot');
        zotop::run('zotop.route');
        zotop::run('zotop.ready');
        zotop::run('zotop.execute');
        zotop::run('zotop.render');
    }

    /**
     * 系统重新启动，删除核心缓存文件
     *
     * @return string
     */
    public static function reboot()
    {

        // 系统重启接口
        zotop::run('zotop.reboot');

        // 删除核心缓存
        @unlink(ZOTOP_PATH_RUNTIME . DS . 'preload.php');
        @unlink(ZOTOP_PATH_RUNTIME . DS . 'global.php');
        @unlink(ZOTOP_PATH_RUNTIME . DS . 'config.php');

        return true;
    }

    /**
     * 类注册，用于自动加载文件
     *
     * @param mixed $name 类的名称
     * @param string $file 类对应的文件
     * @return array string
     */
    public static function register($name = '', $file = '')
    {
        if ( $name === null )
        {
            zotop::$classes = array();
        }

        if ( is_array($name) )
        {
            zotop::$classes = array_merge(zotop::$classes, array_change_key_case($name));
        }

        if ( is_string($name) and $name = strtolower($name) )
        {
            // 删除已经注册的类
            if ($file === null)
            {
                unset(zotop::$classes[$name]);
            }

            // 获取类的路径
            if (empty($file))
            {
                return isset(zotop::$classes[$name]) ? zotop::$classes[$name] : false;
            }

            // 注册单个类
            if (is_string($file))
            {
                zotop::$classes[$name] = $file;
            }
        }

        return zotop::$classes;
    }

    /**
     * load 用于加载文件,相当于require_once，不返回任何错误
     *
     * @param string $file 要加载的文件路径
     * @return bool
     */
    public static function load($file)
    {
        if ( isset(zotop::$loads[$file]) ) return true;

		if ( file_exists($file) )
        {
            require $file;
            zotop::$loads[$file] = true;
            return true;
        }

        return false;
    }

    /**
     * 自动加载，用于自动加载系统的类
     *
     * @param string $class 类名
     * @return bool
     */
    public static function autoload($class)
    {
        // 如果已经加载过则直接返回
        if (class_exists($class, false)) return true;

        // 如果存在该类的注册，则加载该类
        if ( $file = zotop::register($class) )
        {
            return zotop::load($file);
        }

        return false;
    }

    /**
     * Recursively sanitizes an input variable:
     *
     * - Strips slashes if magic quotes are enabled
     * - Normalizes all newlines to LF
     *
     * @param mixed any variable
     * @return mixed sanitized variable
     */
    public static function sanitize($value)
    {
        if (is_array($value) or is_object($value))
        {
            foreach ($value as $key => $val)
            {
                // Recursively clean each value
                $value[$key] = zotop::sanitize($val);
            }
        }
        elseif (is_string($value))
        {
            if (ZOTOP_MAGIC_QUOTES === true)
            {
                // Remove slashes added by magic quotes
                $value = stripslashes($value);
            }

            if (strpos($value, "\r") !== false)
            {
                // Standardize newlines
                $value = str_replace(array("\r\n", "\r"), "\n", $value);
            }
        }

        return $value;
    }

    /**
     * 获取特定的事件的函数集
     *
     * @param string $name 事件的名称，如果为空则返回全部事件
     * @return array 事件函数集
     */
    public static function event($name = '')
    {
        if (empty($name))
        {
            return self::$events;
        }

        return empty(self::$events[$name]) ? array() : self::$events[$name];
    }

    /**
     * 在末尾添加一个新的回调函数
     *
     * @param $name
     * @param $callback
     * @return boolean
     */
    public static function add($name, $callback)
    {
        if (!isset(self::$events[$name]))
        {
            self::$events[$name] = array();
        }
        elseif (in_array($callback, self::$events[$name], true))
        {
            return false;
        }

        self::$events[$name][] = $callback;

        return true;
    }

    /**
     * 从事件集合中删除特定的事件或者事件组
     *
     * @param string $name 事件名称
     * @param string $callback
     * @return null
     */
    public static function remove($name, $callback = false)
    {
        if ($callback === false)
        {
            self::$events[$name] = array();
        }
        elseif (isset(self::$events[$name]))
        {
            foreach (self::$events[$name] as $i => $event_callback)
            {
                if ($callback === $event_callback)
                {
                    unset(self::$events[$name][$i]);
                }
            }
        }
        return true;
    }

    /**
     * 在特定的事件组的特定位置插入事件
     *
     * @param string $name 事件组名称
     * @param int $key
     * @param array $callback 被插入的事件
     * @return boolean 插入是否成功
     */
    public static function insert($name, $key, $callback)
    {
        if (in_array($callback, self::$events[$name], true))
        {
            return false;
        }

        self::$events[$name] = array_merge(array_slice(self::$events[$name], 0, $key), array($callback), array_slice(self::$events[$name], $key));

        return true;
    }

    /**
     * 在特定的事件组前插入特定的事件
     *
     * zotop::before('system.routing', array('router', 'boot'), array('myrouter', 'setup'));
     *
     * @param string $name 事件名称
     * @param array $existing 函数
     * @param array $callback 插入的函数
     * @return boolean 返回插入是否成功
     */
    public static function before($name, $existing, $callback)
    {
        if (empty(self::$events[$name]) or ($key = array_search($existing, self::$events[$name])) === false)
        {
            return self::add($name, $callback);
        }
        return self::insert($name, $key, $callback);
    }

    /**
     * 在特定的事件组后插入特定的事件
     *
     * zotop::after('system.routing', array('router', 'boot'), array('myrouter', 'setup'));
     *
     * @param string $name 事件名称
     * @param array $existing 函数
     * @param array $callback 插入的函数
     * @return boolean 返回插入是否成功
     */
    public static function after($name, $existing, $callback)
    {
        if (empty(self::$events[$name]) or ($key = array_search($existing, self::$events[$name])) === false)
        {
            return self::add($name, $callback);
        }
        return self::insert($name, $key + 1, $callback);
    }

    /**
     * 替换事件
     *
     * @param string $name 事件组名称
     * @param array $existing 被替换的事件
     * @param array $callback 新的事件,callback 为空时直接将$name 事件组替换为 $existing 事件
     * @return boolean 替换是否成功
     */
    public static function replace($name, $existing, $callback = '')
    {
        if (empty(self::$events[$name]) || empty($callback))
        {
            self::remove($name);

            self::add($name, $existing);

            return true;
        }

        if (empty(self::$events[$name]) or ($key = array_search($existing, self::$events[$name], true)) === false)
        {
            return false;
        }

        if (!in_array($callback, self::$events[$name], true))
        {
            self::$events[$name][$key] = $callback;
        }
        else
        {
            unset(self::$events[$name][$key]);
            self::$events[$name] = array_values(self::$events[$name]);
        }

        return true;
    }

    /**
     * 运行一个事件，并传入相关参数，结果是每次运行函数的和
     *
     * @param string $name Event Name
     * @param array $args 相关参数
     * @return bool 如果运行了事件就返回真，否则返回假
     */
    public static function run($name, &$data = '')
    {
        if ($callbacks = self::event($name))
        {
            $str = '';

            $args = func_get_args();

            foreach ($callbacks as $callback)
            {
                call_user_func_array($callback, array_slice($args, 1));
            }
            return true;
        }
        return false;
    }

    /**
     * 运行一系列的对$var的处理函数，最终返回的还是$var,此函数与run相似，只是功能不同，返回的结果不同
     *
     * @param string $name 事件 的 名称
     * @param mix $value 待处理的数据，可以使string，也可以是array或者其它数据
     * @return mix 处理后的$value
     */
    public static function filter($name, $value)
    {
        if ($callbacks = self::event($name))
        {
            // 处理可能的传入的多个参数,其他参数为辅助参数
            $args = func_get_args();

            foreach ($callbacks as $callback)
            {
                $args[1] = $value;
                $value = call_user_func_array($callback, array_slice($args, 1));
            }
        }
        return $value;
    }

    /*
    * 获取客户端使用的语言
    */
    public static function language()
    {
        $language = c('system.language');

        if (empty($language))
        {
            preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
            $language = strtolower($matches[1]);
        }

        return $language;
    }



    /**
     * 根据参数生成完整的URL，支持路由
     *
     * 路由规则不存在，直接生成
     *
     * u('app/controller/action',array('param1'=>'1')) => /index.php/app/controller/action?param1=1&param2=2#anchor
     *
     * 路由规则存在：
     *
     * 'contents'=>'content/list' 解析: u('content/list') => /index.php/contents
     *
     * 'content/<id:\d+>'=>'content/detail/<id>' 解析：u('content/detail/121') => /index.php/content/121
     *
     * 'content/<year:\d{4}>/<title>'=>'content/detail', 解析：u('content/detail',array('year'=>2008,'title'=>'test-title')) => /index.php/content/2008/test-title
     *
     * url格式：
     *
     * u('content/detail',array('year'=>2008,'title'=>'test-title')) 等同于 u('content/detail?year=2008&title=test-title'))
     *
     *
     * @param string 		$uri 		如：{app}/{controller}/{action}?[params]#anchor
     * @param array|string 	$params 	URL参数 ，一般为数组,也可以是字符串，如：param1=1&param2=2
     * @param bool|string 	$host	 	域名，如果host为true，则默认返回当前的host信息
     *
     * @return string
     */
    public static function url($uri = '', $params = array(), $host = false)
    {
        // 支持外部链接
        if ( strpos($uri, '://') ) return $uri;

        // 分解uri app/controller/action?param1=1&param2=2#anchor
        if ( strpos($uri, '#') ) list($uri, $anchor) = explode('#', $uri);
        if ( strpos($uri, '?') ) list($uri, $querys) = explode('?', $uri);

        //处理 string 类型 params 如：param1=1&param2=2 TODO parse_str 会导致rawurlencode()失效
        if ( is_string($params) ) parse_str($params, $params);
		if ( !is_array($params) ) $params = array();
		if ( $querys )
		{
			parse_str($querys, $querys);
			$params = array_merge($querys, $params);
		}

        if ( $anchor ) $anchor = '#'.$anchor;

        // 默认为当前的HOST
        $url = empty($host) ? '' : ($host === true ? request::host() : $host);

        // rewrite返回basepath，默认返回scriptname http://127.0.0.1/ 或者 http://127.0.0.1/index.php
        $url .= ( c('system.url_model') == 'rewrite' ) ? request::basepath() : request::scriptname();

        // 接入路由转换
		foreach ( router::$rules as $pattern => $route )
		{
			if ( ( $u = router::createUrl($pattern, $route, $uri, $params) ) !== false )
			{
                return $route['host'] ? ($u==='' ? '/' : $u).$anchor : trim($url,'/').'/'.$u.$anchor;
			}
		}

		// 链接模式
        if ( $uri = trim($uri, '/') )
        {
            switch ( c('system.url_model') )
            {
                case 'rewrite':
                    $url .= $uri . c('system.url_suffix');
                    break;
                case 'pathinfo':
                    $url .= '/' . $uri . c('system.url_suffix');
                    break;                
                default:
                    $url = '?' . c('system.url_var') . '=' . $uri;
                    break;
            }
        }

        // 参数
        if ( $params = http_build_query($params) )
        {
            $url .= strpos($url, '?') ? '&' . $params : '?' . $params;
        }

        return $url.$anchor;
    }

    /**
     * 加密函数
     *
     * @param $string 字符串
     * @param $string 密钥
     * @param $expire 有效时间
     * @return string
     */
    public static function encode($str, $key = '', $expire = 0)
    {
        return zotop::authcode($str, 'ENCODE', $key, $expire);
    }

    /**
     * 解密函数
     *
     * @param $string 字符串
     * @param $string 密钥
     * @param $expire 有效时间
     * @return string
     */
    public static function decode($str, $key = '', $expire = 0)
    {
        return zotop::authcode($str, 'DECODE', $key, $expire);
    }


    /**
     * 认证函数，来自discuz
     *
     */
    protected static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $key = md5($key ? $key : C('system.safekey'));
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++)
        {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE')
        {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16))
            {
                return substr($result, 26);
            }
            else
            {
                return '';
            }
        }
        else
        {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * 从数组文件中获取配置参数
     *
     * @param string $name 要获取的配置项
     * @return mix
     */
    public static function config($name=null, $value=null, $default=null)
    {
        if ( empty($name) ) return zotop::$config;

        // 合并数组
        if ( is_array($name) )
        {
            zotop::$config = array_merge(zotop::$config, array_change_key_case($name));

            return zotop::$config;
        }		

        // 传入参数 system.language 或者 custom 
        if ( is_string($name) )
        {
            if ( strpos($name, '.') )
            {
                list($name, $key) = explode('.', strtolower($name));
            }
            else
            {
                $key = null;
            }

            // 如果没有相关数据，尝试从配置中获取
            if ( !isset(zotop::$config[$name]) )
            {
                $path = ZOTOP_PATH_CONFIG . DS . "{$name}.php";

                if ( is_file($path) )
                {
                    zotop::$config[$name] = include($path);
                }
            }

            if ( $key )
            {
                if ( is_null($value) )
                {
                    return isset(zotop::$config[$name][$key]) ? zotop::$config[$name][$key] : $default;
                }

                zotop::$config[$name][$key] = $value;
            }
            else
            {
                if ( is_null($value) )
                {
                    return isset(zotop::$config[$name]) ? zotop::$config[$name] : $default;
                }

                zotop::$config[$name] = $value;  
            }       
            
        }

        return $value;
    }

    /**
     * 应用的配置获取
     *
     * 获取应用全部信息 : zotop::app('system');
     * 获取应用全部路径 : zotop::app('system.path');
     * 获取应用全部url : zotop::app('system.url');
     *
     *
     * @param string|array $key 应用的id,或者应用属性
     * @param string $key 键名称，如：name
     * @return mix
     */
    public static function app($key = null)
    {
        static $app = array();

        if ( empty($app) )
        {
            $app = zotop::config('app');


            foreach ($app as $k => &$a)
            {
                $a['dir']	= empty($a['dir']) ? $k : $a['dir'];
                $a['path']	= ZOTOP_PATH_APPS . DS . $a['dir'];
                $a['url']	= ZOTOP_URL_APPS . '/' . $a['dir'];
            }
        }

        // 空值，返回全部应用数据
        if (empty($key)) return $app;

        // 字符串
        if (is_string($key))
        {
            list($id, $key) = explode('.', strtolower($key));

            // 应用不存在
            if (!isset($app[$id])) return false;

            // 返回应用数据
            if (empty($key)) return $app[$id]['status'] ?  $app[$id] : array();

            // 返回指定键值
            return $app[$id][$key];
        }

        return false;
    }

    /**
     * 取得对象的唯一实例
     *
     * @param string $class 对象类名
     * @param string $method 类的静态方法名
     * @return object
     */
    public static function instance($class)
    {
        static $instances = array();

        // 唯一id
        $identify = md5($class);

        // 实例化
        if (!isset($instances[$identify]))
        {
            if (class_exists($class))
            {
                $instance = new $class();
                $instances[$identify] = $instance;
            }
        }

        return $instances[$identify];
    }

    /**
     * 增强型GET
     *
     * @param $name string 参数名称
     * @param $default mix 赋值
     *
     * @return mix
     */
    public static function get($name = '', $default = null)
    {
        if (empty($name)) return $_GET;

        if (isset($_GET[$name]))
        {
            return is_string($_GET[$name]) ? trim($_GET[$name]) : $_GET[$name];
        }

        return $default;
    }

    /**
     * 增强型POST
     *
     * @param $name string 参数名称
     * @param $valid string 有效性验证
     * @param $default mix 赋值
     *
     * @return mix
     */
    public static function post($name = '', $default = null)
    {
        if (empty($name)) return $_POST;

        if (isset($_POST[$name]))
        {
            return is_string($_POST[$name]) ? trim($_POST[$name]) : $_POST[$name];
        }

        return $default;
    }

    /**
     * 增强型COOKIE函数
     *
     * @code
     *
     * zotop::cookie('cookiename'); //获取cookie
     * zotop::cookie('cookiename','cookievalue', 10); //设置cookie
     * zotop::cookie('cookiename',null); //清除名字为cookiename的cookie
     * zotop::cookie(null); //清除全部cookie
     *
     * @endcode
     *
     * @param string $name 名称
     * @param mix $value 值
     * @param int $expire 有效期，单位为秒
     * @param string $path string 路径
     * @param string $domain 域名
     *
     * @return mix
     */
    public static function cookie($name, $value = '', $expire = null, $path = null, $domain = null)
    {
        $expire = is_null($expire) ? C('cookie.expire') : $expire;
        $path   = is_null($path) ? C('cookie.path') : $path;
        $domain = is_null($domain) ? C('cookie.domain') : $domain;   

        // 清除全部cookie
        if ( is_null($name) )
        {
            unset($_COOKIE);
            return true;
        }

        $name = md5(C('cookie.prefix').$name);

        // 清理单个cookie
        if ( $value === null )
        {
            unset($_COOKIE[$name]);
            return setcookie($name, '', time() - 3600, $path, $domain);
        }

        // 获取单个cookie
        if ( $value === '' )
        {
            // 检查cookie是否存在
            if ( 0 === strpos($name, '?') )
            {
                return isset($_COOKIE[substr($name, 1)]);
            }

            if ( isset($_COOKIE[$name]) )
            {
                return unserialize(base64_decode($_COOKIE[$name]));
            }

            return null;
        }

        // 设置cookie
        $value  = base64_encode(serialize($value));
        $expire = empty($expire) ? 0 : intval($expire) + time();
        
               
        if ( setcookie($name, $value, $expire, $path, $domain) )
        {
            $_COOKIE[$name] = $value;
            
            return true;
        }

        return false;
    }

    /**
     * session
     *
     * @code
     *
     * zotop::session('name'); //session
     * zotop::session('?name'); //检查session是否存在
     * zotop::session('name','value'); //session
     * zotop::session('name',null); //清除名字为ame的session
     * zotop::session(null); //清除全部session
     *
     * @endcode
     *
     * @param string|array $name 字符串为session名称， 数组则为session初始化
     * @param mixed $value session值
     * @return mixed
     */
    public static function session($name, $value = '')
    {
        session::instance(C('session'));

        if ( $value === '' )
        {
            if ( strpos($name, '?') === 0 ) return isset($_SESSION[substr($name, 1)]); // 检查session
            if ( $name === null ) $_SESSION = array(); // 清空session
            if ( $name == '[id]' ) return session_id(); // 暂停session
            if ( $name == '[pause]' ) session_write_close(); // 暂停session
            if ( $name == '[start]' ) session_start(); // 启动session
            if ( $name == '[regenerate]' ) session_regenerate_id(); // 重新生成id
            if ( $name == '[destroy]' ) // 销毁session
            {
                $_SESSION = array();
                session_unset();
                session_destroy();
            }

            // 返回session
            return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        }
        elseif ($value === null)
        {
            unset($_SESSION[$name]); //销毁单个session
        }

        $_SESSION[$name] = $value; // 存储session
    }

    /**
     * 读取存储的当前用户信息
     *
     * @param mixed $key 读取的键
     * @return mixed
     */
    public static function user($key = '')
    {
        static $user = array();

        //默认加载用户cookie
        if (!isset($user) or empty($user))
        {
            if (stristr($_SERVER['HTTP_USER_AGENT'], " flash") and isset($_REQUEST['auth']) and zotop::cookie('auth') === null)
            {
                $authcookie = $_REQUEST['auth'];
            }
            else
            {
                $authcookie = zotop::cookie('auth');
            }

            list($id, $username, $password, $groupid, $modelid) = explode("\t", zotop::decode($authcookie));

            $user = is_numeric($id) ? compact('id', 'username', 'password', 'groupid', 'modelid') : array();
        }


        //$key='' 则返回用户信息数组
        if (empty($key)) return $user;

        //$key=string,返回用户的{$key}信息,如：id
        return isset($user[strtolower($key)]) ? $user[strtolower($key)] : null;
    }

     /**
      * 文件存储数据，支持类型数据为：字符串，数组
      *
      * <code>
      *
      * zotop::data($file, $data); //存储数据
      * zotop::data($file); //获取数据
      * zotop::data($file, null); //清空数据
      *
      * </code>
      *
      * @param string $file 文件路径
      * @param mix $data 缓存数据  *
      * @return mix
      */
    public static function data($file, $data='')
    {
        static $files = array();

        //删除缓存
        if ( $data === null )
        {
            if ( $result = file::delete($file) )
            {
                unset($files[$file]);
            }

            return $result;
        }

        //存储数据
        if( $data !== '' )
        {
            $data = "<?php\nreturn ".var_export($data,true).";\n?>";
            file::put($file, $data);
            $files[$file] = $data;
            return true;
        }

        //已读数据
        if ( isset($files[$file]) ) return $files[$file];

        //获取数据
        if ( is_file($file) )
        {
            $data = include($file);
            $files[$file] = $data;
            return $data;
        }

        return false;
    }    

    /**
     * 缓存
     *
     * @param string $id content.list 或者 list
     * @param mix $data 值
     * @param int|true $expire 缓存时间,单位秒，默认使用系统设置的缓存时间
     *
     * @return mix
     */
    public static function cache($id, $data = '', $expire = null)
    {
        $cache = cache::instance();

        // 清理全部缓存
        if ( $id === null ) 
        {
            return $cache->clear();
        }

        // 删除指定缓存
        if ( $data === null )
        {
            return $cache->delete($id);
        } 

        // 获取缓存
        if ( $data === '' )
        {
           return $cache->get($id); 
        } 

        // 存储缓存
        return $cache->set($id, $data, $expire);
    }

    /**
     * 连接数据库
     *
     * @param $config string|array 数据库参数
     * @return object 数据库连接
     */
    public static function db($config = 'default')
    {
        if (is_string($config))
        {
			$database = zotop::config('database');

            if (empty($database) or !is_array($database[$config]))
            {
                throw new zotop_exception(t('没有找到数据库配置“%s”', $config));
            }

            $config = $database[$config];
        }

        return database::instance($config);
    }


    /**
     * 在页面底部显示powered by zotop信息
     *
     * @param bool $runtime  是否显示运行时信息
     * @return string
     */
    public static function powered($runtime=false)
    {
        $powered = $runtime ? 'Powered by {zotop} <span>runtime:{runtime} S, memory:{memory} M, include: {include}, DB: {db}</span>' : 'Powered by {zotop}';

        return t($powered, array(
            'zotop'     => '<a href="http://www.zotop.com/" target="_blank" rel="external">zotop v' . c('zotop.version') . '</a>',
            'runtime'   => number_format(microtime(true) - ZOTOP_BEGIN_TIME, 6),
            'memory'    => number_format((memory_get_usage() - ZOTOP_START_MEMORY) / 1024 / 1024, 6),
            'include'   => count(get_included_files()),
			'db'        => n('db'),
        ));
    }
}
?>