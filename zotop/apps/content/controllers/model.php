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
		$this->content = m('content.content');
	}

 	/**
	 * 模型管理
	 *
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

		$data = $this->model->orderby('listorder','asc')->getAll();

		foreach( $data as &$d )
		{
			$d['datacount'] = $this->model->datacount($d['id']);
			$d['iscustom'] 	= ( $d['app'] == 'content' and $d['model'] == 'custom' ) ? true : false;
		}

		$this->assign('title',t('模型管理'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加模型
	 *
	 * @return mixed
	 */
    public function action_add()
    {
		if ( $post = $this->post() )
		{
			if ( $this->model->add($post) )
			{
				return $this->success(t('操作成功'),request::referer());
			}

			return $this->error($this->model->error());
		}

		$this->assign('title',t('新建模型'));
		$this->assign('data',$data);
		$this->display('content/model_post.php');
    }

	/**
	 * 编辑模型
	 *
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
	 * @param string $id 应用标识(ID)
     * @return void
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
	 * 禁止删除模型
	 *
	 */
	/*
	public function action_delete($id)
	{
		if ( $this->model->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->content->error());
	}
	*/

    /**
     * 导出模型
     *
	 * @param string $id 应用标识(ID)
     * @return void
     */
	public function action_export($id)
	{
		$data = $this->model->get($id);
		$fields = m('content.field')->getall($id);

		function var_export_min($var, $return = false)
		{
		    if (is_array($var))
		    {
		        $toImplode = array();
		        foreach ($var as $key => $value)
		        {
		            $toImplode[] = var_export($key, true).'=>'.var_export_min($value, true);
		        }
		        $code = 'array('.implode(',', $toImplode).')';
		        if ($return)
		        {
		        	return $code;
		        } 
		        else
		        {
		        	echo $code;	
		        }
		    } else {
		        return var_export($var, $return);
		    }
		}		

		$code  = "";
		$code .= "array(";
		foreach ($data as $key => $val)
		{
			$code .= "\n	'{$key}'=>'{$val}'";
		}
		$code .= "\n	'fields'=>array(";
		foreach ($fields as $key => $val)
		{
			$code .= "\n		'{$key}'=>".var_export_min($val,true).",";
		}
		$code .= "\n	)";
		$code .= "\n)";

		$code = str_replace(',)',')',$code);

		header('Content-Disposition: attachment; filename="'.$id.'.model"');
		echo $code;
		exit;
	}
}
?>