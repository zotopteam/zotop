<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 页面控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class guestbook_controller_admin extends admin_controller
{
	protected $guestbook;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->guestbook = m('guestbook.guestbook');
	}


	/**
	 * 列表
	 *
	 */
    public function action_index($status='')
    {
		if( $status )
		{
			$this->guestbook->where('status','=',$status);
		}

		if ( $keywords = $_REQUEST['keywords'] )
		{
			$this->guestbook->where(array(
				array('name','like',$keywords),
				'or',
				array('content','like',$keywords)
			));
		}

		$dataset = $this->guestbook->orderby('createtime','desc')->getPage();

		$this->assign('title',t('留言管理'));
		$this->assign('statuses',$statuses);
		$this->assign('statuscount',$statuscount);
		$this->assign('status',$status);
		$this->assign('keywords',$keywords);
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
					$result = $this->guestbook->where('id','in',$post['id'])->delete();
					break;
				case 'publish' :
				case 'pending' :
				case 'trash' :
					$result = $this->guestbook->where('id','in',$post['id'])->data(
array('status'=>$operation))->update();
					break;
				default :
					break;
			}

			if ( $result )
			{
				return $this->success(t('%s成功',$post['operation']), request::referer());
			}
			$this->error(t('%s失败',$post['operation']));
		}

		$this->error(t('禁止访问'));
    }

	/**
	 * 添加
	 *
	 */
	public function action_add()
	{
		if ( $post = $this->post() )
		{
			if ( $this->guestbook->add($post) )
			{
				return $this->success(t('%s成功',t('保存')),u('guestbook/admin'));
			}
			return $this->error($this->guestbook->error());
		}

		$data = array();

		$this->assign('title',t('添加'));
		$this->assign('data',$data);
		$this->display();
	}

 	/**
	 * 编辑
	 *
	 */
	public function action_reply($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->guestbook->reply($post,$id) )
			{
				return $this->success(t('%s成功',t('回复')));
			}
			return $this->error($this->guestbook->error());
		}

		$data = $this->guestbook->getbyid($id);

		$this->assign('title',t('回复'));
		$this->assign('data',$data);
		$this->assign('statuses',$this->guestbook->statuses);
		$this->display();
	}

 	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->guestbook->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->guestbook->error());
	}

 	/**
	 * 删除
	 *
	 */
	public function action_deleteByIp($id)
	{
		$data = $this->guestbook->getbyid($id);

		if ( $data and $this->guestbook->where('createip','=',$data['createip'])->delete() )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->guestbook->error());
	}
}
?>