<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 地区 后台控制器
*
* @package		area
* @version		1.0
* @author
* @copyright
* @license
*/
class area_controller_admin extends admin_controller
{
	public $area;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->area = m('area.area');
	}

	/**
	 * index 动作
	 *
	 */
	public function action_index($parentid=0)
    {
		if ( $post = $this->post() )
		{
			if ( $this->area->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->area->error());
		}

		// 获取当前节点信息
		$area = $this->area->getbyid($parentid);

		//获取全部父节点信息
		if ( $area and $area['parentids'] )
		{
			$parents = $this->area->where('id','in',explode(',',$area['parentids']))->getAll();
		}
		else
		{
			$parents = array();
		}

		// 获取当前节点的子节点
		$data = $this->area->where('parentid',$parentid)->getAll();

		$this->assign('title',t('地区'));
		$this->assign('parentid',intval($parentid));
		$this->assign('data',$data);
		$this->assign('area',$area);
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
			if ( $this->area->add($post) )
			{
				return $this->success(t('添加成功'),U('area/admin/index/'.$post['parentid']));
			}

			return $this->error($this->area->error());
		}

		// 获取当前节点信息
		$data = array('parentid' => $parentid);

		$this->assign('title',t('地区'));
		$this->assign('parentid',intval($parentid));
		$this->assign('data',$data);
		$this->display('area/admin_post.php');
	}


	/**
	 * 编辑动作
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->area->edit($post,$id) )
			{
				return $this->success(t('编辑成功'),U('area/admin/index/'.$post['parentid']));
			}

			return $this->error($this->area->error());
		}

		// 获取当前节点信息
		$data = $this->area->getbyid($id);

		$this->assign('title',t('地区'));
		$this->assign('data',$data);
		$this->display('area/admin_post.php');
	}

	/**
	 * 删除动作
	 *
	 */
	public function action_delete($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->area->delete($id) )
			{
				return $this->success(t('删除成功'),request::referer());
			}

			return $this->error($this->area->error());
		}
	}

	/**
	 * 刷新区域的父编号串和层级
	 *
	 */
	public function action_refresh()
	{
		$data = $this->area->getAll();

		$tree = tree::instance($data);

		foreach( $data as $d )
		{
			$parentids = array_keys($tree->getParents($d['id']));

			$this->area->set('level',count($parentids))->set('parentids',implode(',',$parentids))->where('id',$d['id'])->update();
		}

		return $this->success(t('操作成功'));
	}

	/**
	 * 导出数据，将数据以数组的方式存储在area/data/default.php中
	 *
	 */
	private function export()
	{
		$data = $this->area->getAll();

		if ( $data )
		{
			file::put(ZOTOP_PATH_APPS.DS.'area'.DS.'data'.DS.'default.php', "<?php\nreturn ".var_export($data, true).";\n?>");
		}

		return true;
	}
}
?>