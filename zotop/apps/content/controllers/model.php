<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 附件管理
 *
 * @package		content
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_controller_model extends admin_controller
{
	protected $model;
	protected $content;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->model = m('content.model');
	}

	/**
	 * 初始化模型，当没有任何模型的时候自动导入系统自带的模型，并自动返回前页
	 * 
	 * @return null
	 */
	public function action_init()
	{
		if ( !$this->model->cache() )
		{
			$files = folder::files(A('content.path').DS.'libraries'.DS.'model',false,true,'model');

			foreach ($files as $file)
			{
				$this->model->import(include($file));
			}
		}

		$this->redirect(request::referer());
	}

 	/**
	 * 模型管理
	 * 
     * @return mixed
	 */
	public function action_index()
	{
		if ( $post = $this->post() )
		{
			if ( $this->model->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->model->error());
		}

		$data = $this->model->orderby('listorder','asc')->select();

		foreach( $data as &$d )
		{
			$d['datacount'] = $this->model->datacount($d['id']);
		}

		$this->assign('title',t('模型管理'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加模型
	 *
	 * @param string $id 模型标识(ID)
     * @return mixed
	 */
    public function action_add()
    {
		if ( $post = $this->post() )
		{
			if ( $this->model->add($post) )
			{
				return $this->success(t('操作成功'),u('content/model'));
			}

			return $this->error($this->model->error());
		}

		$data = array();
		$data['childs'] = array();

		$this->assign('title',t('新建模型'));
		$this->assign('data',$data);
		$this->display('content/model_post.php');
    }

	/**
	 * 编辑模型
	 *
	 * @param string $id 模型标识(ID)
     * @return mixed
	 */
    public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->model->edit($post, $id) )
			{
				return $this->success(t('操作成功'),u('content/model'));
			}

			return $this->error($this->model->error());
		}

		$data = $this->model->get($id);

		$this->assign('title',t('模型设置'));
		$this->assign('data',$data);
		$this->display('content/model_post.php');
    }

    /**
     * 设置状态，禁用或者启用
     *
	 * @param string $id 模型标识(ID)
     * @return mixed
     */
	public function action_status($id)
	{
		if ( $this->model->status($id) )
		{
			return $this->success(t('操作成功'),u('content/model'));
		}
		return $this->error($this->model->error());
	}


	/**
	 * 删除模型
	 * 
	 * @param string $id 模型标识(ID)
     * @return mixed
	 */
	public function action_delete($id)
	{
		if ( $this->model->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->model->error());
	}

    /**
     * 导出模型
     *
	 * @param string $id 模型标识(ID)
     * @return void
     */
	public function action_export($id)
	{
		$data = $this->model->export($id);
		$data = arr::export($data,true);

		header('Content-Disposition: attachment; filename="'.$id.'.model"');
		echo '<?php return '.$data.' ?>';
		exit;
	}


	/**
	 * 导入模型上传过程
	 * 
	 * @return mixed
	 */
	public function action_upload()
	{
		// 文件上传
		$upload = new plupload();
		$upload->allowexts 	= 'model';
		$upload->savepath 	= ZOTOP_PATH_RUNTIME.DS.'temp';
		$upload->maxsize 	= 0;

		if ( $file = $upload->save($filepath) )
		{
			//return $this->message(array('state'=>true,'content'=>t('上传成功'),'file'=>$file));
			
			try
			{
				$model = include($file);				
			}
			catch (Exception $e)
			{
				return $this->error($e->getMessage());
			}
			

			if ( $this->model->import($model) )
			{
				return $this->success(t('导入成功'),U('content/model'));
			}

			return $this->error($this->model->error());

		}

		return $this->error($upload->error);
	}


}
?>