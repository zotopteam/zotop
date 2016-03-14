<?php
defined('ZOTOP') or die('No direct script access.');
/**
 * zotop core
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class html
{
    /**
     * 把一些预定义的字符转换为 HTML 实体，等同于htmlspecialchars
     *
     *     echo html::chars($username);
     *
     * @param   string   string to convert
     * @param   boolean  encode existing entities
     * @return  string
     */
    public static function chars($str, $double_encode = true)
    {
        return htmlspecialchars((string )$str, ENT_QUOTES, ZOTOP_CHARSET, $double_encode);
    }

    /**
     * 等同于htmlspecialchars，对html字符串进行编码，默认使用UTF-8格式
     *
     * @param string $str
     * @return void
     */
	public static function encode($str)
	{
		return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
	}

    /**
     * 等同于htmlspecialchars_decode，对html字符串进行编码
     *
     * @param string $str
     * @return void
     */
	public static function decode($str)
	{
		return htmlspecialchars_decode($str, ENT_QUOTES);
	}

    /**
     * 引入js或者css文件，每个页面只引入一次，并且缓存文件
     *
     * @param mixed $file 文件路径
	 * @param string $type 文件类型，js或者css
     * @return void
     */
    public static function import($file, $type='')
    {
        static $files = array();

		$file = format::url($file);
		$type = empty($type) ? strtolower(pathinfo($file,PATHINFO_EXTENSION))  : strtolower($type);

        if ( !isset($files[$file]) )
        {
            // 缓存并压缩文件
			$files[$file] = true;

			if ( $type == 'css' )
			{
				return '<link rel="stylesheet" type="text/css" href="'.$file.'"/>';
			}

			return '<script type="text/javascript" src="'.$file.'"></script>';

        }

        return '';
    }

    /**
     * 创建HTML标签
     *
     * @param array|string $attrs 标签名称
     * @param string $value 标签值
     * @return void
     */
    public static function attributes($key, $value = null)
    {
        $str = '';

        if ( empty($key) ) return '';

        if ( is_string($key) )
        {
            if( $value === null )
            {
                return '';
            }
            elseif ( is_string($value) )
            {
                return ' '.$key.'="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'"';
            }
            elseif( is_numeric($value) )
            {
                return ' '.$key.'="'.$value.'"';
            }
            elseif( is_bool($value)  )
            {
                return ' '.$key.'="'.($value ? 'true' : 'false').'"';
            }
        }

        if ( is_array($key) )
        {
            foreach ( $key as $k=>$v )
            {
                $str .= html::attributes($k, $v);
            }
        }

        return $str;
    }
}
?>