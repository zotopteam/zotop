<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 文件缓存操作类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class cache_file
{
	public $prefix;

	protected $config = array();
	protected $root;

	public function __construct($config=array())
	{
		$this->config = array_merge($this->config, $config);

		$this->root = ZOTOP_PATH_CACHE;

		if ( !$this->test() )
		{
			throw new zotop_exception(t('目录<b>%s</b>不可写', $this->root));
		}
	}

    /**
     * 缓存测试
     *
     * @return bool
     */
	public function test()
	{
		if ( folder::create($this->root) )
		{
			if ( is_dir($this->root) and is_writable($this->root) )
			{
				return true;
			}
		}

		return false;
	}

    /**
     * 缓存文件名称
     *
     * @param string $id 缓存标识
	 * @param string $group 缓存组
     * @return mixed
     */
	public function filepath($id)
	{
		$filename = sha1($id).'.php';
		$filepath = $this->root.DS.$filename[0].$filename[1].DS.$filename;

		return $filepath;
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
		$file = $this->filepath($id);

		if ( file_exists($file) )
		{
			$data = file_get_contents($file);

			if( ZOTOP_TIME < intval(substr($data, 0, 10)) )
			{
                return unserialize(substr($data, 10));
            }

            unlink($file);
		}

		return false;
	}

    /**
     * 设置缓存
     *
     * @param string $id 缓存标识
	 * @param string $data 缓存数据
	 * @param int $expire 缓存时间，设置为0则缓存365天
	 *
     * @return mixed
     */
    public function set($id, $data, $expire=0)
	{
		//缓存到期时间
		$expire = $expire ? (ZOTOP_TIME + $expire) : (ZOTOP_TIME + 31536000);

		//缓存文件名称
		$file = $this->filepath($id);

		//写入缓存
		return file::put($file, $expire.serialize($data));
    }

    /**
     * 删除缓存
	 *
     * @param string $key 缓存变量名
     * @return mixed
     */
	public function delete($id)
	{
		//缓存文件名称
		$file = $this->filepath($id);

		//删除缓存
		return file::delete($file);
	}

	/**
	 * 清理文件夹中全部文件
	 */
	public function clear()
	{
		return folder::clear($this->root);
	}

}
?>