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

		// 如果没有模型，自动导入系统自带的模型 TODO 改进这里的安装体验
		if ( empty($data) )
		{
			$this->redirect(u('content/model/init'));
		}

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
   	 * 验证时的ajax回调，检查字模型标识(id)等是否被占用
   	 * 
   	 * @param  string $key    字段名
   	 * @param  string $ignore 避免的字段值
   	 * @return json
   	 */
	public function action_check($key, $ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->model->where($key, $_GET[$key])->count();
		}
		else
		{
			$count = $this->model->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
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
		// 输出JS，手动关闭trace
    	C('system.trace', false);

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