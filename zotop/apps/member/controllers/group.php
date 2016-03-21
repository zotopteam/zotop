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
class member_controller_group extends admin_controller
{
	protected $group;
	protected $model;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		//实例化group
		$this->group = m('member.group');
		$this->model = m('member.model');
	}

	/**
	 * index
	 *
	 */
	public function action_index($modelid='member')
    {
		if ( $post = $this->post() )
		{
			if ( $this->group->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->group->error());
		}

		$data   = $this->group->where('modelid',$modelid)->select();
		$models = $this->model->select();

		$this->assign('title',t('会员组'));
		$this->assign('data',$data);
		$this->assign('models',$models);
		$this->assign('modelid',$modelid);
		$this->display();
	}

	/**
	 * 添加
	 *
	 */
	public function action_add($modelid='member')
    {
		if ( $post = $this->post() )
		{
			if ( $this->group->add($post) )
			{
				return $this->success(t('保存成功'),u('member/group/index/'.$post['modelid']));
			}

			return $this->error($this->group->error());
		}

		$data = array('modelid'=>$modelid);

		$this->assign('title',t('添加会员组'));
		$this->assign('data',$data);
		$this->display('member/group_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->group->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('member/group/index/'.$post['modelid']));
			}

			return $this->error($this->group->error());
		}

		$data = $this->group->get($id);

		$this->assign('title',t('添加会员组'));
		$this->assign('data',$data);
		$this->display('member/group_post.php');
	}

	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if( $this->post() )
		{
			if ( $this->group->delete($id) )
			{
				return $this->success(t('删除成功'),u('member/group'));
			}

			return $this->error($this->group->error());
		}
	}
}
?>