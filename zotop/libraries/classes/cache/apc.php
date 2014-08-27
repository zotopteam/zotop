<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * apc 缓存操作类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class cache_apc
{
	public $prefix;

	public function __construct($config=array())
	{
		if ( !$this->test() )
		{
			throw new zotop_exception(t('apc function not exists'));
		}
	}

    /**
     * 缓存测试
     *
     * @return bool
     */
	public function test()
	{
		return function_exists('apc_cache_info');
	}


    /**
     * 读取缓存
     *
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function get($id)
	{
		return apc_fetch($id);
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
		return apc_store($id, $data, $expire);
	}

    /**
     * 删除缓存
     *
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function delete($id)
	{
		return apc_delete($id);
	}


    /**
     * 清理缓存缓存
     *
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function clear()
	{
		apc_clear_cache();

		return TRUE;
	}
}
?>