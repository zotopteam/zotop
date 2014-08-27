<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * cache操作类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class cache
{
    protected $driver = null; //缓存驱动对象
    protected $config = array(); //缓存配置

    /**
     * 类初始化
     *
     * @param   string|array  config 配置
     * @return  object
     */
    public function __construct($config = array())
    {
        if (is_string($config)) $config = array('driver' => $config);

        $config += array(
            'driver' => c('system.cache_driver'),
            'expire' => intval(c('system.cache_expire')),
            'prefix' => c('system.cache_prefix'),
        );

        //默认使用文件作为缓存方式
        $driver = empty($config['driver']) ? 'cache_file' : 'cache_' . strtolower($config['driver']);


        //加载驱动程序
        if (!zotop::autoload($driver))
        {
            throw new zotop_exception(t('未能找到缓存驱动<b>%s</b>', $driver));
        }

        $this->driver = new $driver($config);
        $this->driver->prefix = empty($config['prefix']) ? substr(md5($_SERVER['HTTP_HOST']), 0, 6) : $config['prefix'];
        $this->driver->prefix = trim($this->driver->prefix, '_') . '_';

        return $this->driver;
    }

    /**
     * 获取相同配置的唯一实例
     *
     * @param   string|array  config 配置
     * @return  object
     */

    public static function &instance($config = array())
    {
        static $instances = array();

        //实例唯一的编号
        $id = serialize($config);

        if (!isset($instances[$id]))
        {
            //取得驱动实例
            $instance = new cache($config);

            //存储实例
            $instances[$id] = &$instance;
        }

        return $instances[$id];
    }

    /**
     * 测试缓存是否被支持
     *
     * @return  bool
     */
    public function test()
    {
        return $this->driver->test();
    }

    /**
     * 格式化缓存的标识
     *
     * @param   string   cache key
     * @return  string
     */
    protected function escape($id)
    {
        $id = empty($id) ? '' : str_replace(array('/','\\',' '), '_', $id);

        return $this->driver->prefix . $id;
    }

    /**
     * 读取缓存
     *
     * @param string $id 缓存标识
     * @param string $group 缓存组
     * @return mixed
     */
    public function get($id)
    {
        N('cache.get',1);

        $id = $this->escape($id);

        $data = $this->driver->get($id);

        if ($data === null or $data === false)
        {
            return null;
        }

        return $data;
    }

    /**
     * 设置缓存
     *
     * @param string $id 缓存标识
     * @param mix $data 缓存数据
     * @param int $expire 缓存时间,单位秒, null = 系统设置缓存时间，true = 永久缓存
     * @return mixed
     */
    public function set($id, $data, $expire = null)
    {
		N('cache.set',1);

        $id = $this->escape($id);

        //如果未设置缓存时间，则获取默认的缓存时间
        if ( $expire === null or $expire === true )
        {
            $expire = $this->config['expire'];
        }

        return $this->driver->set($id, $data, intval($expire));
    }

    /**
     * 删除缓存
     *
     * @param string $key 缓存变量名
     * @return mixed
     */
    public function delete($id)
    {
        $id = $this->escape($id);

        return $this->driver->delete($id);
    }

    /**
     * 清除全部缓存
     *
     * @return mixed
     */
    public function clear()
    {
        return $this->driver->clear();
    }
}
?>