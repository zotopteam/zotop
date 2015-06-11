<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * file操作类
 *
 * @copyright (c)2009 zotop team
 * @package core
 * @author  zotop team
 * @license http://zotop.com/license.html
 */
class file
{

	/**
	 * 获取文件的扩展名
	 *
	 * @param string $file 文件地址
	 * @return string
	 */
	public static function ext($file)
	{
		return strtolower(trim(substr(strrchr($file, '.'), 1, 10)));
	}

	/**
	 * 获取文件名称 , 第二个参数控制是否返回文件扩展名，默认为返回带扩展名的文件名称
	 *
	 * @param string $file 文件地址
	 * @param boolean $stripext
	 * @return string
	 */
	public static function name($file, $stripext = false)
	{
		$name = basename($file);

		if ( $stripext )
		{
			$ext = file::ext($file);
			$name = basename($file,'.'.$ext);
		}

		return $name;
	}

	/**
	 * 判断文件是否存在
	 *
	 * @param string $file 文件地址
	 * @return boolean
	 */
	public static function exists($file)
	{
		static $exists = array(); //避免多次查询该文件是否存在

		if ( empty($file) ) return false;

		$file = format::path($file);

		if ( !isset($exists[$file]) )
		{
			$exists[$file] = @is_file($file);
		}

		return $exists[$file];
	}

	/**
	 * 获取文件大小
	 *
	 * @param string $file 文件地址
	 * @return boolean
	 */
	public static function size($file,$format=false)
	{
		$file = format::path($file);

		$size = @filesize($file);

		return $format ? format::byte($size, $format) : $size;
	}

	/**
	 * 获取文件创建/修改时间
	 *
	 * @param string $file 文件地址
	 * @param bool $ctime 获取文件创建时间
	 * @return boolean
	 */
	public static function time($file, $ctime=false)
	{
		return $ctime ? filectime(format::path($file)) : filemtime(format::path($file));
	}

