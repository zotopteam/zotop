<?php
defined('ZOTOP') or die('No direct script access.');

/**
 * zotop core
 *
 * @package zotop
 * @author zotop team
 * @copyright (c)2009-2012 zotop team
 * @license http://zotop.com/license
 */
class request
{

    /**
     * 返回请求的来路url
     *
     * @return string
     */
    public static function referer()
    {
        return isset($_GET['referer']) ? $_GET['referer'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
    }

    /**
     * 返回当前的使用的协议，一般为 http 或者 https
     *
     * @return https http
     */
    public static function protocol()
    {
        return (isset($_SERVER['HTTPS']) && ! strcasecmp($_SERVER['HTTPS'], 'on')) ? 'https' : 'http';
    }

    /**
     * 获取端口
     *
     * 如果是普通连接默认为80，安全链接默认为443
     * request is insecure.
     */
    public function port()
    {
        $default = (request::protocol() == 'http') ? 80 : 443;

        return isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : $default;
    }

    /**
     * 返回含 schema 、 host 及 port 的URL信息，结尾不含斜线
     *
     * http://127.0.0.1
     * http://127.0.0.1:100
     * https://127.0.0.1:100
     *
     * @return string
     */
    public static function host()
    {
        static $host = null;

        if ($host === null)
        {
            $http = request::protocol();

            if (isset($_SERVER['HTTP_HOST']))
            {
                $host = $http . '://' . $_SERVER['HTTP_HOST'];
            }
            else
            {
                $host = $http . '://' . $_SERVER['SERVER_NAME'];

                $port = request::port();

                if (($port !== 80 && $http == 'http') || ($port !== 443 && $http == 'https'))
                {
                    $host .= ':' . $port;
                }
            }
        }

        return $host;
    }

    /**
     * 获取脚本名称[scriptname]
     *
     * http://localhost/index.php returns an empty '/index.php'
     * http://localhost/index.php/page returns an empty '/index.php'
     * http://localhost/web/index.php returns '/web/index.php'
     * http://localhost/we%20b/index.php returns '/we%20b/index.php'
     *
     * @return string
     */
    public static function scriptname()
    {
        static $scriptname = null;

        if ($scriptname === null)
        {
            $filename = basename($_SERVER['SCRIPT_FILENAME']);

            if (basename($_SERVER['SCRIPT_NAME']) === $filename)
            {
                $scriptname = $_SERVER['SCRIPT_NAME'];
            }
            elseif (basename($_SERVER['PHP_SELF']) === $filename)
            {
                $scriptname = $_SERVER['PHP_SELF'];
            }
            elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename)
            {
                $scriptname = $_SERVER['ORIG_SCRIPT_NAME'];
            }
            elseif (($pos = strpos($_SERVER['PHP_SELF'], '/' . $filename)) !== false)
            {
                $scriptname = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $filename;
            }
            elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0)
            {
                $scriptname = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
            }
        }

        return $scriptname;
    }

    /**
     * 获取文件基本路径
     *
     * http://localhost/index.php returns '/'
     * http://localhost/index.php/page returns '/'
     * http://localhost/web/index.php returns '/web/'
     * http://localhost/we%20b/index.php returns '/we%20b/'
     *
     * @return string The raw path (i.e. not urldecoded)
     */
    public static function basepath()
    {
        static $basepath = null;

        if ($basepath === null)
        {
            $filename = basename($_SERVER['SCRIPT_FILENAME']);

            $scriptname = request::scriptname();

            if (empty($scriptname))
            {
                $basepath = '';
            }
            elseif (basename($scriptname) === $filename)
            {
                $basepath = dirname($scriptname);
            }
            else
            {
                $basepath = $scriptname;
            }

            if ('\\' === DIRECTORY_SEPARATOR)
            {
                $basepath = str_replace('\\', '/', $basepath);
            }

            //$basepath = rtrim($basepath, '/');
        }

        return $basepath;
    }

    /**
     * 返回当前的url地址
     *
     * @return string
     */
    public static function url($host = true)
    {
        static $uri = null;

        if ($uri === null)
        {
            if (isset($_SERVER['HTTP_X_REWRITE_URL'])) // IIS
            {
                $uri = $_SERVER['HTTP_X_REWRITE_URL'];
            }
            elseif (isset($_SERVER['REQUEST_URI']))
            {
                $uri = $_SERVER['REQUEST_URI'];

                if (isset($_SERVER['HTTP_HOST']))
                {
                    if (strpos($uri, $_SERVER['HTTP_HOST']) !== false)
                    {
                        $uri = preg_replace('/^\w+:\/\/[^\/]+/', '', $uri);
                    }
                }
                else
                {
                    $uri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $uri);
                }
            }
            elseif (isset($_SERVER['ORIG_PATH_INFO'])) // IIS 5.0 CGI
            {
                $uri = $_SERVER['ORIG_PATH_INFO'];

                if (! empty($_SERVER['QUERY_STRING']))
                {
                    $uri .= '?' . $_SERVER['QUERY_STRING'];
                }
            }
        }

        return $host ? request::host() . $uri : $uri;
    }

    /**
     * 返回客户端IP地址
     *
     * @return string
     */
    public static function ip()
    {
        static $ip = null;
        if ($ip === null)
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) unset($arr[$pos]);
                $ip = trim($arr[0]);
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            elseif (isset($_SERVER['REMOTE_ADDR']))
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $ip = '0.0.0.0';
            }
        }
        return $ip;
    }
}
?>