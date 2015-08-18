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
class shop_controller_type extends admin_controller
{
	private $type;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->type = m('shop.type');
	}


	/**
	 * index
	 *
	 */
	public function action_index()
    {
		if ( $post = $this->post() )
		{
			if ( $this->type->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->type->error());
		}

		$data = $this->type->select();

		$this->assign('title',t('商品类型'));
		$this->assign('data',$data);
		$this->assign('types',$this->type->types);
		$this->assign('shows',$this->type->shows);
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
			if ( $this->type->add($post) )
			{
				return $this->success(t('保存成功'), u('shop/type'));
			}

			return $this->error($this->type->error());
		}

		$data = array('type'=>'text','attrs'=>array(array('id'=>1, 'name'=>'','type'=>'radio')));

		$this->assign('title',t('添加类型'));
		$this->assign('data',$data);
		$this->assign('types',$this->type->types);
		$this->assign('shows',$this->type->shows);
		$this->display('shop/type_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->type->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('shop/type'));
			}

			return $this->error($this->type->error());
		}

		$data = $this->type->get($id);

		//debug::dump($data['value']);exit();

		$this->assign('title',t('编辑类型'));
		$this->assign('data',$data);
		$this->assign('types',$this->type->types);
		$this->assign('shows',$this->type->shows);
		$this->display('shop/type_post.php');
	}

	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if( $this->post() )
		{
			if ( $this->type->delete($id) )
			{
				return $this->success(t('删除成功'),u('shop/type'));
			}

			return $this->error($this->type->error());
		}
	}
}
?>