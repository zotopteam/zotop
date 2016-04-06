<?php defined('ZOTOP') or die('No direct script access.');
/**
 * 简单分析类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team 
 * @license		http://zotop.com/license
 */

class profiler
{
	/**
	 * @var  array  collected benchmarks
	 */
	protected static $marks = array();

	/**
	 * 标记一个监测开始点，并记录时间和内存消耗
	 * 
	 * @param string $tag 标记名称
	 * @return bool；
	 */
	public static function start($tag)
	{
		if ( !C('system.debug') ) return false;

		$tag=strtolower($tag);
		
		if ( !isset(self::$marks[$tag]['start']) )
		{
			self::$marks[$tag]['start']=array(
				'time' => microtime(TRUE),
				'memory' => function_exists('memory_get_usage') ? memory_get_usage() : 0
			);
		}
		
		return true;
	}

	/**
	 * 标记一个监测结束点，并记录用时和内存消耗
	 * 
	 * @param string $tag 标记名称
	 * @return bool;
	 */
	public static function stop($tag)
	{
		if ( !C('system.debug') ) return false;

		$tag = strtolower($tag);
		
		if ( !isset(self::$marks[$tag]['stop']) )
		{
			self::$marks[$tag]['stop']=array(
				'time' => microtime(TRUE),
				'memory' => function_exists('memory_get_usage') ? memory_get_usage(): 0
			);
		}
		return true;
	}

	/**
	 * 获取特定的监测数据
	 * 
	 * @param string $tag 如果标记点为空则返回全部的监测点数据
	 * @return array；
	 */
	public static function mark($tag = '')
	{
		if ( !C('system.debug') ) return false;

		if ( empty($tag) )
		{
			return self::$marks;
		}
		
		$tag = strtolower($tag);

		if ( !isset(self::$marks[$tag]['start']) )
		{
			return false;
		}
		
		if ( !isset($marks[$tag]['stop']) )
		{
			self::stop($tag);
		}
		
		return array(
			'time' => number_format(self::$marks[$tag]['stop']['time'] - self::$marks[$tag]['start']['time'], 4),//返回单位为秒
			'memory' => number_format((self::$marks[$tag]['stop']['memory'] - self::$marks[$tag]['start']['memory'])/1024/1024, 4) //返回单位为Mb
		);
	}
}
?>