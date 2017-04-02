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
	protected $datalist;	

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
				return $this->success(t('操作成功'),u('block/admin/data/'.$blockid));
			}

			return $this->error($this->datalist->error());
		}

		// 应用数据
		$block = $this->block->get($blockid);

		// 初始化数据
		$data  = array(
			'blockid' => $block['id'],
			'dataid'  => $block['dataid']
		);

		$this->assign('title',t('添加数据'));
		$this->assign('data',$data);
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

		// 获取当前数据
		$data = $this->datalist->get($id);

		// 应用数据
		$block = $this->block->get($data['blockid']);

		$this->assign('title',t('编辑数据'));
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

	/**
	 * 根据条目置顶状态设置置顶和取消置顶
	 * 
	 * @param  int $id 编号
	 * @return json
	 */
	public function action_stick($id)
	{
		if ( $this->datalist->stick($id) )
		{
			return $this->success(t('操作成功'),request::referer());
		}

		return $this->error($this->datalist->error());		
	}

	/**
	 * 历史记录数据源
	 *
	 */
	public function action_history($blockid)
	{
		// 应用数据
		$block = $this->block->get($blockid);		

		// 历史数据列表
		if ( $keywords = $_REQUEST['keywords'] )
		{
			$this->datalist->where('data','like',$keywords);
		}		

		$dataset = $this->datalist->where('blockid',$blockid)->where('status','history')->orderby('id','desc')->paginate();

		//格式化数据
		foreach ($dataset['data'] as &$data)
		{
			$data['data'] = (array)unserialize($data['data']);
			$data['data']['url']	= $data['data']['url'] ? U($data['data']['url']) : '';
		}
		
		$this->assign('title',t('历史记录'));		
		$this->assign('block',$block);
		$this->assign('categoryid',$block['categoryid']);
		$this->assign('keywords',$keywords);
		$this->assign($dataset);
		$this->display('block/datalist_history.php');
	}


	/**
	 * 历史记录重新推荐
	 * 
	 * @param  int $id 编号
	 * @return json
	 */
	public function action_back($id)
	{
		if ( $this->datalist->back($id) )
		{
			return $this->success(t('操作成功'),request::referer());
		}

		return $this->error($this->datalist->error());		
	}

	/**
	 * 选取
	 *
	 * @param int $categoryid
     * @return void
	 */
    public function action_insert($blockid)
    {
		if ( $post = $this->post() )
		{
			// 应用数据
			$block = $this->block->get($blockid);

			// 插入的多条数据
			foreach ((array)$post['datalist'] as $data)
			{
				$data['updatetime'] = ZOTOP_TIME ;
				$data['status']     = $data['status'] ? $data['status'] : 'publish';
				$data['userid']     = zotop::user('id');
				$data['listorder']  = $this->datalist->where('blockid',$data['blockid'])->max('listorder') + 1;

				$this->datalist->insert($data);
			}

			$this->datalist->updatedata($blockid);

			if ( $this->datalist->error() )
			{
				return $this->error($this->datalist->error());
			}

			return $this->success(t('插入成功'));
		}
    }	
}
?>