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
class member_controller_admin extends admin_controller
{
	private $member;
	private $model;
	private $user;
	private $group;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->member = m('member.member');
		$this->model = m('member.model');
		$this->group = m('member.group');
		$this->user = m('system.user');
	}

	/**
	 * index 动作
	 *
	 */
	public function action_index()
    {

		$models = $this->model->getall();
		$groups = $this->group->cache();

		$dataset = $this->user->where('modelid','in',array_keys($models))->orderby('logintime','desc')->getPage();

		$this->assign('title',t('会员管理'));
		$this->assign($dataset);
		$this->assign('groups',$groups);
		$this->assign('models',$models);
		$this->display();
	}

	/**
	 * 多选操作
	 *
	 * @param $operation 操作
	 * @return mixed
	 */
    public function action_operate($operation, $v=null)
    {
		if ( $post = $this->post() )
		{
			switch($operation)
			{
				case 'delete' :
					$result = $this->member->delete($post['id']);
					break;
				case 'disabled' :
					$result = $this->member->disabled($post['id'], $v);
					break;
				default :
					break;
			}

			if ( $result )
			{
				return $this->success(t('%s成功',$post['operation']),request::referer());
			}
			$this->error(t('%s失败',$post['operation']));
		}

		$this->error(t('禁止访问'));
    }

	/**
	 * 添加
	 *
	 */
	public function action_add($modelid)
    {
		if ( $post = $this->post() )
		{
			if ( $this->member->add($post) )
			{
				return $this->success(t('保存成功'),u('member/admin'));
			}

			return $this->error($this->member->error());
		}

		$data = array('modelid'=>$modelid);

		$fields = m('member.field')->getFields($modelid);

		$models = $this->model->get($modelid);

		$groups = arr::hashmap(m('member.group')->getModel($modelid),'id','name');

		$this->assign('title',t('添加%s', $models['name']));
		$this->assign('data',$data);
		$this->assign('groups',$groups);
		$this->assign('fields',$fields);
		$this->assign('models',$models);
		$this->display('member/admin_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->member->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('member/admin'));
			}

			return $this->error($this->member->error());
		}

		$data = $this->member->get($id);

		$fields = m('member.field')->getFields($data['modelid'], $data);

		$groups = arr::hashmap(m('member.group')->getModel($data['modelid']),'id','name');

		$models = $this->model->get($data['modelid']);

		$this->assign('title',t('编辑%s', $models['name']));
		$this->assign('data',$data);
		$this->assign('groups',$groups);
		$this->assign('fields',$fields);
		$this->assign('models',$models);
		$this->display('member/admin_post.php');
	}

	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if( $this->post() )
		{
			if ( $this->member->delete($id) )
			{
				return $this->success(t('删除成功'),u('member/admin'));
			}

			return $this->error($this->member->error());
		}
	}

    /**
     * 检查用户名、邮箱是否被占用
     *
     * @return bool
     */
	public function action_check($key,$ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->member->user->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->member->user->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}
}
?>