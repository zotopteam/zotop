<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * eaccelerator缓存操作类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team 
 * @license		http://zotop.com/license.html
 */
class cache_eaccelerator
{
	public $prefix;

	public function __construct($config=array())
	{
		if ( !$this->test() )
		{
			throw new zotop_exception(t('Eaccelerator function not exists'));
		}
	}

    /**
     * 缓存测试
     * 
     * @return bool
     */
	public function test()
	{
		return (extension_loaded('eaccelerator') && function_exists('eaccelerator_get'));
	}
	

    /**
     * 读取缓存
     * 
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function get($id)
	{
		return eaccelerator_get($id);
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
		return $expire ? eaccelerator_put($id, $data, $expire) : eaccelerator_put($id, $data);
	}

    /**
     * 删除缓存
     * 
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function delete($id)
	{
		return eaccelerator_rm($id);
	}


    /**
     * 清理缓存缓存
     * 
     * @param string $id 缓存变量名
     * @return mixed
     */
	public function clear()
	{
		$infos = eaccelerator_list_keys();

		if (is_array($infos))
		{
			foreach ($infos as $info)
			{
				if ( FALSE !== strpos($info['name'], $this->prefix) )
				{
					$id = 0 === strpos($info['name'], ':') ? substr($info['name'], 1) : $info['name'];
					
					if ( !eaccelerator_rm($id) )
					{
						return FALSE;
					}
				}
			}
		}

		return TRUE;
	}
}
?>