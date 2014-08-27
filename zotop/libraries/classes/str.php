<?php defined('ZOTOP') or die('No direct script access.');
/**
 * 字符串类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class str
{
	/**
	 * 查询字符是否存在于某字符串
	 *
	 * @param $haystack 字符串
	 * @param $needle 要查找的字符
	 * @return bool
	 */
	public static function exists($str, $needle)
	{
		return !(strpos($str, $needle) === FALSE);
	}

	/**
	 * 计算字符串长度
	 *
	 * @param $haystack 字符串
	 * @return int
	 */
	public static function len($str)
	{
		return mb_strlen($str,'UTF8');
	}


	/**
	 * 检查字符串是否是UTF8编码
	 *
	 * @param string $string 字符串
	 * @return Boolean
	 */
	public static function is_utf8($str)
	{
		return preg_match('%^(?:
			 [\x09\x0A\x0D\x20-\x7E]            # ASCII
		   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
		   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
		   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
		   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
		   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
		   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
		   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
	   )*$%xs', $str);
	}

	/**
	 * 检查字符串值是否是被 serialized
	 *
	 *
	 * @param mixed $data 待检查的值
	 * @return bool
	 */
	public static function is_serialized($data)
	{
		if ( empty($data) or !is_string($data)) return false;

		$data = trim($data);
		$length = strlen($data);
		$token = $data[0];
		$lastc = $data[$length - 1];

		if ('N;' == $data) return true;
		if ($length < 4) return false;
		if (':' !== $data[1]) return false;
		if (';' !== $lastc && '}' !== $lastc) return false;

		switch ($token)
		{
			case 's':
				if ('"' !== $data[$length - 2]) return false;
			case 'a':
			case 'O':
				return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
			case 'b':
			case 'i':
			case 'd':
				return (bool)preg_match("/^{$token}:[0-9.E-]+;\$/", $data);
		}
		return false;
	}

	/**
	 * 字符串截取，支持中文和其它编码
	 *
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	 * @return string
	 */
	public static function substr($str, $start, $length, $charset = "utf-8")
	{
		if( function_exists("mb_substr") )
		{
			$slice = mb_substr($str, $start, $length, $charset);
		}
		elseif(function_exists('iconv_substr'))
		{
			$slice = iconv_substr($str,$start,$length,$charset);
		}
		else
		{
			$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

			preg_match_all($re[$charset], $str, $match);

			$slice = join("",array_slice($match[0], $start, $length));
		}

		return $slice;
	}

	/**
	 * 字符串截取，支持中文和其它编码
	 *
	 * @param string $str 需要转换的字符串
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	 * @return string
	 */
	public static function cut($str, $length, $charset = "utf-8", $suffix = '...')
	{
		$slice = str::substr($str, 0, $length, $charset);

		if ( $suffix && $str != $slice )
		{
			return $slice.$suffix;
		}
		return $slice;
	}

	/**
	 * 产生随机字串，可用来自动生成密码 默认长度4位 字母和数字混合
	 *
	 * @param string $len 长度
	 * @param string $type 字串类型
	 * @param string $addChars 额外字符
	 * @return string
	 */
	public static function rand($len = 4, $type = '', $addons = '')
	{
		$str ='';
		switch($type)
		{
			case 0:
				$chars= str_repeat('0123456789',3);
				break;
			case 1:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addons;
				break;
			case 2:
				$chars='abcdefghijklmnopqrstuvwxyz'.$addons;
				break;
			case 3:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addons;
				break;
			default :
				// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
				$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addons;
				break;
		}

		if($len>10 )
		{
			//位数过长重复字符串一定次数
			$chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
		}

		$chars   =   str_shuffle($chars);
		$str     =   substr($chars,0,$len);

		return $str;
	}

	/**
	 * 替换多个斜线为一个斜线
	 *
	 *  $str = str::reduceslashes('foo//bar/baz');
	 *
	 *	foo/bar/baz
	 *
	 * @param   string  string to reduce slashes of
	 * @return  string
	 */
	public static function reduceslashes($str)
	{
		return preg_replace('#(?<!:)//+#', '/', $str);
	}

	/**
	 * 把字符串打散为数组，支持双重分隔符
	 *
	 *  str::explode('color:#0066cc;font-weight:bold;', ';', ':') => array('color'=>'#0066cc','font-weight'=>'bold')
	 *
	 * @param string $str 目标字符串
	 * @param string $s1 第一分隔符
	 * @param string $s2 第二分隔符
	 * @return array 返回数组
	 */
	public static function explode($str,$s1 = "=", $s2 = '&')
	{
		$os = array();

		if ( $str and is_string($str) )
		{
			$options = explode($s1, trim($str,$s1));

			foreach( $options as $option )
			{
				if ( strpos($option, $s2) )
				{
					list($name, $value) = explode($s2, trim($option));
				}
				else
				{
					$name = $value = trim($option);
				}

				$os[$name] = $value;
			}
		}

		return $os;
	}

	/**
	 * 将数组串联未字符串，支持双重分隔符
	 *
	 *  str::implode(array('id'=>1,'title'=>'test'), '=', '&') => id=1&title=test
	 *
	 * @param array $arr 目标数组
	 * @param string $s1 第一连接符
	 * @param string $s2 第二连接符
	 * @return array 返回字符串
	 */
	public static function implode($arr, $s1='=', $s2='&')
	{

	}
}
?>