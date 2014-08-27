<?php
defined('ZOTOP') or die('No direct script access.');
/**
 * 路由解析及反向解析类
 *
 *
 * 路由规则
 *
 * <code>
 * array(
 * 'content/<id:num>.html' => 'content/detail/<id>',
 * 'content/list-<id:num>.html' => 'content/list/<id>',
 * 'content/index-<id:num>.html' => 'content/index/<id>',
 * )
 * <code>
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */

class router
{
    public static $rules = array();

    /**
     * 获取并缓存解析后的规则
     *
     * 兼容模式：返回url中的uri参数，默认为zotop，index.php?r=content/index/1 返回 zotop/index/1
     * PATHINFO模式（默认模式）：返回url中的pathinfo部分，如 /index.php/content/index/1 返回 zotop/index/1
     *
     * @return $string
     */
    public static function init()
    {
        // 获取 router::$rules
        if (empty(router::$rules))
        {
            $rules = zotop::cache('zotop.router');

            if (empty($rules))
            {
                $url_router = c('router'); // 获取原始缓存设置

                foreach ((array )$url_router as $pattern => $route)
                {
                    if (! $pattern or ! $route) continue;

                    $pattern = router::regex($pattern);
                    $rules[$pattern] = router::parse($pattern, $route);
                }

                zotop::cache('zotop.router', $rules, false);
            }

            router::$rules = is_array($rules) ? $rules : array();
        }

        //debug::dump(router::$rules);
    }

    /**
     * 格式化正则表达式
     *
     *
     * @param string $reg
     * @return string
     */
    public static function regex($str)
    {
        $str = str_replace(':any', ':[^\/]+', $str); // 任意字符
        $str = str_replace(':num', ':\d+', $str); //数字
        $str = str_replace(':str', ':\w+', $str); //[a-zA-Z_0-9]

        return $str;
    }

    /**
     * 解析路由规则，全局只解析一次并缓存数据
     *
     * <code>

     * 解析：

     * [content/lists-<id:\d+>-<page:\d+>.html] => content/list/<id>

     * 返回：

     * Array
     * (
     * [route] => content/list/<id>
     * [references] => Array
     * (
     * [id] => <id>
     * )

     * [host] => false
     * [params] => Array
     * (
     * [page] => \d+
     * )

     * [template] => content/lists-<id>-<page>.html
     * [route_regex] => /^content\/lists-(?P<id>\d+)-(?P<page>\d+).html\/$/u
     * [create_regex] => /^content\/list\/(?P<id>\d+)$/u
     * )
     * <code>
     *
     * @param mixed $pattern
     * @param mixed $route
     * @return
     */
    public static function parse($pattern, $route)
    {
        $route = trim($route, '/');

        // 判断是否是含有域名的规则
        $host = ! strncasecmp($pattern, 'http://', 7) || ! strncasecmp($pattern, 'https://', 8);

        // 从route中解析出引用变量，如：'content/<id:\d+>'=>'content/index/<id>' 中的 <id>
        $references = array();

        if (strpos($route, '<') !== false && preg_match_all('/<(\w+)>/', $route, $matches2))
        {
            foreach ($matches2[1] as $name)
            {
                $references[$name] = "<$name>";
            }
        }

        //解析规则 获取?后的参数的规则，如：'content/<id:\d+>/<page>'=>'content/index/<id>' 中的 <page>

        $tr2['/'] = $tr['/'] = '\\/';

        $params = array();

        if (preg_match_all('/<(\w+):?(.*?)?>/', $pattern, $matches))
        {
            $tokens = array_combine($matches[1], $matches[2]);

            foreach ($tokens as $name => $value)
            {
                if ($value === '') $value = '[^\/]+';

                $tr["<$name>"] = "(?P<$name>$value)";

                if (isset($references[$name]))
                {
                    $tr2["<$name>"] = $tr["<$name>"];
                }
                else
                {
                    $params[$name] = $value;
                }
            }
        }

        // 获取解析规则的模板，如：content/<id>，content/<id>/<page>
        $template = preg_replace('/<(\w+):?.*?>/', '<$1>', $pattern);

        // 获取解析的正则
        $route_regex = '/^' . strtr($template, $tr) . '\/$/u';

        // 获取反向解析的正则
        if ($references) //不为空
        {
            $create_regex = '/^' . strtr($route, $tr2) . '$/u';
        }

        return array(
            'route' => $route,
            'references' => $references,
            'host' => $host,
            'params' => $params,
            'template' => $template,
            'route_regex' => $route_regex,
            'create_regex' => $create_regex,
            );
    }


