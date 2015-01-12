<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* [name] 后台控制器
*
* @package		[id]
* @version		[version]
* @author		[author]
* @copyright	[author]
* @license		[homepage]
*/
class [id]_controller_admin extends admin_controller
{
	
	protected $model;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->model = m('[id].area');
	}	

	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {

		$this->assign('title',t('[name]'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加动作
	 * 
	 * @return mixed
	 */
	public function action_add()
    {
    	if ( $post = $this->post() )
    	{
    		if ( $this->admin->add($post) )
    		{
    			return $this->success(t('操作成功'),U('[id]/admin/index'));
    		}

    		return $this->error($this->admin->error());
    	}

    	//默认数据
    	$data = array();

    	//模板赋值并显示
		$this->assign('title',t('添加'));
		$this->assign('data',$data);
		$this->display();
	}	
}
?>