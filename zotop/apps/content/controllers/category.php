<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 栏目管理
 *
 * @package		content
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_controller_category extends admin_controller
{
	protected $model;
	protected $category;


	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->model = m('content.model');
		$this->category = m('content.category');
	}

 	/**
	 * 栏目管理
	 *
	 */
	public function action_index($parentid=0)
	{
		if ( $post = $this->post() )
		{
			if ( $this->category->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->category->error());
		}

		// 获取当前的子栏目
		$data = $this->category->getChild($parentid);

		foreach( $data as &$d )
		{
			$d['datacount'] = $this->category->datacount($d['childids']);
		}

		// 父栏目数据
		$parents = $this->category->getParents($parentid);

		$this->assign('title',t('栏目管理'));
		$this->assign('parentid',$parentid);
		$this->assign('parents',$parents);
		$this->assign('current',$current);
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加栏目
	 *
	 * @return mixed
	 */
    public function action_add($parentid=0)
    {
		if ( $post = $this->post() )
		{
			if ( $id = $this->category->add($post) )
			{
				// 关联附件
				m('system.attachment')->setRelated("content-category-{$id}");

				return $this->success(t('操作成功'),u("content/category/index/{$post['parentid']}"));
			}

			return $this->error($this->category->error());
		}

		// 读取父栏目用于父栏目继承数据
		if ( $parentid ) $parent = $this->category->get($parentid);

		if ( is_array($parent) )
		{
			$data['parentid'] = $parentid;
			$data['settings'] = $parent['settings'];
		}
		else
		{
			$data['parentid'] = 0;
			$data['settings'] = array('template_index' => 'content/index.php','template_list' => 'content/list.php');
		}

		// 获取模型
		$models = $this->model->orderby('listorder','asc')->getAll();

		foreach( $models as $i=>&$m )
		{
			if ( is_array($parent) )
			{
				$m['enabled']	= $parent['settings']['models'][$i]['enabled'];
				$m['template']	= $parent['settings']['models'][$i]['template'];
			}
			else
			{
				$m['enabled']	= true;
			}
		}

		// 获取全部父栏目
		$parents = $this->category->getParents($parentid);


		$this->assign('title',t('栏目管理'));
		$this->assign('parent',$parent);
		$this->assign('data',$data);
		$this->assign('models',$models);
		$this->assign('parents',$parents);
		$this->display("content/category_post.php");
    }

	/**
	 * 编辑栏目
	 *
	 * @return mixed
	 */
    public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->category->edit($post, $id) )
			{
				return $this->success(t('操作成功'),u("content/category/index/{$post['parentid']}"));
			}

			return $this->error($this->category->error());
		}

		// 获取栏目数据
		$data = $this->category->get($id);
		$data['dataid'] = "content-category-{$id}";

		// 获取模型
		$models = $this->model->orderby('listorder','asc')->getAll();

		foreach( $models as $i=>&$m )
		{
			$m['enabled']		= $data['settings']['models'][$i]['enabled'];
			$m['template']		= $data['settings']['models'][$i]['template'] ? $data['settings']['models'][$i]['template'] : $m['template'];
		}


		// 父栏目数据
		$parents = $this->category->getParents($id);


		$this->assign('title',t('栏目管理'));
		$this->assign('data',$data);
		$this->assign('models',$models);
		$this->assign('parents',$parents);
		$this->display('content/category_post.php');
    }

	/**
	 * 移动栏目
	 *
	 * @return mixed
	 */
    public function action_move($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->category->move($id, $post['parentid']) )
			{
				return $this->success(t('操作成功'),u("content/category/index/{$post['parentid']}"));
			}

			return $this->error($this->category->error());
		}

		// 获取栏目数据
		$category = $this->category->get($id);

		$this->assign('title',t('移动栏目'));
		$this->assign('category',$category);
		$this->assign('id',$id);
		$this->display();
    }

	/**
	 * 选择栏目
	 *
	 * @return mixed
	 */
    public function action_select($id=0)
    {
		// 获取栏目数据
		$category = $this->category->get($id);

		$this->assign('title',t('选择栏目'));
		$this->assign('category',$category);
		$this->assign('id',$id);
		$this->display();
    }

    /**
     * 检查栏目名称是否被占用
     *
     * @return bool
     */
	public function action_check($key,$ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->category->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->category->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}

    /**
     * 设置状态，禁用或者启用
     *
	 * @param string $id 应用标识(ID)
     * @return void
     */
	public function action_status($id)
	{
		if ( $this->category->status($id) )
		{
			return $this->success(t('操作成功'),request::referer());
		}
		return $this->error($this->category->error());
	}

    /**
     * 修复栏目并更新缓存
     *
     * @return void
     */
	public function action_repair()
	{
		if ( $this->category->repair() )
		{
			return $this->success(t('操作成功'),request::referer());
		}
		return $this->error($this->category->error());
	}

 	/**
	 * 删除栏目
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->category->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->category->error());
	}
}
?>