    /*
    * 页面访问时的路由解析，将访问地址解析为实际的地址
    *
    * 直接解析
    *
    * 'contents'=>'content/list' 解析: /index.php/contents => /index.php/content/list
    *
    * 正则解析
    *
    * 'content/<id:\d+>'=>'content/detail' 解析：/index.php/121 => /index.php/content/detail?id=121
    *
    * 'content/<year:\d{4}>/<title>'=>'content/detail', 解析：/index.php/6/test-title => /index.php/content/detail?year=6&title=test-title
    *
    */
    public static function route()
    {
        $uri = zotop::$uri; //获取uri

        foreach (router::$rules as $pattern => $rule)
        {
            if (($r = router::parseUrl($pattern, $rule, $uri, zotop::$uri)) !== false)
            {
                $uri = $r;
                break;
            }
        }

        zotop::$uri = $uri;
    }

    /*
    * 根据路由规则解析url
    *
    *
    * 'content/<id:\d+>'=>'content/detail' 解析：/index.php/121 => /index.php/content/detail?id=121
    *
    * 'content/<year:\d{4}>/<title>'=>'content/detail', 解析：/index.php/6/test-title => /index.php/content/detail?year=6&title=test-title
    *
    */
    public static function parseUrl($pattern, $route, $uri, $rawuri)
    {
        $uri = trim($uri, '/') . '/';

        if (preg_match($route['route_regex'], $uri, $matches))
        {
            $tr = array();

            foreach ($matches as $key => $value)
            {
                if (isset($route['references'][$key]))
                {
                    $tr[$route['references'][$key]] = $value;
                }
                else
                    if (isset($route['params'][$key]))
                    {
                        $_REQUEST[$key] = $_GET[$key] = $value;
                    }
            }

            if ($route['create_regex'] !== null)
            {
                return strtr($route['route'], $tr);
            }

            return $route['route'];
        }

        return false;
    }


    public static function url()
    {

    }

    /**
     * 根据路由和参数生成URL
     *
     * 路由规则不存在，直接生成返回
     *
     * 路由规则存在：
     *
     * 'contents'=>'content/list' 解析: u('content/list') => /index.php/contents
     *
     * 'content/<id:\d+>'=>'content/detail' 解析：u('content/detail',array('id'=>121)) => /index.php/content/121
     *
     * 'content/<year:\d{4}>/<title>'=>'content/detail', 解析：u('content/detail',array('year'=>2008,'title'=>'test-title')) => /index.php/content/2008/test-title
     *
     *
     *
     * @param string 		$pattern 	规则
     * @param array|string 	$route 		路由
     * @param string 		$uri		uri
     * @param array			$params		参数

     * @return string
     */
    public static function createUrl($pattern, $route, $uri, $params)
    {
        if (empty($uri)) return false;

        $tr = array();

        // $uri不等于规则里的uri 从route里面获取相应值，如：'content/<id:\d+>'=>'content/detail/<id>', 获取id的值
        if ($uri !== $route['route'])
        {
            if ($route['create_regex'] !== null && preg_match($route['create_regex'], $uri, $matches))
            {
                foreach ($route['references'] as $key => $name)
                {
                    $tr[$name] = $matches[$key]; //写入替换值
                }
            }
            else
            {
                return false;
            }
        }

        //比较params
        if ($params and empty($route['params'])) return false;

        //如果含有params 且传入参数中找不到params，则不适应 否则 写入替换值
        foreach ($route['params'] as $key => $rule)
        {
            if (isset($params[$key]) and preg_match('/\A' . $rule . '\z/u', $params[$key]))
            {
                $tr["<$key>"] = urlencode($params[$key]);
                unset($params[$key]); //删除路径中存在的params，余下的通过参数传递
            }
            else
            {
                return false;
            }
        }

        $url = strtr($route['template'], $tr);

        if ($params and is_array($params))
        {
            $url .= '?' . http_build_query($params);
        }

        //'http://<id:\d+>.zotop.com/detail'=>'user/detail/<id>'
        if ($route['host'])
        {
            $host = request::host();

            if (stripos($url, $host) === 0)
            {
                $url = substr($url, strlen($host));
            }
        }

        return $url;
    }

}
?>