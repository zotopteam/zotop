<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * Memcache缓存操作类
 *
 *  
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team 
 * @license		http://zotop.com/license.html
 */
class cache_memcache
{
	public $prefix;

	protected $config = array();
	protected $memcache;
	protected $connected;
	
	public function __construct($config=array())
	{	
		if ( !$this->test() )
		{
			throw new zotop_exception(t('Memcache PHP extention not loaded'));
		}
		
		$this->config = array_merge($this->config, $config);

		$this->config += array('servers' => c('system.cache_memcache'));
		
		$servers = $this->config['servers'];
		
		// 多个memcache使用换行或者都好隔开
		if ( !is_array($servers) )
		{
			$servers = str_replace(',',"\n", $servers);
			$servers = explode("\n",$servers);
		}

		if ( !$servers)
		{
			throw new zotop_exception('未定义memcache缓存服务器');
		}

		// 初始化memcache
		$this->memcache = new Memcache();
	
		foreach ($servers as $server)
		{
			if ( !is_array($server) ) $server = explode(':', $server);

			if ( !$this->memcache->addServer($server[0], ( isset($server[1]) ? $server[1] : 11211 ), (isset($server[2]) ? $server[2] : true)) )
			{
				throw new zotop_exception(t('无法连接Memcache服务器 "%s:%s"',$server[0],$server[1]));
			}
		}
	}

    /**
     * 缓存测试
     * 
     * @return bool
     */
	public function test()
	{
		return (extension_loaded('memcache') && class_exists('Memcache'));
	}
	

    /**
     * 读取缓存
     * 
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function get($id)
	{
		return $this->memcache->get($id);
	}

    /**
     * 设置缓存
     * 
     * @param string $id 缓存变量名
	 * @param mix $value 缓存数据
	 * @param int $expire 缓存时间,单位秒
     * @return mixed
     */
	public function set($id, $data ,$expire=0)
	{
		return $this->memcache->set($id, $data, MEMCACHE_COMPRESSED,  $expire);
	}

    /**
     * 删除缓存
     * 
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function delete($id, $timeout=0)
	{
		return $this->memcache->delete($id,$timeout);
	}

    /**
     * 清理缓存缓存
     * 
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function clear()
	{
		$result = $this->memcache->flush();

		// We must sleep after flushing, or overwriting will not work!
		// @see http://php.net/manual/en/function.memcache-flush.php#81420
		sleep(1);

		return $result;
	}

}
?>