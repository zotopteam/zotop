<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * wechat 公众号管理 控制器
 *
 * @package		wechat
 * @author		zotop hankx_chen@qq.com
 * @copyright	zotop
 * @license		http://www.zotop.com
 */
class wechat_controller_account extends admin_controller
{
	protected $account;
	protected $wechat;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->account = m('wechat.account');
	}


 	/**
	 * 公众号管理
	 * 
     * @return mixed
	 */
	public function action_index()
	{
		if ( $post = $this->post() )
		{
			if ( $this->account->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->account->error());
		}

		$data = $this->account->orderby('listorder','asc')->getAll();


		$this->assign('title',t('公众号管理'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加公众号
	 *
	 * @param string $id 公众号标识(ID)
     * @return mixed
	 */
    public function action_add()
    {
		if ( $post = $this->post() )
		{
			if ( $this->account->add($post) )
			{
				return $this->success(t('操作成功'),u('wechat/account'));
			}

			return $this->error($this->account->error());
		}

		$data = array();
		$data['childs'] = array();

		$this->assign('title',t('添加公众号'));
		$this->assign('data',$data);
		$this->display('wechat/account_post.php');
    }

	/**
	 * 编辑公众号
	 *
	 * @param string $id 公众号标识(ID)
     * @return mixed
	 */
    public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->account->edit($post, $id) )
			{
				return $this->success(t('操作成功'),u('wechat/account'));
			}

			return $this->error($this->account->error());
		}

		$data = $this->account->get($id);

		$this->assign('title',t('公众号设置'));
		$this->assign('data',$data);
		$this->display('wechat/account_post.php');
    }

    /**
     * 设置状态，禁用或者启用
     *
	 * @param string $id 公众号标识(ID)
     * @return mixed
     */
	public function action_status($id)
	{
		if ( $this->account->status($id) )
		{
			return $this->success(t('操作成功'),u('wechat/account'));
		}
		return $this->error($this->account->error());
	}


	/**
	 * 删除公众号
	 * 
	 * @param string $id 公众号标识(ID)
     * @return mixed
	 */
	public function action_delete($id)
	{
		if ( $this->account->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->account->error());
	}

}
?>