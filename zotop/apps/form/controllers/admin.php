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
	 */
	public function __init()
	{
		parent::__init();

		$this->form = m('form.form');
	}

	/**
	 * 表单列表
	 *
	 */
	public function action_index()
    {
    	$data = $this->form->getall();

		$this->assign('title',t('表单管理'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加表单
	 *
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

		$this->assign('title',t('添加表单'));
		$this->assign('data',$data);
		$this->display('form/admin_post.php');
	}

	/**
	 * 添加表单
	 *
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

		$this->assign('title',t('表单设置'));
		$this->assign('data',$data);
		$this->display('form/admin_post.php');
	}

    /**
     * 检查字段值是否被占用
     *
     * @return bool
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
     * @return bool
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
	 * @param string $id 应用标识(ID)
     * @return void
     */
	public function action_status($id)
	{
		if ( $this->form->status($id) )
		{
			return $this->success(t('操作成功'),request::referer());
		}
		return $this->error($this->form->error());
	}		
}
?>