<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 数组操作类
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop.util
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class format
{
	/**
	 * 格式化文件大小单位byte为友好的单位
	 *
	 * @param int $size 大小
	 * @param int $len 小数点后面的位数
	 * @return string
	 */
	public static function size($size,$len=2)
	{
		$len = (is_numeric($len) && $len > 0) ? $len : 2;
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
		$pos=0;
		while ($size >= 1024)
		{
			$size /= 1024;
			$pos++;
		}
		return number_format($size,$len).' '.$units[$pos];
	}


	/**
	 * 格式化路径，清理并转化分隔符，去掉结尾的分隔符，并将其转化为系统的风格符号,替换系统的一些变量
	 *
	 * @param string $path 路径字符串
	 * @return string
	 */
	public static function path($path)
	{
		$path = preg_replace('#[/\\\\]+#', DIRECTORY_SEPARATOR, $path); //清理并转化
		$path = rtrim($path,DIRECTORY_SEPARATOR); //去掉结尾的分隔符号

		return $path;
	}

	/**
	 * 格式化url，去除多余的斜杠，并转化为相对url或者绝对url
	 *
	 *
	 * @param string $url
	 * @return string
	 */
	public static function url($url)
	{
		$url = str_replace("\\", "/", $url);
		$url = preg_replace("#(^|[^:])//+#", "\\1/", $url); //替换多余的斜线
		return $url;
	}

	/**
	 * 格式化为js
	 *
	 *
	 * @param string $string
	 * @return string
	 */
	public static function js($string)
	{
		return addslashes(str_replace(array("\r", "\n"), array('', ''), $string));
	}

	/**
	 * 格式化文本，一般用于格式化textarea的显示值
	 *
	 *
	 * @param string $string
	 * @return string
	 */
	public static function textarea($string)
	{
		return format::nl2p(str_replace(' ', '&nbsp;', htmlspecialchars($string)));
	}

	/**
	 * 格式化文本，将换行 “/n” 转化为段落
	 *
	 * @param string $string String to be formatted.
	 * @return string
	 */
	public static function nl2p($string)
	{

		$string = trim(str_replace(array('<p>', '</p>', '<br>', '<br/>','<br />'), '', $string));
	    $string = '<p>'.preg_replace("/([\n]{1,})/i", "</p>\n<p>", $string).'</p>';
	    $string = str_replace(array('<p><br/></p>','<p></p>'), '', $string);

	    return $string;
	}	


    /**
     * 对时间进行格式化，支持多种格式化方式
     *
     * @param string 待格式化的时间戳或者时间标准串
     * @param string 时间格式,如:datetime, date, y-m-d H:i:s ,或者友好格式如：u|y-m-d H:i,友好格式含时间格式必须从第三个字符开始
	 * @param string 时区偏移，如8=>GMT+08:00
     *
     * @return string 格式化后的时间
     */
	public static function date($timestamp, $format=null, $timeoffset=null)
	{
		if ( empty($timestamp) ) return '';

		static $offset,$dateformat,$timeformat;

		if ( $offset == null )
		{
			$offset 	= c('system.locale_timezone');
			$dateformat = c('system.locale_date'); //获取日期格式
			$timeformat = c('system.locale_time');
		}

		//如果设置时区，则格式化当前时间为目标时区时间
		$timeoffset = ( $timeoffset === null ) ? $offset : $timeoffset;
		$timestamp  = $timestamp + $timeoffset * 3600;
		$format = empty($format) || $format == 'datetime' ? $dateformat.' '.$timeformat : ($format == 'date' ? $dateformat : ($format == 'time' ? $timeformat : $format));

		if( $format[0] == 'u' )
		{
			$uformat = substr($format,2);

			$uformat = empty($uformat) || $uformat == 'datetime' ? $dateformat.' '.$timeformat : ($uformat == 'date' ? $dateformat : ($uformat == 'time' ? $timeformat : $uformat));

			$todaytimestamp = TIME - (TIME + $timeoffset * 3600) % 86400 + $timeoffset * 3600;

			$s = gmdate($uformat, $timestamp);

			$time = TIME + $timeoffset * 3600 - $timestamp;

			if( $timestamp >= $todaytimestamp )
			{
				if( $time > 3600 )
				{
					return '<span title="'.$s.'">'.intval($time / 3600).'&nbsp;'.t('小时').t('前').'</span>';
				}
				elseif( $time > 1800 )
				{
					return '<span title="'.$s.'">'.t('半').t('小时').t('前').'</span>';
				}
				elseif( $time > 60 )
				{
					return '<span title="'.$s.'">'.intval($time / 60).'&nbsp;'.t('分钟').t('前').'</span>';
				}
				elseif( $time > 0 )
				{
					return '<span title="'.$s.'">'.$time.'&nbsp;'.t('秒').t('前').'</span>';
				}
				elseif( $time == 0 )
				{
					return '<span title="'.$s.'">'.t('刚刚').'</span>';
				}
				else
				{
					return $s;
				}
			}
			elseif(($days = intval(($todaytimestamp - $timestamp) / 86400)) >= 0 && $days < 7)
			{
				if( $days == 0 )
				{
					return '<span title="'.$s.'">'.t('昨天').'&nbsp;'.gmdate('H:i', $timestamp).'</span>';
				}
				elseif( $days == 1 )
				{
					return '<span title="'.$s.'">'.t('前天').'&nbsp;'.gmdate('H:i', $timestamp).'</span>';
				}
				else
				{
					return '<span title="'.$s.'">'.($days + 1).'&nbsp;'.t('天').t('前').'</span>';
				}
			}
			else
			{
				return $s;
			}
		}

		return gmdate($format, $timestamp);
	}


	/**
	 * 格式化正则表达式
	 *
	 *
	 * @param string $reg
	 * @return string
	 */
	public static function regex($regex)
	{
		$regex = str_replace(':any','.+',$regex);
		$regex = str_replace(':num','[0-9]+',$regex);

		return $regex;
	}

	/**
	 * 格式化ID，删除非数字的编号，并将1,2,3,4格式化为数组
	 *
	 *
	 * @param mixed $id
	 * @return mixed
	 */
	public static function id($id)
	{
		if ( is_numeric( $id ) ) return $id;

		if ( is_array( $id ) ) return array_filter( $id, "is_numeric" );

		if ( strpos( $id, "," ) !== FALSE )
		{
			return preg_match( "/^([\\d]+,)+\$/", $id."," ) ? explode( ",", $id ) : false;
		}
		return false;
	}
}
?>