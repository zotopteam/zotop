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
class block_controller_datalist extends admin_controller
{
	protected $block;
	protected $category;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->block = m('block.block');
		$this->category = m('block.category');
		$this->datalist = m('block.datalist');
	}


	/**
	 * 排序
	 *
	 * @param int $blockid
     * @return void
	 */
    public function action_order($blockid)
    {
		if ( $post = $this->post() )
		{
			if ( $this->datalist->order($blockid, $post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->datalist->error());
		}
	}

	/**
	 * 添加
	 *
	 * @param int $categoryid
     * @return void
	 */
    public function action_add($blockid)
    {
		if ( $post = $this->post() )
		{
			if ( $id = $this->datalist->add($post) )
			{
				return $this->success(t('操作成功'),u('block/block/data/'.$blockid));
			}

			return $this->error($this->datalist->error());
		}

		//全部分类
		$categories = $this->category->cache();

		// 应用数据
		$block = $this->block->get($blockid);

		// 获取当前分类
		$category = $this->category->get($block['categoryid']);


		$this->assign('title',t('添加数据'));
		$this->assign('category',$category);
		$this->assign('categories',$categories);
		$this->assign('block',$block);
		$this->display('block/datalist_post.php');
    }

	/**
	 * 编辑
	 *
	 * @param int $id
     * @return void
	 */
    public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->datalist->edit($post, $id) )
			{
				return $this->success(t('操作成功'),u('block/block/data/'.$post['blockid']));
			}

			return $this->error($this->datalist->error());
		}

		//全部分类
		$categories = $this->category->cache();

		//获取当前数据
		$data = $this->datalist->get($id);

		// 应用数据
		$block = $this->block->get($data['blockid']);

		// 获取当前分类
		$category = $this->category->get($block['categoryid']);


		$this->assign('title',t('设置'));
		$this->assign('category',$category);
		$this->assign('categories',$categories);

		$this->assign('data',$data);
		$this->assign('block',$block);
		$this->display('block/datalist_post.php');
    }

 	/**
	 * 删除区块
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->datalist->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->datalist->error());
	}

}
?>