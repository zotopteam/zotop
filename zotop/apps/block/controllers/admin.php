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
class block_controller_admin extends admin_controller
{
	protected $block;
	protected $category;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->block 	= m('block.block');
		$this->category = m('block.category');
		$this->datalist = m('block.datalist');
	}


	/**
	 * 首页及列表
	 *
	 * @param int $categoryid
     * @return void
	 */
    public function action_index($categoryid=0)
    {
		if ( $post = $this->post() and $post['id'] )
		{
			// 排序
			if ( $this->block->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->block->error());
		}

		// 获取分类信息
		$categories = $this->category->getAll();

		// 搜索结果
		if ( $keywords = $_REQUEST['keywords'] )
		{
			$data = $this->block->where('name','like', $keywords)->getall();
		}
		else
		{
			$data = $this->block->where('categoryid',$categoryid)->getall();
		}


		$this->assign('title', A('block.name'));
		$this->assign('categories',$categories);
		$this->assign('categoryid',$categoryid);
		$this->assign('category',$categories[$categoryid]);
		$this->assign('types',$this->block->types);
		$this->assign('keywords',$keywords);
		$this->assign('data',$data);
		$this->display();
	}


	/**
	 * 添加
	 *
	 * @param int $categoryid
     * @return void
	 */
    public function action_add($categoryid)
    {
		if ( $post = $this->post() )
		{
			if ( $id = $this->block->add($post) )
			{
				if ( $post['operate']=='submit' )
				{
					return $this->success(t('保存成功'), u('block/block/index/'.$post['categoryid']) );
				}

				return $this->success(t('保存成功'), u('block/block/data/'.$id), 1);
			}

			return $this->error($this->block->error());
		}
		//全部分类
		$categories = $this->category->getall();

		// 获取当前分类
		$category 	= $this->category->get($categoryid);

		// 初始化数据
		$data = array(
			'type'			=> 'list',
			'categoryid' 	=> $categoryid,
			'fields'		=> array(
				array('show'=>1,'label'=>t('标题'),'type'=>'title','name'=>'title','minlength'=>1,'maxlength'=>50, 'required'=>'required'),
				array('show'=>1,'label'=>t('链接'),'type'=>'text','name'=>'url', 'required'=>'required'),
				array('show'=>1,'label'=>t('图片'),'type'=>'image','name'=>'image','image_resize'=>1,'image_width'=>'','image_height'=>'', 'watermark'=>0),
				array('show'=>1,'label'=>t('摘要'),'type'=>'textarea','name'=>'description','minlength'=>0,'maxlength'=>255),
				array('show'=>1,'label'=>t('日期'),'type'=>'datetime','name'=>'createtime'),
			)
		);

		$this->assign('title',t('新建区块'));
		$this->assign('categoryid',$categoryid);
		$this->assign('category',$category);
		$this->assign('categories',$categories);
		$this->assign('types',$this->block->types);
		$this->assign('data',$data);
		$this->display('block/admin_post.php');
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
			if ( $this->block->edit($post, $id) )
			{
				if ( $post['operate']=='submit' )
				{
					return $this->success(t('保存成功'), u('block/block/index/'.$post['categoryid']) );
				}

				return $this->success(t('保存成功'), u('block/block/data/'.$id), 1);
			}

			return $this->error($this->block->error());
		}

		//全部分类
		$categories = $this->category->getall();

		// 当前数据
		$data = $this->block->get($id);

		// 获取当前分类
		$category = $this->category->get($data['categoryid']);


		$this->assign('title',t('设置'));
		$this->assign('category',$category);
		$this->assign('categoryid',$category['id']);
		$this->assign('categories',$categories);
		$this->assign('types',$this->block->types);
		$this->assign('data',$data);
		$this->display('block/admin_post.php');
    }

    /**
     * 表单扩展部分
     * 
     * @return [type] [description]
     */
    public function action_postextend()
    {
		// 获取当前控件
		$type = $_POST['type'];

		// 获取模板
		$template = A('block.path').DS.'templates'.DS.'postextend'.DS.$type.'.php';

		if ( !file::exists($template) )
		{
			exit('');
		}

		$this->assign('data',$_POST);
		$this->display($template);
    }

 	/**
	 * 删除区块
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->block->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->block->error());
	}


	/**
	 * 维护内容
	 *
	 * @param string $id
     * @return void
	 */
    public function action_data($id)
    {
		if ( $post = $this->post() )
		{
			// TODO 保存的时候是否发布的逻辑需要处理
			if ( $this->block->savedata($post['data'], $id) )
			{
				return $post['operate']=='save' ? $this->success(t('保存成功')) : $this->success(t('保存成功'),u('block/block/index/'.$post['categoryid']));
			}

			return $this->error($this->block->error());
		}

		//全部分类
		$categories = $this->category->getall();

		// 当前数据
		$block = $this->block->get($id);

		// 获取当前分类
		$category = $this->category->get($block['categoryid']);

		$this->assign('title',t('数据管理').'-'.$block['name']);
		$this->assign('category',$category);
		$this->assign('categoryid',$category['id']);
		$this->assign('categories',$categories);
		$this->assign('types',$this->block->types);
		$this->assign('block',$block);
		$this->assign('data',$data);
		$this->display("block/block_data_{$block['type']}.php");
	}

	/**
	 * 选取数据
	 *
	 * @param string $id 区块编号
     * @return void
	 */
	public function action_selectdata($id)
	{
		// 当前区块
		$block 	= $this->block->get($id);

		$source = zotop::filter('block.datasource', array(
			'content' => array('text'=>t('内容'),'href'=>u('content/content/selectdata'))
		));

		$this->assign('block',$block);
		$this->assign('fields',$fields);
		$this->display();
	}	

	/**
	 * 发布
	 *
	 * @param string $id 区块编号
     * @return void
	 */
    public function action_publish($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->block->clearcache($id) )
			{
				$this->success(t('操作成功'));
			}

			return $this->error($this->block->error());
		}
	}


}
?>