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
class content_controller_content extends admin_controller
{
	protected $content;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->content	= m('content.content');
		$this->category = m('content.category');
		$this->model	= m('content.model');
	}


	/**
	 * 列表
	 *
	 */
    public function action_index($categoryid=0, $status='publish')
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
			$dataset = $this->content->where(array(
				array('title','like',$keywords),
				'or',
				array('keywords','like',$keywords)
			))->orderby('createtime','desc')->getPage();
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

			// 状态
			if ( $status ) $this->content->where('status','=',$status);

			// 获取数据集
			$dataset = $this->content->orderby('weight','desc')->orderby('createtime','desc')->getPage();

			// 获取当前状态的数据条数
			foreach($statuses as $s=>$t)
			{
				if ( $s == 'publish' ) continue;

				if ( $categoryid )
				{
					$statuscount[$s] = $this->content->where('categoryid','in',$category['childids'])->where('status','=',$s)->count();
				}
				else
				{
					$statuscount[$s] = $this->content->where('status','=',$s)->count();
				}
			}
		}

		// 允许发布的模型
		$postmodels = array();

		foreach( $models as $i=>$m )
		{
			if ( $m['disabled'] ) continue;

			if ( $category['settings']['models'][$i]['enabled'] == 0 ) continue;

			$postmodels[$i] = $m;
		}


		$this->assign('title',A('content.name'));
		$this->assign('statuses',$statuses);
		$this->assign('statuscount',$statuscount);
		$this->assign('status',$status);
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
	 * 多选操作
	 *
	 * @param $operation 操作
	 * @return mixed
	 */
    public function action_operate($operation)
    {

		if ( $post = $this->post() )
		{
			if ( empty($post['id']) )
			{
				return $this->error(t('请选择要操作的项'));
			}

			switch($operation)
			{
				case 'delete' :
					$result = $this->content->delete($post['id']);
					break;
				case 'publish' :
				case 'pending' :
				case 'reject' :
				case 'draft' :
				case 'trash' :
					$result = $this->content->where('id', 'in', $post['id'])->set('status', $operation)->update();
					break;
				case 'move':
					if ( empty($post['categoryid']) )
					{
						return $this->error(t('禁止移动到根目录'));
					}
					$result = $this->content->where('id', 'in', $post['id'])->set('categoryid', $post['categoryid'])->update();
					break;
				case 'weight':
					$result = $this->content->where('id', 'in', $post['id'])->set('weight', $post['weight'])->update();
					break;
				default :
					break;
			}

			return $result ? $this->success(t('操作成功'),request::referer()) : $this->error(t('操作失败'));
		}

		return $this->error(t('禁止访问'));
    }

	/**
	 * 添加
	 *
	 */
	public function action_add($categoryid, $modelid)
	{
		if ( $post = $this->post() )
		{
			if ( $id = $this->content->save($post) )
			{
				if (  $post['status'] == 'draft' )
				{
					return $this->success(t('保存成功'),$id);
				}

				return $this->success(t('保存成功'),u('content/content/index/'.$categoryid));
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
		$this->display('content/content_post.php');
	}

 	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->content->save($post) )
			{
				return $this->success(t('保存成功'), u('content/content/index/'.$post['categoryid']));
			}

			return $this->error($this->content->error());
		}

		//debug::dump(m('content.tag')->where('name','中国电信')->getField('id'));exit;

		$data = $this->content->get($id);

		$data['blockids'] = arr::column(m('block.datalist')->select('blockid')->where('app', 'content')->where('dataid',"content-{$id}")->getall(), 'blockid');

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
		$this->display('content/content_post.php');
	}

 	/**
	 * 设置单项数据
	 *
	 */
	public function action_set($key, $id)
	{
		if ( $post = $this->post() )
		{
			$newvalue = $post['newvalue'];

			if ( $key == 'weight' and !preg_match('/^\d{1,2}$/',$newvalue) )
			{
				return $this->error(t('权重必须是0-99之间的数字'));
			}

			if ( $this->content->where('id',$id)->set($key, $newvalue)->update() )
			{
				return $this->success(t('操作成功'),request::referer());
			}

			return $this->error($this->content->error());
		}
	}

 	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{
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
}
?>