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

	public function action_test()
	{
		$this->content->db()->table('content')->clear();

		$listorder = ZOTOP_TIME-1000;

		for ($i=0; $i < 1000; $i++)
		{ 
			$this->content->insert(array (
			  'parentid' => '0',
			  'categoryid' => '1',
			  'modelid' => 'url',
			  'title' => '测试标题'.$i,
			  'url' => 'http://www.163.com/',
			  'createtime' => '1421161200',
			  'updatetime' => '1421845252',
			  'weight' => '0',
			  'listorder' => $listorder,
			  'stick' => '0',
			  'userid' => '1',
			  'status' => 'publish',
			));

			$listorder = $listorder + 1;
		}

		return $this->success('操作成功');

	}


	/**
	 * 列表
	 *
	 */
    public function action_index($categoryid=0, $status='')
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
		if ( $status )
		{
			$this->content->where('status','=',$status);
		} 

		// 获取数据集
		$dataset = $this->content->orderby('stick','desc')->orderby('listorder','desc')->getPage();


		// 允许发布的模型
		$postmodels = array();

		foreach( m('content.model.cache') as $i=>$m )
		{
			if ( $m['disabled'] ) continue;

			if ( $category['settings']['models'][$i]['enabled'] == 0 ) continue;

			$postmodels[$i] = $m;
		}


		$this->assign('title',$category['name']);		
		$this->assign('categoryid',$categoryid);
		$this->assign('status',$status);	
		$this->assign('category',$category);
		$this->assign('postmodels',$postmodels);			
		$this->assign($dataset);
		$this->display();
    }

    /**
     * 内容搜索
     * 
     * @return mixed
     */
    public function action_search()
    {
		$keywords = $_REQUEST['keywords'];

		if ( empty($keywords) )
		{
			return $this->error(t('请输入关键词'));
		}

		$dataset = $this->content->where(array(array('title','like',$keywords),'or',array('keywords','like',$keywords)))->orderby('listorder','desc')->getPage();

		$this->assign('keywords',$keywords);
		$this->assign('category',$category);
		$this->assign($dataset);
		$this->display('content/content_index.php');		  	
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
     * 拖动排序
     * 
     * @return mixed 操作结果
     */
    public function action_listorder()
    {
		if ( $post = $this->post() )
		{
			@extract($post);

			if ( empty($id) or empty($listorder) or empty($categoryid) ) return $this->error(t('禁止访问'));

			try
			{
				// $categoryid 为排序所在的栏目，不是排序数据的栏目编号，获取下级全部子栏目编号，用于父栏目也可以对所有子栏目的数据进行排序
				$categoryids = m('content.category.get',$categoryid,'childids');

				// 将当前列表 $listorder 之前的数据的 listorder 全部加 1， 为拖动的数据保留出位置
				$this->content->where('categoryid','in',$categoryids)->where('parentid',$parentid)->where('listorder','>=',$listorder)->set('listorder',array('listorder','+',1))->update();
				
				// 更新拖动的数据为当前 $listorder
				$this->content->where('id',$id)->set('listorder',$listorder)->set('stick',$stick)->update();
				
				return $this->success(t('操作成功'),request::referer());
			}
			catch (Exception $e)
			{
				return $this->error($e->getMessage());
			}			
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


		// 默认数据
		$data = array();
		$data['categoryid'] = $categoryid;
		$data['modelid'] 	= $modelid;
		$data['createtime'] = ZOTOP_TIME;

		$this->assign('title',$category['name']);
		$this->assign('category',$category);
		$this->assign('model',$model);
		$this->assign('categoryid',$categoryid);
		$this->assign('modelid',$modelid);
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

		$data = $this->content->get($id);

		// 获取“推荐到区块”的区块编号
		$data['blockids'] = arr::column(m('block.datalist')->select('blockid')->where('dataid',$data['dataid'])->getall(), 'blockid');

		// 栏目数据
		$category 	= $this->category->get($data['categoryid']);

		// 模型数据
		$model 		= $this->model->get($data['modelid']);

		$this->assign('title',$category['name']);
		$this->assign('category',$category);
		$this->assign('model',$model);
		$this->assign('categoryid',$data['categoryid']);
		$this->assign('modelid',$data['modelid']);
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
	 * 根据条目置顶状态设置置顶和取消置顶
	 * 
	 * @param  int $id 编号
	 * @return json
	 */
	public function action_stick($id, $stick)
	{
		if ( $this->content->where('id',$id)->set('stick',$stick)->update() )
		{
			return $this->success(t('操作成功'),request::referer());
		}

		return $this->error($this->content->error());		
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