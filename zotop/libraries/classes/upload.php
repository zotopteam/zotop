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
class upload
{
	protected $uploads = 0;
	protected $error = 0;

	public $files = array();
	public $field = 'filedata';
	public $savepath;
	public $maxsize = 0;
	public $overwrite = true;
	public $allowexts = 'jpg|jpeg|gif|bmp|png|doc|docx|xls|xlst|ppt|pdf|txt|rar|zip';
	public $filename = '[time]';
	public $maxfile = 10;
	public $totalsize = 0;



    /**
     * 初始化控制器
     *
     */
    public function __construct($field='filedata')
    {
		$this->field = $field;
		$this->maxsize = c('system.upload_maxsize');
		$this->savepath = ZOTOP_PATH_UPLOADS.DS.c('system.upload_dir');
		$this->allowexts = c('system.upload_allowexts');
    }

    /**
     * 上传保存过程
     *
     * @param string $field 上传文件字段名称
     * @return mixed
     */
	public function save()
	{
		$uploadfiles = array();

		//字段名称
		$field = $this->field;

		//初始化上传数据
		$this->uploads = count($_FILES[$field]['name']);

		if ( 0 == $this->uploads )
		{
			$this->error = 4;//没有文件被上传，没有设置上传文件
			return false;
		}

		if ( 1 == $this->uploads )
		{
			$uploadfiles[0] = array(
				'guid' => md5_file($_FILES[$field]['tmp_name']),
				'name' => $_FILES[$field]['name'],
				'tmp_name' => $_FILES[$field]['tmp_name'],
				'type' => $_FILES[$field]['type'],
				'size' => $_FILES[$field]['size'],
				'ext' => $this->getFileExt($_FILES[$field]['name']),
				'error' => $_FILES[$field]['error']
			);
		}
		else
		{
			foreach( $_FILES[$field]['error'] as $key => $error )
			{
				if ( $error === UPLOAD_ERR_NO_FILE )
				{
					$this->error = 4;
					continue;
				}

				$uploadfiles[$key] = array(
					'guid' => md5_file($_FILES[$field]['tmp_name'][$key]),
					'name' => $_FILES[$field]['name'][$key],
					'tmp_name' => $_FILES[$field]['tmp_name'][$key],
					'type' => $_FILES[$field]['type'][$key],
					'size' => $_FILES[$field]['size'][$key],
					'ext' => $this->getFileExt($_FILES[$field]['name'][$key]),
					'error' => $_FILES[$field]['error'][$key]
				);
			}
		}

		//上传
		foreach($uploadfiles as $key=>$file)
		{
			$this->move($file);
		}

		if ( is_array($this->files) and !empty($this->files) )
		{
			return $this->files;
		}

		return array();
	}

	public function move($file)
	{
		//检查系统上传错误
		if ( $file['error'] !== UPLOAD_ERR_OK )
		{
			$this->error = $file['error'];
			return false;
		}

		//格式检查
		if ( !$this->isAllowedFile($file) )
		{
			$this->error = 10; //上传格式错误
			return false;
		}

		//文件大小检查
		if ( $this->maxsize() && $file['size'] > $this->maxsize() )
		{
			$this->error = 11; // 超出限制
			return false;
		}


		//文件检查
		if(!$this->isUploadedFile($file['tmp_name']))
		{
			$this->error = 12;
			return false;
		}

		//获取存储的实际文件
		$savefile = $this->filepath($file); // E://wwwroot/zotop/uploads/dsfsdfasdfsdfsd.jpg

		//不允许覆写则调过该过程
		if ( !$this->overwrite && file_exists($savefile) )
		{
			$this->error = 13;
			return false;
		};

		//移动上传文件
		if( move_uploaded_file($file['tmp_name'], $savefile) || @copy($file['tmp_name'], $savefile) )
		{
			@chmod($savefile, 0644);
			@unlink($file['tmp_name']);
			$filepath = substr($savefile,strlen(ZOTOP_PATH));
			$this->files[] = array(
				'id'	=> md5($file['guid'].microtime(true)),
				'guid'	=> $file['guid'],
				'name'	=> $file['name'],
				'path'	=> $filepath, //转换为站内路径
				'url'	=> format::url(ZOTOP_URL.$filepath), //转换为url
				'type'	=> $file['type'],
				'size'	=> $file['size'],
				'ext'	=> $file['ext'],
			);
			return true;
		}
		@unlink($file['tmp_name']);
		return false;
	}

	/*
	 * 最大值转换，获取真实的大小
	 *
	 *
	 */
	public function maxsize()
	{
		$maxsize = $this->maxsize; //单位MB
		$maxsize = $maxsize * 1024 * 1024;
		return $maxsize;
	}

