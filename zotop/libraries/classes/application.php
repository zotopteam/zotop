<?php
defined('ZOTOP') or die('No direct script access.');

/**
 * application
 *
 * @package zotop
 * @author zotop team
 * @copyright (c)2009-2012 zotop team
 * @license http://zotop.com/license
 */
class application
{

    /**
     * 应用启动函数
     * 默认 zotop::run('zotop.boot')时运行
     */
    public static function boot()
    {
        // 设定错误和异常处理,定义错误句柄，将php错误转化为异常
        register_shutdown_function(array('application', 'shutdown_handler'));
        set_error_handler(array('application', 'error_handler'));
        set_exception_handler(array('application', 'exception_handler'));

        // 时区设置
        if (function_exists('date_default_timezone_set'))
        {
            // 网站时区，Etc/GMT-8 实际表示的是 GMT+8,北京时间
            $timezone = c('system.locale_timezone');
            $timezone = 'Etc/GMT' . ($timezone > 0 ? '-' : '+') . (abs($timezone));
            date_default_timezone_set($timezone);
        }

        // 设置输出缓存
        ob_start(array('application', 'output_buffer'));
    }

    /**
     * 获取url地址中的uri部分
     *
     * 兼容模式：返回url中的uri参数，默认为r，index.php?r=content/index/1 返回 zotop/index/1
     * PATHINFO模式（默认模式）：返回url中的pathinfo部分，如 /index.php/content/index/1 返回 zotop/index/1
     *
     * @return $string
     */
    public static function finduri()
    {
        // 默认uri参数
        $var = c('system.url_var');

        // 判断URL里面是否有兼容模式参数
        if (!empty($_GET[$var]))
        {
            //设置pathinfo
            $_SERVER['PATH_INFO'] = $_GET[$var];

            //将uri从参数中去除
            unset($_GET[$var]);
        }

        // 获取pathinfo
        foreach (array('PATH_INFO','ORIG_PATH_INFO','PHP_SELF') as $v)
        {
            if (isset($_SERVER[$v]) and $_SERVER[$v])
            {
                $uri = $_SERVER[$v];
                break;
            }
        }

        // 处理获得的uri，去除其中的SCRIPT_NAME部分
        if (isset($_SERVER['SCRIPT_NAME']) and $_SERVER['SCRIPT_NAME'])
        {
            if (strncmp($uri, $_SERVER['SCRIPT_NAME'], strlen($_SERVER['SCRIPT_NAME'])) === 0)
            {
                $uri = (string )substr($uri, strlen($_SERVER['SCRIPT_NAME']));
            }
        }

        // 清理多余的斜线及后缀
        if ($uri = trim($uri, '/'))
        {
            if ($suffix = c('system.url_suffix'))
            {
                // 去除后缀
                $uri = preg_replace('#' . preg_quote($suffix) . '$#u', '', $uri);
            }

            // 删除多余斜线
            $uri = preg_replace('#//+#', '/', $uri);
        }

        zotop::$uri = empty($uri) ? zotop::$uri : $uri;
        zotop::$uri = strtolower(trim(trim(zotop::$uri), '/'));
    }

    /**
     * 执行程序
     * 分解zotop::$uri并执行当前的 app/controller/action/params
     * 默认 zotop::run('zotop.execute')时运行
     *
     */
    public static function execute()
    {
        define('ZOTOP_URI', trim(trim(zotop::$uri), '/'));

        // 分解uri
        $arguments	= explode('/', ZOTOP_URI);
        $app		= array_shift($arguments);
        $controller = array_shift($arguments);
        $action		= array_shift($arguments);

        // 检查应用是否存在以及是否被启用
        if ( !preg_match("/^[0-9a-z_]+\$/i", $app) or A($app) == false or intval(A("{$app}.status")) < 1)
        {
            throw new zotop_exception(t('The app [ %s ] has no access or not exists', $app), 404);
        }

        // 应用初始化
        zotop::run('app.init',$app,$controller,$action,$arguments);

        // 检查controller
        $controller			= preg_match("/^[0-9a-z_]+\$/i", $controller) ? $controller : 'index';
        $controller_path	= A("{$app}.path") . DS . 'controllers' . DS . $controller . '.php';
        $controller_class	= "{$app}_controller_{$controller}";

        // 控制器存在，并且存在控制器类
        if ( zotop::load($controller_path) and class_exists($controller_class, false) )
        {
            // 实例化控制器
            $c = new $controller_class();

            // 当在控制器中找不到当前传入的动作，则调用默认动作，并将动作作为参数传入，如：system/act/3/1 参数为：3,1
            if ( !method_exists($c, 'action_' . $action) )
            {
                if (strlen($action) > 0)
                {
                    $arguments = array_merge(array($action), $arguments);
                }

                // 获取控制器的默认动作
                $action = $c->default_action;
            }

            //定义app，controller，action常量
            define('ZOTOP_APP', $app);
            define('ZOTOP_CONTROLLER', $controller);
            define('ZOTOP_ACTION', $action);

            //执行init动作
            call_user_func_array(array($c, '__init'), $arguments);

            // 执行动作
            if ( method_exists($c, 'action_' . $action) )
            {
                call_user_func_array(array($c, 'action_' . $action), $arguments);
            }
            else
            {
                call_user_func_array(array($c, '__empty'), array($action, $arguments));
            }

            unset($c);
        }
		else
		{
			throw new zotop_exception(t('The system is unable to find the requested controller or class :"%s"', debug::path($controller_path)), 404);
		}
    }

