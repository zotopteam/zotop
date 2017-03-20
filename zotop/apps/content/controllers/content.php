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

	/*
	public function action_test()
	{
		$this->content->db()->table('content')->clear();

		$listorder = ZOTOP_TIME-1000;

		for ($i=0; $i < 1000; $i++)
		{
			$this->content->insert(array (
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
	*/


	/**
	 * 内容列表
	 * 
	 * @return mixed
	 */
    public function action_index()
    {
    	$category = array();

		if ( $categoryid = zotop::get('categoryid',0) and $category = $this->category->get($categoryid))
		{
			$this->content->where('categoryid','in',$category['childids']);
		}

		// 状态
		if ( $status = zotop::get('status','publish') )
		{
			$this->content->where('status','=',$status);
		}
		
		// 用户
		if ( $userid = zotop::get('userid',0) ) 
		{
			$this->content->where('userid','=',$userid);
		}

		// 获取数据集
		$dataset = $this->content->orderby('stick','desc')->orderby('listorder','desc')->paginate();

		// 获取全部模型，并筛选出当前栏目可用的模型
		$models = $this->model->cache();

		foreach( $models as $i=>$m )
		{
			if ( $m['disabled'] OR ($categoryid and $category['settings']['models'][$i]['enabled'] == 0 ))
			{
				unset($models[$i]);
			}
		}
		
		$this->assign('title',$category['title']);
		$this->assign('categoryid',$categoryid);
		$this->assign('status',$status);
		$this->assign('category',$category);
		$this->assign('models',$models);
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

		$dataset = $this->content->where(array(array('title','like',$keywords),'or',array('keywords','like',$keywords)))->orderby('listorder','desc')->paginate();

		$this->assign('title', t('搜索“$1”',$keywords));
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
					$result = $this->content->where('id', 'in', $post['id'])->data('status', $operation)->update();
					break;
				case 'move':
					if ( empty($post['categoryid']) )
					{
						return $this->error(t('禁止移动到根目录'));
					}
					$result = $this->content->where('id', 'in', $post['id'])->data('categoryid', $post['categoryid'])->update();
					break;
				case 'weight':
					$result = $this->content->where('id', 'in', $post['id'])->data('weight', $post['weight'])->update();
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

			if ( empty($id) or empty($listorder) ) return $this->error(t('禁止访问'));

			try
			{
				// $categoryid 为排序所在的栏目，不是排序数据的栏目编号，获取下级全部子栏目编号，用于父栏目也可以对所有子栏目的数据进行排序
				$categoryids = m('content.category.get',$categoryid,'childids');

				// 将当前列表 $listorder 之前的数据的 listorder 全部加 1， 为拖动的数据保留出位置
				$this->content
					->where('categoryid','in',$categoryids)
					->where('listorder','>=',$listorder)
					->data('listorder',array('listorder','+',1))
					->update();

				// 更新拖动的数据为当前 $listorder
				$this->content
					->where('id',$id)
					->data('listorder',$listorder)
					->data('stick',$stick)
					->update();

				return $this->success(t('操作成功'));
			}
			catch (Exception $e)
			{
				return $this->error($e->getMessage());
			}
		}

    	return $this->error(t('禁止访问'));
    }

	/**
	 * 添加内容
	 *
	 * @param  int $categoryid 栏目编号
	 * @param  string $modelid 模型编号
	 * @return mixed
	 */
	public function action_add()
	{
		$categoryid = zotop::get('categoryid',0);
		$modelid  = zotop::get('modelid','category');

		if ( $post = $this->post() )
		{
			if ( $id = $this->content->save($post) )
			{
				if (  $post['status'] == 'draft' )
				{
					return $this->success(t('保存成功'),$id);
				}

				return $this->success(t('保存成功'), request::referer());
			}

			return $this->error($this->content->error());
		}

		// 默认数据
		$data = array();
		$data['modelid']    = $modelid;
		$data['categoryid']   = $categoryid;
		$data['createtime'] = ZOTOP_TIME;

		$this->assign('title',t('添加'));
		$this->assign('data',$data);
		$this->display('content/content_post.php');
	}


	/**
	 * 编辑内容
	 *
	 * @param  int $id 内容编号
	 * @return mixed
	 */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->content->save($post) )
			{
				return $this->success(t('保存成功'), request::referer());
			}

			return $this->error($this->content->error());
		}

		// 获取当前数据
		$data = $this->content->get($id);

		$this->assign('title',t('编辑'));
		$this->assign('data',$data);
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

			if ( $this->content->where('id',$id)->data($key, $newvalue)->update() )
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
		if ( $this->content->where('id',$id)->data('stick',$stick)->update() )
		{
			return $this->success(t('操作成功'),request::referer());
		}

		return $this->error($this->content->error());
	}

	/**
	 * 设置内容状态
	 *
	 * @param  int $id 编号
	 * @return json
	 */
	public function action_status($id, $status)
	{
		if ( $this->content->where('id',$id)->data('status',$status)->update() )
		{
			return $this->success(t('操作成功'), request::referer());
		}

		return $this->error($this->content->error());
	}

 	/**
	 * 删除
	 *
	 */
	public function action_delete($id)
	{	
		if ( $data = $this->content->getbyid($id) )
		{

			if( $data['status'] == 'delete' )
			{
				$this->content->delete($id);

				return $this->success(t('彻底删除成功'),request::referer());
			}

			if ( $data['status'] == 'trash' )
			{
				$this->content->where('id',$id)->data('status', 'delete')->update();

				return $this->success(t('删除成功'),request::referer());
			}			

			$this->content->where('id',$id)->data('status', 'trash')->update();

			return $this->success(t('已经放入回收站'),request::referer());
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
	 * 选取
	 *
	 * @param int $categoryid
     * @return void
	 */
    public function action_select_block($blockid)
    {
		if ( $post = $this->post() )
		{
			if ( empty($post['id']) )
			{
				return $this->error(t('请选择要操作的项'));
			}

			$return   = array();
			$datalist = $this->content->where('id','in',$post['id'])->full_url(false)->select();

			foreach($datalist as $id=>$data)
			{
				$return[$id]['app']     = 'content';
				$return[$id]['dataid']  = $data['dataid'];				
				$return[$id]['blockid'] = $blockid;
				$return[$id]['data']    = 	array(
					'title'                 => $data['title'],
					'style'                 => $data['style'],
					'url'                   => $data['url'],
					'image'                 => $data['image'],
					'description'           => $data['summary'],
					'time'                  => $data['createtime']
				);
			}

			exit(json_encode($return));
		}

		// 应用数据
		$block = m('block.block')->get($blockid);


		if ( $keywords = $_REQUEST['keywords'] )
		{
			$this->content->where(array(array('title','like',$keywords),'or',array('keywords','like',$keywords)));
		}

		// 获取数据集
		$dataset = $this->content->where('status','=','publish')->orderby('stick','desc')->orderby('listorder','desc')->paginate();		

		$this->assign('title',t('选取内容'));
		$this->assign('block',$block);
		$this->assign('keywords',$keywords);
		$this->assign($dataset);
		$this->display('content/content_select_block.php');
    }			
}
?>