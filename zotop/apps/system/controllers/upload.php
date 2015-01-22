<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 图片、文件等上传，独立于附件管理，以便权限控制
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_upload extends admin_controller
{
	protected $folder;
	protected $attachment;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->folder 		= m('system.attachment_folder');
		$this->attachment 	= m('system.attachment');
	}

	/**
	 * 附件上传对话框
	 *
	 */
	public function action_index($type)
	{
		// 获取全部的附件类型
		$types = $this->attachment->types();

		if ( !in_array($type, array_keys($types)) )
		{
			return $this->error(t('禁止访问'));
		}

		// 传入文件类型，上传时进行限制
		$params = array_merge($_GET, array('type' => $type));

		// 获取图片缩放参数，如果没有传入任何图片参数，则使用系统默认的参数
		foreach( array('image_resize','image_width','image_height','image_quality','watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity','watermark_quality' ) as $param )
		{
			$params[$param] = isset($params[$param]) ? $params[$param] : c('system.'.$param);
		}

		// 获取当前类型名称，如“图片”，并获取当前类型允许上传的格式及最大值设置
		$typename	= $types[$type];
		$allowexts	= c('system.attachment_'.$type.'_exts');
		$maxsize	= c('system.attachment_'.$type.'_size');

		// 获取当前已经上传到文件
		$files = $this->attachment->getRelated($_GET['dataid'], $type);

		$this->assign('title',t('上传%s',$typename));
		$this->assign('params', $params);
		$this->assign('type', $type);
		$this->assign('typename', $typename);
		$this->assign('allowexts', $allowexts);
		$this->assign('maxsize', intval($maxsize));
		$this->assign('folders', $folders);
		$this->assign('files', $files);

		$this->display();
	}


	/*
	 * 上传处理过程
	 *
	 * 传入数据可能包含以下字段
	 * 'app', 'dataid', 'status', 'maxfile':
	 * 'image_resize','image_width', 'image_height','image_quality',
	 * 'watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity'
	 */
	public function action_uploadprocess()
	{
		// 全部文件类型
		$types = array_keys($this->attachment->types());

		// 获取当前上传文件类型
		$type = $_POST['type'] ? $_POST['type'] : $this->attachment->type($_REQUEST["filename"]);

		// 获取文件类型[image|file|video|audio]的上传格式及大小限制并上传
		if ( $type and in_array($type, $types) )
		{
			$this->attachment->allowexts 	= c('system.attachment_'.$type.'_exts');
			$this->attachment->maxsize 		= c('system.attachment_'.$type.'_exts');

			// 上传成功
			if ( $file = $this->attachment->upload($_POST) )
			{
				exit(json_encode(array(
					'state'		=> true,
					'content' 	=> t('{1}上传成功',$file['name']),
					'time'		=> 2,
					'file'		=> $file,
				)));
			}

			return $this->error($this->attachment->error());

		}

		return $this->error(t('错误文件类型'));
	}

	/**
	 * 附件库对话框
	 *
	 */
	public function action_library($type='')
	{
		// 允许文件类型名称，如：图像
		$typename = empty($type) ? t('文件') : $this->attachment->types($type);

		$this->assign('title',t('从库中选择'));
		$this->assign('type',$type);
		$this->assign('typename',$typename);
		$this->assign($_GET);
		$this->display();
	}

	/**
	 * 附件库数据源
	 *
	 */
	public function action_libraryData()
	{
		@extract($_GET);

		if( $type )
		{
			$where[] = array('type','=',$type);
		}

		// 如果传入类别参数
		if( $folderid )
		{
			if( !empty($where) )$where[] = 'and';
			$where[] = array('folderid','=',$folderid);
		}

		$dataset = $this->attachment->where($where)->orderby('uploadtime','desc')->getPage($page,$pagesize);

		exit(json_encode($dataset));
	}

	/**
	 * 目录浏览对话框
	 *
	 */
	public function action_dirview($type='')
	{
		// 允许文件类型名称，如：图像
		$typename 	= empty($type) ? t('文件') : $this->attachment->types($type);
		$exts 		= empty($type) ? array() : $this->attachment->exts($type);

		// 获取模版根目录
		$root = ZOTOP_PATH_UPLOADS;

		// 获取当前文件名称
		if ( $file = $_GET['file'] )
		{
			$file = str_replace(ZOTOP_URL_UPLOADS, '', ZOTOP_URL.rawurldecode($_GET['file']));
			unset($_GET['file']);
		}

		// 获取当前目录
		$dir = empty($file) ? rawurldecode($_GET['dir']) : dirname($file);
		$dir = trim($dir, '/');

		// 获取当前路径
		$path = $root.DS.$dir;

		// 获取当前路径下的全部文件夹和文件
		$_folders 	= folder::folders($path);
		$_files 	= folder::files($path);

		$folders = $files = array();

		// 获取文件夹详细信息
		foreach ( (array)$_folders as $f)
		{
			$folders[] = array(
				'name'	=>	$f,
				'url'	=>	u("system/upload/dirview/{$type}",array_merge($_GET,array('dir'=>rawurlencode($dir.'/'.$f))))
			);
		}

		// 获取文件详细信息
		foreach ( (array)$_files as $f)
		{
			if ( !in_array(file::ext($f),$exts) ) continue;

			$files[] = array(
				'name'	=>	$f,
				'url'	=>	ZOTOP_URL_UPLOADS.'/'.$dir.'/'.$f,
				'size'	=>	file::size($path.DS.$f),
				'time'	=>	file::time($path.DS.$f),
				'ext'	=>	file::ext($f),
				'type'	=>	$this->attachment->type($f)
			);
		}

		// 解析dir,生成position数组
		$position[] =  array('text'=>t('上传目录'),'url'=>u("system/upload/dirview/{$type}",array_merge($_GET, array('dir'=>''))));

		foreach( folder::dirmap($dir) as $d )
		{
			$position[] = array('text'=>$d[0],'url'=>u("system/upload/dirview/{$type}",array_merge($_GET, array('dir'=>$d[1]))));
		}


		$this->assign('title',t('目录浏览'));
		$this->assign('type',$type);
		$this->assign('typename',$typename);
		$this->assign('position',$position);
		$this->assign('folders',$folders);
		$this->assign('files',$files);
		$this->assign('dir',rawurlencode($dir));
		$this->assign($_GET);
		$this->display();
	}
}
?>