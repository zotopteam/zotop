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
class shop_controller_brand extends admin_controller
{
	private $brand;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->brand = m('shop.brand');
	}


	/**
	 * index
	 *
	 */
	public function action_index()
    {
		if ( $post = $this->post() )
		{
			if ( $this->brand->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->brand->error());
		}

		$data = $this->brand->select();

		$this->assign('title',t('商品品牌'));
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
			if ( $this->brand->add($post) )
			{
				return $this->success(t('保存成功'),u('shop/brand'));
			}

			return $this->error($this->brand->error());
		}

		$data = array();

		$this->assign('title',t('添加品牌'));
		$this->assign('data',$data);
		$this->display('shop/brand_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->brand->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('shop/brand'));
			}

			return $this->error($this->brand->error());
		}

		$data = $this->brand->get($id);

		//debug::dump($data['value']);exit();

		$this->assign('title',t('编辑品牌'));
		$this->assign('data',$data);
		$this->display('shop/brand_post.php');
	}

	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if( $this->post() )
		{
			if ( $this->brand->delete($id) )
			{
				return $this->success(t('删除成功'),u('shop/brand'));
			}

			return $this->error($this->brand->error());
		}
	}
}
?>