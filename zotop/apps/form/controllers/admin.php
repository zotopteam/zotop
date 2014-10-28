<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 自定义表单 后台控制器
*
* @package		form
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class form_controller_admin extends admin_controller
{
	protected $form;

	/**
	 * 重载__init函数
	 * 
 	 * @return void
	 */
	public function __init()
	{
		parent::__init();

		$this->form = m('form.form');
	}

	/**
	 * 表单列表
	 * 
 	 * @return void 显示页面
     */
	public function action_index()
    {
		if ( $post = $this->post() )
		{
			if ( $this->form->order($post['id']) )
			{
				return $this->success(t('操作成功'),request::referer());
			}

			return $this->error($this->form->error());
		}

    	$data = $this->form->getall();

		$this->assign('title',t('表单管理'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加
	 *
 	 * @return void|json 显示页面或者返回操作结果
     */
	public function action_add()
	{
		if ( $post = $this->post() )
		{
			if ( $this->form->add($post) )
			{
				return $this->success(t('保存成功'),u('form/admin'));
			}

			return $this->error($this->form->error());
		}

		$data = array();
		$data['settings']['listtemplate'] 	= 'form/list.php';
		$data['settings']['detailtemplate'] = 'form/detail.php';
		$data['settings']['posttemplate'] 	= 'form/post.php';

		$this->assign('title',t('添加表单'));
		$this->assign('data',$data);
		$this->display('form/admin_post.php');
	}

	/**
	 * 编辑
	 *
 	 * @param  int $id 表单编号
 	 * @return void|json 显示页面或者返回操作结果
     */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->form->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('form/admin'));
			}

			return $this->error($this->form->error());
		}

		$data = $this->form->get($id);

		$this->assign('title',$data['name']);
		$this->assign('data',$data);
		$this->display('form/admin_post.php');
	}

    /**
     * 检查字段值是否被占用
     *
 	 * @param  string $ignore 允许忽略的数据
 	 * @return json 操作结果
     */
	public function action_check($key,$ignore='')
	{
		if ( empty($ignore) )
		{
			$count = $this->form->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->form->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}

    /**
     * 检查数据表名称是否被占用
     *
 	 * @param  string $ignore 允许忽略的数据
 	 * @return json 操作结果
     */
	public function action_checktable($ignore='')
	{
		$table = $_GET['table'];

		// 检查表名称是否有效
		if ( !preg_match('/^[a-z][a-z0-9_]*$/',$table) )
		{
			exit('"'.t('只能由小写英文字母、数字和下划线组成，且有英文字母开头').'"');
		}

		$count = ($table == $ignore) ? 0 : $this->form->db()->table($table)->exists();

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}

    /**
     * 设置状态，禁用或者启用
     *
 	 * @param  int $id 表单编号
 	 * @return json 操作结果
     */
	public function action_status($id)
	{
		if ( $this->form->status($id) )
		{
			return $this->success(t('操作成功'),request::referer());
		}
		return $this->error($this->form->error());
	}

 	/**
 	 * 删除操作
 	 * 
 	 * @param  int $id 表单编号
 	 * @return json 操作结果
 	 */
	public function action_delete($id)
	{
		if ( $this->form->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->form->error());
	}			
}
?>