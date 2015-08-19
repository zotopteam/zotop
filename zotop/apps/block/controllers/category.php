<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 栏目管理
 *
 * @package		block
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class block_controller_category extends admin_controller
{
	protected $category;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->category = m('block.category');
	}

  	/**
	 * 分类管理
	 *
     * @return void
	 */
	public function action_index()
	{
		if ( $post = $this->post() )
		{
			if ( $this->category->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->category->error());
		}

		$data = $this->category->orderby('listorder','asc')->select();

		$this->assign('title',t('分类管理'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加分类
	 *
     * @return void
	 */
    public function action_add()
    {
		if ( $post = $this->post() )
		{
			if ( $this->category->add($post) )
			{
				return $this->success(t('操作成功'),u('block/category'));
			}

			return $this->error($this->category->error());
		}

		$this->assign('title',t('新建分类'));
		$this->assign('data',$data);
		$this->display('block/category_post.php');
    }

	/**
	 * 编辑分类
	 *
	 * @param int $id
     * @return void
	 */
    public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->category->edit($post, $id) )
			{
				return $this->success(t('操作成功'),u('block/category'));
			}

			return $this->error($this->category->error());
		}

		$data = $this->category->getbyid($id);

		$this->assign('title',t('编辑分类'));
		$this->assign('data',$data);
		$this->display('block/category_post.php');
    }

    /**
     * 设置状态，禁用或者启用
     *
	 * @param int $id
     * @return void
     */
	public function action_status($id)
	{
		if ( $this->category->status($id) )
		{
			return $this->success(t('操作成功'),u('block/category'));
		}
		return $this->error($this->category->error());
	}

 	/**
	 * 删除分类
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->category->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->block->error());
	}
}
?>