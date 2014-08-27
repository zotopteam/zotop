<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 投稿
 *
 * @package		content
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_controller_contribute extends member_controller
{
	protected $content;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->content = m('content.content');
		$this->category = m('content.category');
		$this->model = m('content.model');
	}


	/**
	 * 列表
	 *
	 */
    public function action_index($categoryid=0)
    {
		// 获取模型
		$models = $this->model->cache();

		// 获取栏目数据
		$categorys = $this->category->getAll();

		// 状态
		$statuses = $this->content->statuses;

		// 搜索: 标题和关键词
		if ( $keywords = $_REQUEST['keywords'] )
		{
			$dataset = $this->content->where(array(array('title','like',$keywords),'or',array('keywords','like',$keywords)))->where('userid',$this->userid)->orderby('createtime','desc')->getPage();
		}
		else
		{
			// 栏目
			if ( $categoryid )
			{
				// 获取栏目信息
				$category = $this->category->get($categoryid);

				// 获取包含子栏目的全部数据
				$this->content->where('categoryid','in',$category['childids']);
			}

			// 获取数据集
			$dataset = $this->content->where('userid',$this->userid)->orderby('createtime','desc')->getPage();
		}

			// 允许发布的模型
		$postmodels = array();

		foreach( $models as $i=>$m )
		{
			if ( $m['disabled'] ) continue;
			if ( $category['settings']['models'][$i]['enabled'] == 0 ) continue;

			$postmodels[$i] = $m;
		}	

		$this->assign('title',t('投稿'));
		$this->assign('statuses',$statuses);
		$this->assign('models',$models);
		$this->assign('postmodels',$postmodels);
		$this->assign('categorys',$categorys);
		$this->assign('category',$category);
		$this->assign('categoryid',$categoryid);
		$this->assign('keywords',$keywords);
		$this->assign($dataset);
		$this->display();
    }

	/**
	 * 添加
	 *
	 */
	public function action_add($categoryid, $modelid)
	{
		if ( !$this->allow('add', $categoryid, $modelid) ) return $this->error(t('权限不足'));

		if ( $post = $this->post() )
		{
			if ( $id = $this->content->save($post) )
			{
				if (  $post['status'] == 'draft' )
				{
					return $this->success(t('保存成功'),$id);
				}

				return $this->success(t('保存成功'),u('content/contribute/index/'.$categoryid));
			}

			return $this->error($this->content->error());
		}

		// 栏目数据
		$category = $this->category->get($categoryid);

		// 模型数据
		$model = $this->model->get($modelid);

		// 父栏目数据
		$parents = $this->category->getParents($categoryid);

		// 默认数据
		$data['categoryid'] = $categoryid;
		$data['app'] = $model['app'];
		$data['modelid'] = $modelid;
		$data['createtime'] = ZOTOP_TIME;

		$this->assign('title',$category['name']);
		$this->assign('category',$category);
		$this->assign('model',$model);
		$this->assign('categoryid',$categoryid);
		$this->assign('modelid',$modelid);
		$this->assign('parents',$parents);
		$this->assign('data',$data);
		$this->assign('statuses',$this->content->statuses);
		$this->display('content/contribute_post.php');
	}

 	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
	{
		if ( !$this->allow('edit', $id) ) return $this->error(t('权限不足'));

		if ( $post = $this->post() )
		{
			if ( $this->content->save($post) )
			{
				return $this->success(t('保存成功'), u('content/contribute/index/'.$post['categoryid']));
			}

			return $this->error($this->content->error());
		}

		$data = $this->content->get($id);

		// 栏目数据
		$category = $this->category->get($data['categoryid']);

		// 模型数据
		$model = $this->model->get($data['modelid']);

		// 父栏目数据
		$parents = $this->category->getParents($data['categoryid']);

		$this->assign('title',$category['name']);
		$this->assign('category',$category);
		$this->assign('model',$model);
		$this->assign('categoryid',$data['categoryid']);
		$this->assign('modelid',$data['modelid']);
		$this->assign('parents',$parents);
		$this->assign('data',$data);
		$this->assign('statuses',$this->content->statuses);
		$this->display('content/contribute_post.php');
	}

 	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
		if ( !$this->allow('delete', $id) ) return $this->error(t('权限不足'));

		if ( $this->content->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->content->error());
	}

    /**
     * 检查标题或者其它字段是否被占用
     *
     * @return bool
     */
	public function action_check($key,$ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->content->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->content->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}

    /**
     * 检查用户是否能操作
     *
     * @return bool
     */
	public function allow($action, $p1, $p2='', $p3='')
	{
		if ( in_array($action, array('edit','delete')) )
		{
			return $this->content->where('id', $p1)->where('userid', $this->userid)->count();
		}

		return true;
	}
}
?>