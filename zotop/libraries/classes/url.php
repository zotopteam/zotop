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
    public static function add_query_arg($args, $url=null)
    {
        if ( empty($url) ) $url = request::url();

        if ( is_string($args) )
        {
            parse_str($args,$args);
        }
        
        $u = parse_url($url);

        if(isset($u['query']))
        {
            parse_str($u['query'], $p);

            $args = array_merge($p, $args);
        }
        
        if ( is_array($args) )
        {
            $query = http_build_query($args);
        }

        $scheme   = empty($u['scheme']) ? '' : $u['scheme'].'://';
        $user     = empty($u['user']) ? '' : $u['user'].':';
        $pass     = empty($u['pass']) ? '' : $u['pass'].'@';
        $host     = $u['host'];
        $port     = empty($u['port']) ? '' : ':'.$u['port'];
        $path     = $u['path'];
        $query    = empty($query) ? '' : '?'.$query;
        $fragment = empty($u['fragment']) ? '' : '#'.$u['fragment'];

        return "$scheme$user$pass$host$port$path$query$fragment";     
    }


}
?>