	/*
	 * 根据文件存储路径，获取文件的真实地址 如：E://wwwroot/zotop/uploads/dsfsdfasdfsdfsd.jpg
	 *
	 * @param string $file，
	 * @return string
	 */
	public function filepath($file)
	{
		//返回实际目录
		$savepath = $this->savepath;

		if ( strpos($savepath,ZOTOP_PATH) === false )
		{
			$savepath = ZOTOP_PATH.DS.$savepath;
		}

		$savepath = $this->parsePath($savepath); //替换特殊变量

		//目录检测
		if( !is_dir($savepath) && !folder::create($savepath, 0777) )
		{
			$this->error = 8; //目录不存在且无法自动创建
			return false;
		}

		@chmod($savepath, 0777);

		if(!is_writeable($savepath) && ($savepath != '/'))
		{
			$this->error = 9; //不可写
			return false;
		}

		$filepath = $savepath.DS.$this->filename($file);

		return $filepath;
	}


	/*
	 * 根据临时文件获取真实文件名称
	 *
	 * @param string $file
	 * @return string
	 */
	public function filename($file)
	{

		$filename = $this->filename; //获取文件命名方式

		if ( $filename == '[time]' )
		{
			$newfilename = date('Ymdhis').rand(1000, 9999).'.'.$file['ext'];
		}
		elseif ( $filename == '[md5]' ||  $filename == '[id]' )
		{
			$newfilename = $file['id'].'.'.$file['ext'];
		}
		else
		{
			$newfilename = $this->cleanFileName($file['name']);
		}

		return $newfilename;
	}


    /**
     * 获取文件扩展名
     *
	 * @param string $filename，
     * @return mixed
     */
	public function getFileExt($filename)
	{
		$x = explode('.', $filename);

		return strtolower(end($x));
	}

	/*
	 * 过滤文件名称中的非法字符
	 *
	 * @param string $filename，
	 * @return string
	 */
	public function cleanFileName($filename)
	{
		$bad = array(
				"<!--",
				"-->",
				"'",
				"<",
				">",
				'"',
				'&',
				'$',
				'=',
				';',
				'?',
				'/',
				"%20",
				"%22",
				"%3c",		// <
				"%253c", 	// <
				"%3e", 		// >
				"%0e", 		// >
				"%28", 		// (
				"%29", 		// )
				"%2528", 	// (
				"%26", 		// &
				"%24", 		// $
				"%3f", 		// ?
				"%3b", 		// ;
				"%3d"		// =
		);

		$filename = str_replace($bad, '', $filename);

		return stripslashes($filename);
	}

	/*
	 * 接续文件路径，支持变量，如： $type = 文件类型 ，$year = 年 ，$month = 月 ，$day = 日
	 *
	 * @param string $filename，
	 * @return string
	 */
	public function parsePath($path)
	{
		$p = array(
			'{YYYY}' => date("Y"),
			'{MM}' => date("m"),
			'{DD}' => date("d"),
	    );

	    $path = strtr($path, $p);
		$path = format::path($path);
		return $path;
	}

	/**
	 * 检查文件是否已经上传，一般用于检测临时文件
	 *
	 * @param string $file 文件路径
	 * @return boolean
	 */
	public function isUploadedFile($file)
	{
		return is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file));
	}

	/**
	 * 检查文件是否是允许上传的文件
	 *
	 * @param array $file 文件数组
	 * @return boolean
	 */
	public function isAllowedFile($file)
	{
		$allowexts = $this->allowexts;

		if ( $allowexts == '*' )
		{
			return true;
		}

		if ( is_array($allowexts) )
		{
			$allowexts = implode('|', $allowexts);
		}
		else
		{
			$allowexts = str_replace(array(',','/','\\'), '|', $allowexts);
		}

		$fileext = $file['ext'];

		return preg_match("/^(".strtolower($allowexts).")$/", $fileext);
	}


	/*
	 * 获取上传错误
	 *
	 * @return mix
	 */
	function error()
	{
		$messages = array(
			0 => t('文件上传成功'),
			1 => t('上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值'),
			2 => t('上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值'),
			3 => t('文件只有部分被上传'),
			4 => t('没有文件被上传'),
			5 => t('请选择要上传的文件'),
			6 => t('找不到临时文件夹'),
			7 => t('文件写入临时文件夹失败'),
			8 => t('目录不存在且无法自动创建'),
			9 => t('目录没有写入权限'),
			10 => t('不允许上传该类型文件'),
			11 => t('文件超过了管理员限定的大小'),
			12 => t('非法上传文件'),
			13 => t('文件已经存在，且系统不允许覆盖已有文件'),
		);

		return ( $this->error  == 0 ) ? false : $messages[$this->error];
	}

}
?>