    /**
     * 渲染输出内容,并输出缓冲区内容
     * 默认 zotop::run('zotop.render')时运行
     *
     */
    public static function render()
    {
        //获取页面内容
        $output = ob_get_contents();

        //清理输出数据
        ob_clean();

        //执行zotop.output 并输出页面内容
        echo zotop::filter('zotop.output', $output);
        unset($output);

        //exit(1);
    }

    public static function shutdown()
    {

    }

    /**
     * 输出缓冲函数
     *
     */
    public static function output_buffer($data = '')
    {
        $strlen = strlen($data);

        //如果gzip，则执行
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && $strlen > 255 && extension_loaded('zlib') && !ini_get('zlib.output_compression') && ini_get('output_handler') != 'ob_gzhandler')
        {
            $data = gzencode($data, 9);
            $strlen = strlen($data);
            header('Content-Encoding: gzip');
            header('Vary: Accept-Encoding');
        }

        //输出头部信息
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('ETag: "' . $strlen . '-' . time() . '"');
        header('X-Powered-By: zotop v'.c('zotop.version'));
        header('Author: zotop team && zotop.com');
        header('Accept-Ranges: bytes');

        return $data;
    }

    /**
     * 系统关闭函数
     *
     * @return void
     */
    public static function shutdown_handler()
    {
        if (  ZOTOP_TRACE )
        {
            ob_start();
            include ZOTOP_PATH_LIBRARIES . DS . 'views' . DS . 'trace.php';
            echo ob_get_clean();
            exit(1);            
        }


        if ($error = error_get_last())
        {
            // 清理输出
            ob_get_level() and ob_clean();

            // 异常处理
            application::exception_handler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));

            // 避免死循环
            exit(1);
        }
    }


    /**
     * 错误句柄，将php错误转化为异常
     *
     * @param int $code
     * @param string $error
     * @param string $file
     * @param int $line
     * @throws ErrorException
     * @return boolean
     */
    public static function error_handler($code, $error, $file = null, $line = null)
    {
        if (error_reporting() & $code)
        {
            throw new ErrorException($error, $code, 0, $file, $line);
        }

        // 不执行默认的PHP错误句柄
        return true;
    }

	/**
	 * 异常处理函数
	 *
	 * @uses zotop_exception::text
	 * @param object exception object
	 * @return boolean
	 */
	public static function exception_handler(Exception $e)
	{
		try
		{
			$type    = get_class($e);
			$code    = $e->getCode();
			$message = $e->getMessage();
			$file    = $e->getFile();
			$line    = $e->getLine();
			$trace   = $e->getTrace();

			if ( $e instanceof ErrorException )
			{
				if ( isset(zotop_exception::$php_errors[$code]) )
				{
					// Use the human-readable error name
					$code = zotop_exception::$php_errors[$code];
				}

				if ( version_compare(PHP_VERSION, '5.3', '<') )
				{
					// Workaround for a bug in ErrorException::getTrace() that
					// exists in
					// all PHP 5.2 versions. @see
					// http://bugs.php.net/bug.php?id=45895
					for ($i = count($trace) - 1; $i > 0; -- $i)
					{
						if ( isset($trace[$i - 1]['args']) )
						{
							// Re-position the args
							$trace[$i]['args'] = $trace[$i - 1]['args'];

							// Remove the args
							unset($trace[$i - 1]['args']);
						}
					}
				}
			}

			// Create a text version of the exception
			$error = zotop_exception::text($e);

			// If ajax request
			if ( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' OR strtolower($_REQUEST['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
			{
				// 清理已经输出内容
				ob_clean();

				exit(json_encode(array(
					'state' => false,
					'content' => $error,
					'time' => 100
				)));
			}

			if ( !headers_sent() )
			{
				header('Content-Type: text/html; charset=' . ZOTOP_CHARSET, TRUE, ($e instanceof zotop_exception) ? $code : 500);
			}

			if ( ZOTOP_DEBUG )
			{
				ob_start();
				include ZOTOP_PATH_LIBRARIES . DS . 'views' . DS . 'error.php';
				echo ob_get_clean();
				exit(1);
			}

			exit($error);
		}
		catch (Exception $e)
		{
			ob_get_level() and ob_clean();
			echo zotop_exception::text($e), "\n";
			exit(1);
		}
	}
}
?>