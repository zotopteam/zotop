<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 *
 *
 * @package		zotop
 * @class		url_base
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */
class plupload
{
	public $error = 0;
	public $savepath;
	public $maxsize = 20; //单位MB
	public $overwrite = true;
	public $allowexts;
	public $autoclean = true; // 自动清理临时文件
	public $maxage = 3600; // 临时文件生存周期，单位秒


	public function save($targetfile='')
	{
		@set_time_limit(5 * 60);

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		// 如果定义文件保存位置，则获取目标目录
		if ( !empty($targetfile) )
		{
			$this->savepath = dirname($targetfile);
		}

		// 获取文件名称
		if ( isset($_REQUEST["name"]) )
		{
			$name = $_REQUEST["name"];
		}
		elseif (!empty($_FILES))
		{
			$name = $_FILES["file"]["name"];
		}
		else
		{
			$name = uniqid("file_");
		}

		// 限定文件格式
		if( !in_array(file::ext($name), explode(',',$this->allowexts) ) )
		{
			return $this->error(t('文件类型错误，允许格式：%s', $this->allowexts));
		}

		// 获取文件路径（确保所在文件夹可写）
        if ( false === $filepath = $this->filepath($name)  )
		{
			return $this->error($this->error);
		}

		// 获取chunk及chunks
		$chunk 	= isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

		// 写入临时文件
		if ( !$out = @fopen("{$filepath}.part", $chunks ? "ab" : "wb") )
		{
			return $this->error('Failed to open output stream');
		}

		if ( !empty($_FILES) )
		{
			if ( $_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"]) )
			{
				return $this->error('Failed to move file');
			}

			if ( !$in = @fopen($_FILES["file"]["tmp_name"], "rb") )
			{
				return $this->error('Failed to open input stream');
			}
		}
		else
		{
			if (!$in = @fopen("php://input", "rb"))
			{
				return $this->error('Failed to open input stream');
			}
		}

		while ( $buff = fread($in, 4096) )
		{
			fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		// 文件成功上传
		if ( !$chunks || $chunk == $chunks - 1 )
		{
			// Strip the temp .part suffix off
			@rename("{$filepath}.part", $filepath);

			// 文件检查
			if ( @filesize($filepath) > $this->maxsize()  )
			{
				@unlink($filepath);
				return $this->error(t('文件大小不能超过 %s', format::size($this->maxsize())));
			}

			// 获取文件名称
			$filename = empty($targetfile) ? $this->filename($filepath) : basename($targetfile);

			// 如果名称变更则使用新名称，不然直接试用uploader生成的名称
			if ( !empty($filename) )
			{
				$newfile = dirname($filepath).DS.$filename;

				if ( file_exists($newfile) )
				{
					if ( $this->overwrite == false or @unlink($newfile) == false )
					{
						return $this->error(t('文件已存在且不允许被覆盖'));
					}
				}

				@rename($filepath, $newfile);

				$filepath = $newfile;
			}

			return $filepath;
		}

		return false;
	}

	/*
	 * 最大值转换，获取真实的大小
	 *
	 *
	 */
	public function maxsize()
	{
		$maxsize = ( is_numeric($this->maxsize) and  $this->maxsize > 0.1 ) ? $this->maxsize : 20;
		$maxsize = $maxsize * 1024 * 1024;
		return $maxsize;
	}

	/*
	 * 获取文件名称
	 */
	public function filename($file)
	{
		return date('Ymdhis').rand(1000, 9999).'.'.file::ext($file);
	}

	/*
	 * 获取文件路径，如果路径不存在，自动创建路径
	 */
	public function filepath($name)
	{
		//返回实际目录，并去掉末位的DS
		$savepath = $this->parse_path($this->savepath);

		//支持相对根路径的目录，如aaa/aaa
		if ( stripos($savepath, ZOTOP_PATH) === false )
		{
			$savepath = ZOTOP_PATH.DS.trim($savepath, DS);
		}

		//目录检测
		if( !folder::create($savepath, 0777) )
		{
			return $this->error(t('目录不存在且无法自动创建'));
		}

		//设置目录为可写状态
		@chmod($savepath, 0777);

		//可写检测
		if( !is_writeable($savepath) && ($savepath != DS) )
		{
			return $this->error(t('目录没有写入权限'));
		}

		$filepath = $savepath.DS.$name;

		// 清理临时文件
		if ( $this->autoclean )
		{
			if ( !$dir = opendir($savepath) )
			{
				return $this->error(t('Failed to open temp directory'));
			}

			while ( ($file = readdir($dir)) !== false )
			{
				$tmpfile = $savepath . DIRECTORY_SEPARATOR . $file;

				if ( $tmpfile == "{$filepath}.part")
				{
					continue;
				}

				if ( preg_match('/\.part$/', $file) && (filemtime($tmpfile) < time() - $this->maxage) )
				{
					@unlink($tmpfile);
				}
			}
			closedir($dir);
		}

		return $filepath;
	}

	/*
	 * 接续文件路径，支持变量，如： $type = 文件类型 ，$year = 年 ，$month = 月 ，$day = 日
	 *
	 * @param string $filename，
	 */
	public function parse_path($filepath)
	{
		$p = array(
			'[YYYY]' => date("Y"),
			'[MM]' => date("m"),
			'[DD]' => date("d"),
	    );

	    $path = strtr($filepath, $p);

		return rtrim($path, DS);
	}

	public function error($error='')
	{
		if ( empty($error) )
		{
			return empty($this->error) ? true : $this->error;
		}

		$this->error = $error;

		return false;
	}
}
?>