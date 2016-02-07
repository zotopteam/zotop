<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* [description]
*
* @package		[app.id]
* @version		[app.version]
* @author		[app.author]
* @copyright	[app.author]
* @license		[app.homepage]
*/
class [app.id]_controller_[name] extends admin_controller
{
	
	protected $[name];

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->[name] = m('[app.id].[name]');
	}	

	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {
    	$data = $this->[name]->select();

		$this->assign('title',t('[title]'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 添加
	 * 
	 * @return mixed
	 */
	public function action_add()
    {
    	if ( $post = $this->post() )
    	{
    		if ( $this->[name]->add($post) )
    		{
    			return $this->success(t('操作成功'),U('[app.id]/[name]/index'));
    		}

    		return $this->error($this->[name]->error());
    	}

    	//默认数据
    	$data = array();

    	//模板赋值并显示
		$this->assign('title',t('[title]添加'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 编辑
	 * 
	 * @param  int|string $id 编号
	 * @return mixed
	 */
	public function action_edit($id)
    {
    	if ( $post = $this->post() )
    	{
    		if ( $this->[name]->edit($post,$id) )
    		{
    			return $this->success(t('操作成功'),U('[app.id]/[name]/index'));
    		}

    		return $this->error($this->[name]->error());
    	}

    	//默认数据
    	$data = array();

    	//模板赋值并显示
		$this->assign('title',t('[title]编辑'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 删除
	 * 
	 * @param  int|string $id 编号
	 * @return json
	 */
	public function delete($id)
	{
		if ( $this->[name]->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}

		return $this->error($this->[name]->error());		
	}			
}
?>