<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * foler 类
 *
 * @copyright  (c)2009 zotop team
 * @package    core
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class folder
{
	/**
	 * 判断目录是否 存在
	 *
     * @param string $dir 路径
	 * @return bool
	 */
	public static function exists($dir)
	{
		return is_dir( format::path($dir) );
	}
	
	/**
	 * 返回文件夹的大小
	 *
     * @param string $dir 路径
	 * @return int
	 */
	public static function size($dir)
	{
        $size = 0;

		$dir = format::path($dir);

		$handle = @opendir($dir);

		while ( false!==($f = readdir($handle)) )
        {
			if($f != "." && $f != "..")
            {
                if( is_dir("$dir/$f") )
                {
                    $size += folder::size("$dir/$f");
                }
                else
                {
                    $size += filesize("$dir/$f");
                }
            }
        }
        
        @closedir($handle);

        return $size;
	}

	/**
	 * 检查目录可写性
	 *
	 * @param $dir 目录路径
	 * @return bool
	 */
	public static function writeable($dir)
	{		
		if( is_dir($dir) )
		{  
	        if( $fp = @fopen("$dir/chkdir.test", 'w') )
			{
	            @fclose($fp);      
	            @unlink("$dir/chkdir.test"); 
	            return true;
	        }
		}

		return false;
	}

	/**
	 * 创建文件夹，返回true或者false
	 *
	 * @param $dir 目录路径
	 * @param $mode 目录属性
	 * @return bool
	 */
	public static function create($dir, $mode = 0755)
	{
		$dir = format::path($dir);

		// 检查目标目录是否存在
		if ( is_dir($dir) || @mkdir($dir,$mode) ) return true;

		// 创建父目录
		if ( !folder::create(dirname($dir),$mode) ) return false;

		if ( @mkdir($dir,$mode) )
		{
			return $dir;
		}

		return false;
	}
	
	/**
	 * 清理文件夹中全部文件，并不删除目录本身
	 *
	 * @param $dir 目录路径
	 * @param $recurse 循环删除
	 * @return bool
	 */
	public static function clear($path, $recurse=true)
	{
		return folder::delete($path, $recurse, false);
	}
	

	/**
	 * 删除文件夹
	 *
	 * @param $dir 目录路径
	 * @param $recurse 循环删除
	 * @return bool
	 */
	public static function delete($path, $recurse=false, $deleteself=true)
	{
	    $path = format::path($path).DS;
		
		// 判断是否是文件夹
		if ( !is_dir($path) ) return false;
		
		// 删除文件夹下的全部文件
		$items = glob($path.'*');		
				
		if ( is_array($items) )
		{
			// 文件夹不为空，不允许删除
			if ( $recurse == false ) return false;

			// 循环删除
			foreach( $items as $item )
			{
				if ( is_dir($item) )
				{
					folder::delete($item,true);
				}
				else
				{
					if ( !@unlink($item) ) return false;
				}			
			}
		}
		
		//删除文件夹
		if ( $deleteself ) return @rmdir($path);

		return true;
	}


    /**
     * 文件夹重命名
     *
     * @param string $file  文件夹路径
     * @param string $newname 新名称，不含路径
	 *
     * @return bool
     */	
	public static function rename($path, $newname)
	{
		$path = format::path($path);

		$newpath = dirname($path).DS.$newname;

		if ( $path == $newpath ) return true;

		// 检查文件是否允许读写
		if (!is_readable($path) || !is_writable($path)) return false;
		
		// 目标文件夹已经存在
		if ( folder::exists($newpath) ) return false;

		return @rename($path,$newpath);
	}

     /**
     * 文件夹移动，将 $source 移动到目标位置 $target 下
     *
     * @param string $source  文件夹路径
     * @param string $target 目标位置	 
	 *
     * @return bool
     */	
	public static function move($source, $target, $overwrite=true)
	{
		$source = format::path($source).DS;
		$target = format::path($target).DS.basename($source).DS;
		
		if ( dirname($source) == $target ) return true;

		// 检查文件是否允许读写
		if ( !is_dir($source) OR !is_readable($source) OR !is_writable($source)) return false;

		// 复写
		if ( is_dir($target) and $overwrite) folder::delete($target);

		//自动创建目标文件夹
		if ( !folder::create($target) ) return false;
		
		//获取全部源文件
		$items = glob($source.'*');

		if ( is_array($items) )
		{
			foreach($items as $item)
			{
				$newpath = format::path($target.DS.basename($item));

				if ( is_dir($item) )
				{
					folder::move($item, $newpath);
				}
				else
				{
					if ( !@rename($item, $newpath) ) return false;
				}
			}
		}

		return @rmdir($source);
	}

     /**
     * 文件夹移动，将 $source 移动到目标位置 $target 下
     *
     * @param string $source  文件夹路径
     * @param string $target 目标位置	 
	 *
     * @return bool
	 * @since 0.1
     */	
	public static function copy($source, $target, $overwrite=true)
	{

		$source = format::path($source).DS;
		$target = format::path($target).DS.basename($source).DS;
		
		if ( dirname($source) == $target ) return true;

		//检查文件是否允许读写
		if ( !is_dir($source) OR !is_readable($source) )  return false;
		
		// 复写
		if ( is_dir($target) and $overwrite) folder::delete($target);
		
		//自动创建目标文件夹
		if ( !folder::create($target) ) return false;
		
		//获取全部源文件
		$items = glob($source.'*');

		if ( is_array($items) )
		{
			foreach($items as $item)
			{
				$newpath = format::path($target.DS.basename($item));

				if ( is_dir($item) )
				{
					folder::copy($item, $newpath);
				}
				else
				{
					if ( !@copy($item, $newpath) ) return false;
				}
			}
		}

		return true;
	}
    
	/**
	 * 返回目录下的全部文件的数组
	 * @param string $path 路径
	 * @param array $ext 特定的文件格式,如只获取jpg,png格式
	 * @param bool|int $recurse 子目录，或者子目录级数
	 * @param bool $fullpath 全路径或者仅仅获取文件名称
	 * @param array $ignore 忽略的文件夹名称
	 * @return array
	 */
	public static function files($path,  $recurse=false, $fullpath=false, $ext='', $ignore = array('.svn', 'CVS','.DS_Store','__MACOSX'))
	{

    	$files =array();
    	
	    $path = format::path($path);
	    
	    if( !is_dir($path) ) return false;
	    
	    $handle = opendir($path);
	    
	    while (($file = readdir($handle)) !== false)
	    {
	        if( $file != '.' && $file != '..' && !in_array($file,$ignore) )
	        {
	            $f = $path .DS. $file;

	            if( is_dir($f) )
	            {
	                if ( $recurse )
	                {
    	                if( is_bool($recurse) )
    	                {
    	                    $subfiles = folder::files($f,$recurse,$fullpath,$ext);
    	                }
    	                else
    	                {
    	                    $subfiles = folder::files($f,$recurse-1,$fullpath,$ext);
    	                }
                    	if( is_array($subfiles) )
	                    {
	                        $files = array_merge($files,$subfiles);
	                    }    	                
	                }	                
	            }
	            else
	            {   
	                if( $ext )
	                {
	                    if ( is_string($ext) ) $ext = explode(',', $ext);

	                    if ( is_array($ext) && in_array(file::ext($file),$ext) )
	                    {
	                        $files[] = $fullpath ? $f :  $file;
	                    }
	                }
	                else
	                {
	                    $files[] = $fullpath ? $f :  $file;
	                }
	                
	            }
	        }
	    }
	    closedir($handle);
	    return $files;
	}

	/**
	 * 返回目录下的全部文件夹的数组
	 * @param string $path 路径
	 * @param array $filter 
	 * @param bool|int $recurse 子目录，或者子目录级数
	 * @param bool $fullpath 全路径或者仅仅获取文件名称
	 * @param array $ignore 忽略的文件夹名称
	 * @return array
	 */	
	public static function folders($path, $recurse=false, $fullpath=false, $filter='.', $ignore = array('.svn', 'CVS','.DS_Store','__MACOSX'))
	{
	    $path = format::path($path);
	    
	    if( is_dir($path) )
		{
			$folders = array();

			$handle = opendir($path);
			
			while ( ($file = readdir($handle)) !== false )
			{
				$f = $path .DS. $file;

				if( $file != '.' && $file != '..' && !in_array($file,$ignore) && is_dir($f) )
				{
					if ( preg_match("/$filter/", $file) )
					{
						$folders[] = $fullpath ? $f : $file;
					}

					if ( $recurse )
					{
						if ( is_integer($recurse) )
						{
							$recurse--;
						}

						$subfolders = folder::folders($f, $recurse, $fullpath, $filter,$ignore);
						$folders = array_merge($folders, $subfolders);
					}       
				}	        
			}
			
			closedir($handle);

			return $folders;
	    }

		return false;
	}

	/**
	 * 格式化路径
	 *
	 * @param	string $path The full path to sanitise
	 * @return	string The sanitised string
	 */
	public function safename($path)
	{
		$ds		= ( DS == '\\' ) ? '\\'.DS : DS;
		return preg_replace(array('#[^A-Za-z0-9:\_\-'.$ds.' ]#'), '', $path);
	}

    /**
     * 将一个路径转化成名称-路径数组，一般用于生成position
     *
     * 用法：
     * @code php
     * $dir = 'cms/admin/common'
	 *
     * $arr = arr::dirpath($dir, '/');
     *
     * dump($arr);
     *   // 输出结果为
     *   // array(
     *   //   array('system','system'),
     *   //   array('admin','system/admin'),
	 *   //   array('common','system/admin/common'),
     *   // )
     * @endcode
     *
     * @param array $dir 路径
     * @param string $s 分隔符
     *
     * @return array 包含全部路径的数组
     */
    public function dirmap($dir, $s='/')
    {
		$data = array();

		if ( $dir )
		{
			$dirs = explode($s, trim($dir, $s));

			$path = '';

			foreach($dirs as $d)
			{
				$path .= $d.$s;
				$data[] = array($d, $path);
			}
		}
		return $data;
    }	

}
?>