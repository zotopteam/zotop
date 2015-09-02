<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 数据导入 后台控制器
*
* @package		dbimport
* @version		1.0
* @author
* @copyright
* @license
*/
class dbimport_controller_admin extends admin_controller
{
	public $dbimport;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->dbimport = m('dbimport.dbimport');
	}

	/**
	 * 规则列表
	 *
	 */
	public function action_index()
    {
		$data = $this->dbimport->select();

		foreach ($data as &$d)
		{
			$d['source']['count'] = $this->dbimport->source_count($d);
		}

		$this->assign('title',t('数据导入'));
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 新建规则
	 *
	 */
	public function action_add()
    {
    	if ( $post = $this->post() )
    	{

  			if ( $this->dbimport->add($post) )
			{
				return $this->success(t('保存成功'),u('dbimport/admin'));
			}

			return $this->error($this->dbimport->error());
    	}

		$data = array();
		$data['source']['driver'] 	= 'mysql';
		$data['source']['hostname'] = 'localhost';
		$data['source']['hostport'] = '3306';
		$data['source']['username'] = 'root';
		$data['source']['charset'] 	= 'utf8';


		$this->assign('title',t('新建规则'));
		$this->assign('data',$data);
		$this->display('dbimport/admin_post.php');
	}

	/**
	 * 编辑规则
	 *
	 */
	public function action_edit($id)
    {
    	if ( $post = $this->post() )
    	{

  			if ( $this->dbimport->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('dbimport/admin'));
			}

			return $this->error($this->dbimport->error());
    	}

		$data = $this->dbimport->get($id);

		$this->assign('title',t('编辑规则'));
		$this->assign('data',$data);
		$this->display('dbimport/admin_post.php');
	}

	/**
	 * 连接接数据库，获取对应关系
	 *
	 * @return json
	 */
	public function action_post_maps()
	{
		if ( $post = $this->post() )
		{
			if ( empty($post['source']['database']) ) exit('<div class="error">'.t('数据库名称不能为空').'</div>');

			try
			{
				zotop::db($post['source'])->connect();
			}
			catch (Exception $e)
			{
				exit('<div class="error">'.$e->getMessage().'</div>');
			}

			// 获取目标数据库的数据表及字段
			$tables = $this->tables('default');

			if ( !in_array($post['table'], array_keys($tables)) )
			{
				$post['table'] = reset(array_keys($tables));
			}

			$fields = $this->fields('default', $post['table']);


			// 获取源数据库的数据表及字段
			$source_tables = $this->tables($post['source']);

			if ( !in_array($post['source']['table'], array_keys($source_tables)) )
			{
				$post['source']['table'] = reset(array_keys($source_tables));
			}

			$source_fields = $this->fields($post['source'], $post['source']['table']);

			$this->assign('tables',$tables);
			$this->assign('fields',$fields);
			$this->assign('source_tables',$source_tables);
			$this->assign('source_fields',$source_fields);
			$this->assign('data',$post);
			$this->display('dbimport/admin_post_maps.php');
		}

		exit();
	}

 	/**
	 * 复制
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->dbimport->delete($id) )
		{
			return $this->success(t('删除成功'),request::referer());
		}
		return $this->error($this->dbimport->error());
	}


 	/**
	 * 删除
	 *
	 */
	public function action_copy($id)
	{
		if ( $data = $this->dbimport->get($id) )
		{
			unset($data['id']);

			$data['name'] = $data['name'] .' '.t('复制');

			if ( $this->dbimport->add($data) )
			{
				return $this->success(t('复制成功'),request::referer());
			}
		}
		return $this->error($this->dbimport->error());
	}


	/**
	 * 导入指定配置的数据
	 *
	 * @param  int $id 配置编号
	 * @return json
	 */
	public function action_import($id)
	{
		// 获取规则
		if ( $data = $this->dbimport->get($id) )
		{
			$count = $this->dbimport->import($data);

			if ( $count )
			{
				return $this->success(t('操作成功，导入数据 $1 条', $count));
			}
		}

		return $this->error($this->dbimport->error());
	}


	/**
	 * 获取数据库数据表
	 *
	 * @param  array|string  $config 数据库配置
	 * @return array  数据表数组
	 */
	private function tables($config)
	{
		$tables = array();

		$_tables = zotop::db($config)->tables();

		foreach($_tables as $name=>$table)
		{
			$tables[$name] = $table['comment'] ? $name.'['.$table['comment'].']' : $name;
		}

		return $tables;
	}

	/**
	 * 获取数据库数据表字段列表
	 *
	 * @param  array|string  $config 数据库配置
	 * @param  string  $table 表名称
	 * @return array  字段数组
	 */
	private function fields($config, $table)
	{
		return $table ? zotop::db($config)->table($table)->fields() : array();
	}
}
?>