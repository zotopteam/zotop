<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 地区 后台控制器
*
* @package		region
* @version		1.0
* @author
* @copyright
* @license
*/
class region_controller_admin extends admin_controller
{
	public $region;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->region = m('region.region');
	}

	/**
	 * index 动作
	 *
	 */
	public function action_index($parentid=0)
    {
		if ( $post = $this->post() )
		{
			if ( $this->region->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->region->error());
		}

		// 获取当前节点信息
		$region = $this->region->getbyid($parentid);

		//获取全部父节点信息
		if ( $region and $region['parentids'] )
		{
			$parents = $this->region->where('id','in',explode(',',$region['parentids']))->select();
		}
		else
		{
			$parents = array();
		}

		// 获取当前节点的子节点
		$data = $this->region->where('parentid',$parentid)->select();

		$this->assign('title',t('地区'));
		$this->assign('parentid',intval($parentid));
		$this->assign('data',$data);
		$this->assign('region',$region);
		$this->assign('parents',$parents);
		$this->display();
	}

	/**
	 * 添加动作
	 *
	 */
	public function action_add($parentid=0)
    {
		if ( $post = $this->post() )
		{
			if ( $this->region->add($post) )
			{
				return $this->success(t('添加成功'),U('region/admin/index/'.$post['parentid']));
			}

			return $this->error($this->region->error());
		}

		// 获取当前节点信息
		$data = array('parentid' => $parentid);

		$this->assign('title',t('地区'));
		$this->assign('parentid',intval($parentid));
		$this->assign('data',$data);
		$this->display('region/admin_post.php');
	}


	/**
	 * 编辑动作
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->region->edit($post,$id) )
			{
				return $this->success(t('编辑成功'),U('region/admin/index/'.$post['parentid']));
			}

			return $this->error($this->region->error());
		}

		// 获取当前节点信息
		$data = $this->region->getbyid($id);

		$this->assign('title',t('地区'));
		$this->assign('data',$data);
		$this->display('region/admin_post.php');
	}

	/**
	 * 删除动作
	 *
	 */
	public function action_delete($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->region->delete($id) )
			{
				return $this->success(t('删除成功'),request::referer());
			}

			return $this->error($this->region->error());
		}
	}

	/**
	 * 刷新区域的父编号串和层级
	 *
	 */
	public function action_refresh()
	{
		$data = $this->region->select();

		$tree = tree::instance($data);

		foreach( $data as $d )
		{
			$parentids = array_keys($tree->getParents($d['id']));

			$this->region->data('level',count($parentids))->data('parentids',implode(',',$parentids))->where('id',$d['id'])->update();
		}

		return $this->success(t('操作成功'));
	}

	/**
	 * 导出数据，将数据以数组的方式存储在region/data/default.php中
	 *
	 */
	private function export()
	{
		$data = $this->region->select();

		if ( $data )
		{
			file::put(ZOTOP_PATH_APPS.DS.'region'.DS.'data'.DS.'default.php', "<?php\nreturn ".var_export($data, true).";\n?>");
		}

		return true;
	}
}
?>