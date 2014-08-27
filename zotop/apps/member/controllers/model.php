<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员 后台控制器
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class member_controller_model extends admin_controller
{
	protected $model;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		//实例化model
		$this->model = m('member.model');
	}

	/**
	 * index
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

		$data = $this->model->getall();

		$this->assign('title',t('会员模型'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加
	 *
	 */
	public function action_add()
    {
		if ( $post = $this->post() )
		{
			if ( $this->model->add($post) )
			{
				return $this->success(t('保存成功'),u('member/model'));
			}

			return $this->error($this->model->error());
		}

		$data = array(
			'disabled' => 0,
			'settings' => array(
				'register'	=> 0,
				'register_template'	=> 'member/register.php',

				'point'		=> 0,
				'amount'	=> 0,
			)
		);

		$this->assign('title',t('添加模型'));
		$this->assign('data',$data);
		$this->display('member/model_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->model->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('member/model'));
			}

			return $this->error($this->model->error());
		}

		// 模型数据
		$data = $this->model->get($id);

		// 用户组
		$groups = arr::hashmap(m('member.group')->getModel($id),'id','name');

		$this->assign('title',t('模型设置'));
		$this->assign('data',$data);
		$this->assign('groups',$groups);
		$this->display('member/model_post.php');
	}

	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if( $this->post() )
		{
			if ( $this->model->delete($id) )
			{
				return $this->success(t('删除成功'),u('member/model'));
			}

			return $this->error($this->model->error());
		}
	}
}
?>