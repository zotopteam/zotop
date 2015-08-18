<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * badword
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_badword extends model
{
	protected $pk = 'id';
	protected $table = 'badword';

    /**
     * 添加
     *
     */
	public function add($data)
	{
		if ( empty($data['word']) ) return $this->error(t('敏感词不能为空'));

		return $this->insert($data,true);
	}

    /**
     * 编辑
     *
     */
	public function edit($data, $id)
	{
		if ( empty($data['word']) ) return $this->error(t('敏感词不能为空'));

		return $this->update($data, $id);
	}

    /**
     * 保存后处理数据
     *
     */
	protected function _after_insert()
	{
		$this->cache(true);
	}

    /**
     * 保存后处理数据
     *
     */
	protected function _after_update()
	{
		$this->cache(true);
	}

    /**
     * 删除后处理数据
     *
     */
	protected function _after_delete()
	{
		$this->cache(true);
	}

	/*
	 * 缓存数据和设置缓存
	 * @param string $refresh 强制刷新
	 * @return string 缓存数据
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache("badword.data");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->db()->field('word,replace,level')->orderby('listorder','desc')->select();

			zotop::cache("badword.data", $cache, false);
		}

		return $cache;
	}

	/**
	 * 敏感词处理接口,对传递的数据进行处理,并返回
	 *
	 * @param string $str 待处理字符串
	 * @return string 处理后的数据
	 */
	public function replace($str) {
		//读取敏感词缓存
		$badword = $this->cache();

		foreach($badword as $w)
		{
 			$newwords[] = ( intval($w['level']) == 0 ) ? ( empty($w['replace']) ? str_repeat('*',str::len($w['word'])) : $w['replace'] ) : '';
 			$oldwords[] = $w['word'];
		}
		$str = str_replace($oldwords, $newwords, $str);
 		return $str;
 	}

}
?>