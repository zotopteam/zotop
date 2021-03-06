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
		$categories = $this->category->getall();

		// 如果没有任何分类，则转向分类管理
		if ( empty($categories) ) 
		{
			$this->redirect(U('block/category'));
		}

		// 如果没有传入任何分类编号，则获取第一个
		if ( empty($categoryid) )
		{
			$categoryid = reset(array_keys($categories));
		}

		// 搜索关键词
		if ( $keywords = $_REQUEST['keywords'] )
		{
			$this->block->where('name','like', $keywords);
		}

		// 读取当前分类下面的模型
		$data = $this->block->where('categoryid',$categoryid)->select();

		foreach ($data as &$d)
		{
			$d['tag']          = '{block id="'.$d['id'].'"}';
			$d['tag_advanced'] = '{block id="'.$d['id'].'" name="'.$d['name'].'" type="'.$d['type'].'"}';
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
					return $this->success(t('保存成功'), u('block/admin/index/'.$post['categoryid']) );
				}

				return $this->success(t('保存成功'), u('block/admin/data/'.$id), 1);
			}

			return $this->error($this->block->error());
		}
		//全部分类
		$categories = $this->category->select();

		// 获取当前分类
		$category 	= $this->category->get($categoryid);

		// 初始化数据
		$data = array(
			'type'			=> 'list',
			'categoryid' 	=> $categoryid,
			'fields'		=> $this->block->fieldlist('title,url'),
			'template'		=> 'block/list.php',
		);

		$this->assign('title',t('新建区块'));
		$this->assign('categoryid',$categoryid);
		$this->assign('category',$category);
		$this->assign('categories',$categories);
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
					return $this->success(t('保存成功'), u('block/admin/index/'.$post['categoryid']) );
				}

				return $this->success(t('保存成功'), u('block/admin/data/'.$id), 1);
			}

			return $this->error($this->block->error());
		}

		//全部分类
		$categories = $this->category->select();
		
		// 当前数据
		$data       = $this->block->get($id);
		
		// 获取当前分类
		$category   = $this->category->get($data['categoryid']);


		$this->assign('title',t('区块设置'));
		$this->assign('category',$category);
		$this->assign('categoryid',$category['id']);
		$this->assign('categories',$categories);
		$this->assign('data',$data);
		$this->display('block/admin_post.php');
    }

    /**
     * 检查编号是否被占用
     *
     * @return bool
     */
	public function action_check($key)
	{
		if ( $ignore = $_GET['ignore'] )
		{
			$count = $this->block->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}
		else
		{
			$count = $this->block->where($key,$_GET[$key])->count();

		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}    

    /**
     * 表单扩展部分
     * 
     * @return [type] [description]
     */
    public function action_postextend($action=null)
    {
		// 获取当前控件
		$type = $_POST['type'];

		//dd($_POST);

		// 获取模板
		$template = A('block.path').DS.'templates'.DS.'postextend'.DS.$type.'.php';

		if ( !file::exists($template) )
		{
			exit('');
		}

		if ( $action == 'add' )
		{
			$key = 'custom_'.ZOTOP_TIME;

			$_POST['fields'][$key] = array(
				'show'=>1,'label'=>'','type'=>'text','name'=>'','required'=>'required'
			);
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
				return $post['operate']=='save' ? $this->success(t('保存成功')) : $this->success(t('保存成功'),u('block/admin/index/'.$post['categoryid']));
			}

			return $this->error($this->block->error());
		}

		// 当前数据
		$block = $this->block->get($id);

		// 获取当前分类		
		$category = $block['categoryid'] == 0 ? array('id'=>0,'name'=>t('全局区块')) : $this->category->get($block['categoryid']);

		$this->assign('title',$block['name']);
		$this->assign('category',$category);
		$this->assign('categoryid',$category['id']);
		$this->assign('block',$block);
		$this->assign('data',$data);
		$this->display("block/admin_data_{$block['type']}.php");
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