	/**
	 * 获取文件编码
	 *
	 * @param string $file
	 * @return string
	 */
	public static function isUTF8($file)
	{
		$str = file::get($file);

		if ( $str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32") )
		{
			return true;
		}
		else
		{
			return false;
		}
       
	}	

	/**
	 * 读取文件内容, file_get_contents 增强版
	 *
	 * @param string $file
	 * @return string
	 */
	public static function get($file)
	{
		$file = format::path($file);

		if ( file_exists($file) )
		{
			return file_get_contents($file);
		}

		return false;
	}

	/**
	 * Strip close comment and close php tags from file headers used by WP
	 *
	 * @param string $str
	 * @return string
	 */
	public static function cleanup_header_comment($str)
	{
		return trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $str));
	}

	/**
	 * 读取文件的信息
	 *
	 * @param string $file
	 * @return string
	 */
	public static function info($file, $headers=array(), $context = '')
	{
		$file = format::path($file);

		if ( !file_exists($file) ) return array();

		if ( empty($headers) )
		{
			$headers = array(
				'name'=>'name',
				'title'=>'title',
				'description'=>'description',
				'author'=>'author',
				'url'=>'url',
			);
		}

		//读取文件的头部8KB
		$fp = fopen( $file, 'r' );
		$data = fread( $fp, 8192 );
		fclose( $fp );

		foreach ( $headers as $field => $regex )
		{
			preg_match( '/' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $data, ${$field});

			if ( !empty( ${$field} ) )
			{
				${$field} = file::cleanup_header_comment( ${$field}[1] );
			}
			else
			{
				${$field} = '';
			}
		}

		$info = compact( array_keys( $headers ) );

		return $info;
	}


	/**
	 * 写入文件, file_put_contents 增强版，自动创建文件夹及覆盖
	 *
	 * @param string $file 文件地址
	 * @param mixed $content 文件内容
	 * @param int $flags 写入标识，FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX
	 * @return boolean
	 */
	public static function put($file, $content='', $flags=null)
	{
		$file = format::path($file);

		//当目录不存在的情况下先创建目录
		if ( !folder::exists(dirname($file)) )
		{
			folder::create(dirname($file));
		}

		return file_put_contents($file, $content, $flags);
	}


	/**
	 * 删除文件，unlink 增强版
	 *
	 * @param string $file 文件地址
	 * @return boolean
	 */
	public static function delete($file)
	{
		$file = format::path($file);

		//尝试设置文件为可以读写删除
		@chmod($file, 0777);

		//删除文件
		@unlink($file);

		return @file_exists($file) ? false : true;
	}

 /**
  * 文件移动
  *
  * @param string $file 文件路径
  * @param string $path 新文件路径
	 *
  * @return bool
	 * @since 0.1
  */
	public static function move($file, $target, $overwrite=true)
	{
		$file 	= format::path($file);
		$target = format::path($target);

		if ( $file == $target ) return true;

		// 检查文件是否允许读写
		if ( !is_readable($file) OR !is_writable($file) ) return false;

		// 自动创建目标文件夹
		if ( !folder::create(dirname($target)) ) return false;

		// 如果复写，则删除目标文件
		if ( $overwrite ) file::delete($target);

		//移动文件
		return @rename($file, $target) ? true : false;
	}

 /**
  * 文件复制
  *
  * @param string $file 文件路径
  * @param string $path 新文件位置，含文件名称
	 *
  * @return bool
	 * @since 0.1
  */
	public static function copy($file, $target, $overwrite=true)
	{
		$file 	= format::path($file);
		$target = format::path($target);

		// 检查文件是否允许读写
		if (!is_readable($file) OR !is_writable($file)) return false;

		// 自动创建目标文件夹
		if ( !folder::create(dirname($target)) ) return false;

		// 如果复写，则删除目标文件
		if ( $overwrite ) file::delete($target);

		//移动文件
		return @copy($file, $target) ? $target : false;
	}



 /**
  * 文件重命名
  *
  * @param string $file 文件路径
  * @param string $newname 新文件名称
	 *
  * @return bool
	 * @since 0.1
  */
	public static function rename($file, $newname, $overwrite=true)
	{
		$file = format::path($file);

		$target = dirname($file).DS.$newname;

		return file::move($file, $target, $overwrite);
	}

	/**
	 * 返回安全的文件名称，去掉特殊字符
	 *
	 * @param string $file The name of the file [not full path]
	 * @return string The sanitised string
	 * @since 0.1
	 */
	public static function safename($file)
	{
		return preg_replace(array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#'), '', $file);
	}

    /**
     * 采集远程文件
	 *
     * @access public
     * @param string $url 远程文件名
     * @param string $local 本地保存文件名
     * @return mixed
     */
	public static function remote($url, $local)
	{
        $cp = curl_init($url);
        $fp = fopen($local,"w");
        curl_setopt($cp, CURLOPT_FILE, $fp);
        curl_setopt($cp, CURLOPT_HEADER, 0);
        curl_exec($cp);
        curl_close($cp);
        fclose($fp);
	}

	/**
	 * 文件下载
	 *
	 * @param $file 文件路径
	 * @param $name 文件名称
	 */

	public function download($file, $name='')
	{
		
		if ( preg_match( "/^(".preg_quote( ZOTOP_URL, "/" )."|".preg_quote( ZOTOP_PATH, "/" ).")(.*)\$/", $file, $matches ) )
		{
			$file = $matches[2];
		}		

		$file = strpos($file, ZOTOP_PATH) === false ? ZOTOP_PATH.DS.$file : $file;

		if ( !file::exists($file) )
		{
			throw new zotop_exception(t('文件 %s 不存在', $file));
		}

		$name = empty($name) ? basename($file) : $name;

		if ( strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie ' ) !== false )
		{
			$name = rawurlencode($name);
		}

		$ext = file::ext($name);

		if ( empty($ext) )
		{
			$ext = file::ext($file);
			$name = $name.'.'.$ext;
		}

		$size = sprintf("%u", file::size($file));

		//清理OB
		if ( ob_get_length() !== false ) @ob_end_clean();

		//下载数据
		header('Pragma: public');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header('Content-Transfer-Encoding: binary');
		header('Content-Encoding: none');
		header('Content-type: '.$ext);
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header('Content-length: '.$size);
		readfile($file);
		exit;
	}

	 /**
	  * 压缩文件夹为zip文件
	  *
	  * @param string $source 压缩路径
	  * @param array $target zip文件地址
	  * @param array $removepath 删除地址
	  * @param array $overwrite 是否允许覆盖
	  * @return array
	  */
	public static function zip($source, $target='', $removepath=true, $overwrite=true)
	{
		$source = format::path($source);

		if ( !folder::exists($source) )
		{
			throw new zotop_exception(t('待压缩文件夹 %s 不存在', $source));
		}

		$target = empty($target) ? dirname($source).DS.basename($source).'.zip' : $target;

		if ( file::exists($target) and !overwrite )
		{
			throw new zotop_exception(t('文件 %s 已经存在且不允许覆盖', $target));
		}

		if ( !folder::exists(dirname($target)) ) folder::create(dirname($target));

		$removepath =  ($removepath === true) ? $source : (empty($removepath) ? ZOTOP_PATH : $removepath);

		$zip = new PclZip($target);

		if ( $zip->create($source, PCLZIP_OPT_REMOVE_PATH, $removepath) )
		{
			unset($zip);
			return $target;
		}

		return false;
	}

	 /**
	  * 解压zip文件
	  *
	  * @param string $source zip文件地址
	  * @param array $target 解压路径
	  * @param array $overwrite 0: 不允许覆盖，1，允许覆盖，2：删除原目录下文件并解压
	  * @return array
	  */
	public static function unzip($source, $target='', $overwrite=2)
	{
		$source = format::path($source);

		$target = empty($target) ? dirname($source).DS.file::name($source,true) : $target;

		if ( !file::exists($source) OR file::ext($source) != 'zip' )
		{
			throw new zotop_exception(t('文件不存在或者不是zip文件',$source));
		}

		if ( folder::exists($target) )
		{
			if ( $overwrite == 0 ) throw new zotop_exception(t('解压目录已经存在且不允许覆盖', $target));

			if ( $overwrite == 2 ) folder::clear($target);
		}
		else
		{
			folder::create($target);
		}

		if ( class_exists('ZipArchive') )
		{
			$zip = new ZipArchive;
			
			if ( $zip->open($source) === true and $zip->extractTo($target) )
			{
				$zip->close();
				unset($zip);
				return $target;
			}
			
			unset($zip);
		}
		else
		{
			$zip = new PclZip($source);

			if ( $zip->extract (PCLZIP_OPT_PATH, $target, PCLZIP_OPT_REPLACE_NEWER) )
			{
				unset($zip);
				return $target;
			}
			
			unset($zip);
		}

		return false;
	}
}
?>