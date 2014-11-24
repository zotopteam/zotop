<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 商城 后台控制器
*
* @package		shop
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class shop_controller_spec extends admin_controller
{
	private $spec;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->spec = m('shop.spec');
	}


	/**
	 * index
	 *
	 */
	public function action_index()
    {
		if ( $post = $this->post() )
		{
			if ( $this->spec->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->spec->error());
		}

		$data = $this->spec->getall();

		$this->assign('title',t('商品规格'));
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
			if ( $this->spec->add($post) )
			{
				return $this->success(t('保存成功'),u('member/spec'));
			}

			return $this->error($this->spec->error());
		}

		$data = array();

		$this->assign('title',t('添加规格'));
		$this->assign('data',$data);
		$this->assign('settings_fields',$this->settings_fields($data));
		$this->display('member/spec_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->spec->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('member/spec'));
			}

			return $this->error($this->spec->error());
		}

		$data = $this->spec->get($id);

		$this->assign('title',t('编辑规格'));
		$this->assign('data',$data);
		$this->assign('settings_fields',$this->settings_fields($data));
		$this->display('member/spec_post.php');
	}

	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if( $this->post() )
		{
			if ( $this->spec->delete($id) )
			{
				return $this->success(t('删除成功'),u('member/spec'));
			}

			return $this->error($this->spec->error());
		}
	}
}
?>