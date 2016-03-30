<?php
defined('ZOTOP') or die('No direct script access.');
/**
 * zotop core
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class url
{
    /**
     * 解析url，parse_url函数中的query为字符串，此函数用parse_str解析为数组
     * 
     * @param  string $url [description]
     * @param  string $component 获取url中的组成部分，可选值：scheme|host|port|user|pass|path|query|fragment
     * @return mixed
     */
    public static function parse($url, $component=null)
    {
        $url = @parse_url($url);
        $url = is_array($url) ? $url : array();

        // query 转化为数组
        if ( isset($url['query']) )
        {
            @parse_str($url['query'], $query);

            $url['query'] = $query;
        }       

        return $component ? ( isset($url[$component]) ? $url[$component] : null ) : $url;
    }

    /**
     * 合并url数组
     * 
     * @param  array  $params 解析过的url数组
     * @return string
     */
    public static function combine(array $params)
    {
        if ( is_array($params['query']) )
        {
            $params['query'] = http_build_query($params['query']);
        }          

        $scheme   = isset($params['scheme']) ? $params['scheme'] . '://' : ''; 
        $host     = isset($params['host']) ? $params['host'] : ''; 
        $port     = isset($params['port']) ? ':' . $params['port'] : ''; 
        $user     = isset($params['user']) ? $params['user'] : ''; 
        $pass     = isset($params['pass']) ? ':' . $params['pass']  : ''; 
        $pass     = ($user || $pass) ? $pass.'@' : ''; 
        $path     = isset($params['path']) ? $params['path'] : ''; 
        $query    = isset($params['query']) ? '?' . $params['query'] : ''; 
        $fragment = isset($params['fragment']) ? '#' . $params['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";         
    }

    /**
     * 设置URl中的查询参数
     * 
     * @param mixed $args  参数数组或者参数字符串
     * @param string $url  待处理的url，默认不传值的时候为当前url
     */
    public static function set_query_arg($args, $url=null)
    {
        $url = empty($url) ? request::url() : $url;
        $url = self::parse($url);

        if ( $args )
        {
            if ( is_string($args) ) parse_str($args,$args);

            if ( is_array($args) ) 
            {
                $url['query'] = array_merge((array)$url['query'], $args);
            }            
        }
        else
        {
            $url['query'] = null;
        }

        return self::combine($url); 
    }
}
?>