<?php
defined('ZOTOP') or die('No direct script access.');
/**
 * zotop session
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
abstract class session
{
    protected $config, $started = false;

    public function __construct($config = array())
    {
        $this->config = $config;

        if (! $this->test())
        {
            throw new zotop_exception(t('The session extension %s is not available', $config['driver']));
        }

        if (isset($_REQUEST['sessionid']))
        {
            session_id($_REQUEST['sessionid']);
        }

        ini_set('session.auto_start', 0);

        if (isset($config['name'])) session_name($config['name']); // 不设置默认为PHPSESSID
        if (isset($config['expire'])) ini_set('session.gc_maxlifetime', $config['expire']);
        if (isset($config['use_trans_sid'])) ini_set('session.use_trans_sid', $config['use_trans_sid'] ? 1 : 0);
        if (isset($config['use_cookies'])) ini_set('session.use_cookies', $config['use_cookies'] ? 1 : 0);
        if (isset($config['cookie_domain'])) ini_set('session.cookie_domain', $config['cookie_domain']);
        if (isset($config['cache_limiter'])) session_cache_limiter($config['cache_limiter']);
        if (isset($config['cache_expire'])) session_cache_expire($config['cache_expire']);

        if ($config['autostart']) session_start();

        $this->register();
    }

    /*
    *  session instance
    */
    public static function &instance(array $config)
    {
        static $instances = array();

        if (empty($config['driver'])) $config['driver'] = 'native';

        if (! isset($instances[$config['driver']]))
        {
            // session驱动程序
            $driver = 'session_' . strtolower($config['driver']);

            // 加载驱动程序
            if (! zotop::autoload($driver))
            {
                throw new zotop_exception(t('Cannot find session driver "%s"', $driver));
            }

            $instances[$config['driver']] = new $driver($config);
        }

        return $instances[$config['driver']];
    }

    /*
    *  设置session保存的句柄
    */
    public function register()
    {
        session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
    }

    /*
    *  测试驱动是否正常
    */
    public function test()
    {
        return true;
    }
}
?>