<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 附件管理
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_attachment extends admin_controller
{
	protected $folder;
	protected $attachment;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->folder = m('system.attachment_folder');
		$this->attachment = m('system.attachment');
	}

 	/**
	 * list
	 *
	 */
	public function action_index($type='list', $folderid=0)
	{

		// 如果传入类别参数
		if( $folderid )
		{
			$where[] = array('folderid','=',$folderid);
		}

		$dataset = $this->attachment->where($where)->orderby('uploadtime','desc')->getPage();

		$types = $this->attachment->types();
		$allowexts = $this->attachment->allowexts;

		$folders = $this->folder->orderby('listorder','asc')->getAll();
		$folders = arr::hashmap($folders,'id');

		$template = ( $type=='list' ) ? 'system/attachment_index.php' : 'system/attachment_advanced.php';

		$this->assign('title',t('附件管理'));
		$this->assign('type',$type);
		$this->assign('types',$types);
		$this->assign('allowexts',$allowexts);
		$this->assign('folders',$folders);
		$this->assign('folderid',$folderid);
		$this->assign($dataset);
		$this->display($template);
	}

	/**
	 * 多选操作
	 *
	 * @param $operation 操作
	 * @return mixed
	 */
    public function action_operate($operation)
    {
		if ( $post = $this->post() )
		{
			switch($operation)
			{
				case 'delete' :
					$result = $this->attachment->delete($post['id']);
					break;
				default :
					break;
			}

			if ( $result )
			{
				return $this->success(t('%s成功',$post['operation']));
			}
			$this->error(t('%s失败',$post['operation']));
		}

		$this->error(t('禁止访问'));
    }

	/**
	 * 附件库对话框
	 *
	 */
	public function action_library($type='')
	{
		// 允许文件类型名称，如：图像
		$typename = empty($type) ? t('文件') : $this->attachment->types($type);

		// 文件夹
		$folders = $this->folder->orderby('listorder','asc')->getAll();

		$this->assign('title',t('从库中选择'));
		$this->assign('type',$type);
		$this->assign('typename',$typename);
		$this->assign('folders',$folders);
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
				'url'	=>	u("system/attachment/dirview/{$type}",array_merge($_GET,array('dir'=>rawurlencode($dir.'/'.$f))))
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

		//debug::dump($dir);debug::dump($folders);debug::dump($files);exit();

		// 解析dir,生成position数组
		$position[] =  array('text'=>t('上传目录'),'url'=>u("system/attachment/dirview/{$type}",array_merge($_GET, array('dir'=>''))));

		foreach( folder::dirmap($dir) as $d )
		{
			$position[] = array('text'=>$d[0],'url'=>u("system/attachment/dirview/{$type}",array_merge($_GET, array('dir'=>$d[1]))));
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

	/**
	 * 附件上传对话框
	 *
	 */
	public function action_upload($type)
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


	/*上传处理过程*/
	public function action_process()
	{
		// 设置upload参数
		if ( $post = $this->post() )
		{
			$types = $this->attachment->types();

			if ( $post['type'] and in_array($post['type'], array_keys($types)) )
			{
				$this->attachment->allowexts	= c('system.attachment_'.$post['type'].'_exts');
				$this->attachment->maxsize		= c('system.attachment_'.$post['type'].'_size');
			}

			if ( $file = $this->attachment->upload($post) )
			{
				exit(json_encode($file));
			}

			exit(json_encode(array(
				'state'=>0,
				'content'=>$this->attachment->error()
			)));
		}

		exit(t('参数错误'));
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
		// 强制声明为AJAX状态
		$_REQUEST['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

		// 全部文件类型
		$types = array_keys($this->attachment->types());

		// 获取当前上传文件类型
		$type = $_POST['type'] ? $_POST['type'] : $this->attachment->type($_REQUEST["filename"]);

		// 获取文件类型[image|file|video|audio]的上传格式及大小限制并上传
		if ( $type and in_array($type, $types) )
		{
			$this->attachment->allowexts = c('system.attachment_'.$type.'_exts');
			$this->attachment->maxsize = c('system.attachment_'.$type.'_exts');

			// 上传成功
			if ( $file = $this->attachment->upload($_POST) )
			{
				exit(json_encode($file));
			}

			$this->error($this->attachment->error());

		}

		$this->error(t('错误文件类型'));
	}



	/**
	 * 下载附件
	 *
	 * @param $field 对应字段
	 * @param $id 附件编号
	 * @return mixed
	 */
    public function action_download($id)
    {
		$file = $this->attachment->getbyid($id);

		if ( $file['path'] and file::exists(ZOTOP_PATH_UPLOADS.DS.$file['path']) )
		{
			file::download(ZOTOP_PATH_UPLOADS.DS.$file['path'],$file['name']);
		}

		return $this->error(t('文件不存在'));
    }

	/**
	 * 编辑附件
	 *
	 * @param $field 对应字段
	 * @param $id 附件编号
	 * @return mixed
	 */
    public function action_edit($field, $id)
    {
		if ( $post = $this->post() )
		{
			$data = array($field =>	$post['newvalue']);

			if ( $this->attachment->update($data, $id) )
			{
				return $this->success(t('操作成功'),request::referer());
			}

			return $this->error($this->attachment->error());
		}
    }


 	/**
	 * 删除附件
	 *
	 */
	public function action_delete($id='')
	{
		$id = empty($id) ? $_GET['id'] : $id;

		if ( $this->attachment->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->attachment->error());
	}

 	/**
	 * 附件分类管理
	 *
	 */
	public function action_folder()
	{
		if ( $post = $this->post() )
		{
			if ( $this->folder->order($post['id']) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error($this->folder->error());
		}

		$folders = $this->folder->orderby('listorder','asc')->getAll();

		$this->assign('title',t('分类管理'));
		$this->assign('folders',$folders);
		$this->display();
	}

	/**
	 * 添加附件分类
	 *
	 * @param $parentid 父分类名称
	 * @return mixed
	 */
    public function action_folderAdd($parentid=0)
    {
		if ( $post = $this->post() )
		{
			$data = array('name' =>	$post['newvalue']);

			if ( $this->folder->add($data) )
			{
				return $this->success(t('操作成功'),request::referer());
			}

			return $this->error(t('操作失败'));
		}
    }

	/**
	 * 编辑附件分类
	 *
	 * @param $parentid 父分类名称
	 * @return mixed
	 */
    public function action_folderEdit($id)
    {
		if ( $post = $this->post() )
		{
			$data = array('name' =>	$post['newvalue']);

			if ( $this->folder->edit($data, $id) )
			{
				return $this->success(t('操作成功'),request::referer());
			}

			return $this->error(t('操作失败'));
		}
    }

 	/**
	 * 删除附件分类
	 *
	 */
	public function action_folderDelete($id)
	{
		if ( $this->folder->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->attachment->error());
	}
}
?>