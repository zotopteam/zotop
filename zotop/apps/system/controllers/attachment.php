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

		$this->folder 		= m('system.attachment_folder');
		$this->attachment 	= m('system.attachment');
	}

 	/**
	 * list
	 *
	 */
	public function action_index($folderid=0)
	{
		$types 		= $this->attachment->types();
		$allowexts 	= $this->attachment->allowexts;

		// 如果传入类别参数
		if( $folderid )
		{
			$where[] = array('folderid','=',$folderid);
		}

		$dataset = $this->attachment->where($where)->orderby('uploadtime','desc')->getPage();

		$this->assign('title',t('附件管理'));
		$this->assign('type',$type);
		$this->assign('types',$types);
		$this->assign('allowexts',$allowexts);
		$this->assign('folderid',$folderid);
		$this->assign($dataset);
		$this->display();
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

	/*上传处理过程*/
	public function action_uploadprocess()
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
				return $this->success(t('{1}上传成功',$file['name']));
			}

			return $this->error($this->attachment->error());
		}

		return $this->error(t('参数错误'));
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

		$folders = $this->folder->category();

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