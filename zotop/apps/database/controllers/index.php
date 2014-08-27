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
class database_controller_index extends admin_controller
{
	public $config;
	public $db;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		if ( $databaseconfig = $_GET['databaseconfig'] )
		{
			zotop::cookie('databaseconfig', $databaseconfig);
		}
		else
		{
			$databaseconfig = zotop::cookie('databaseconfig');
		}

		if ( !empty($databaseconfig) )
		{
			$databases = @include(ZOTOP_PATH_DATA.DS.'database.php');

			// 获取数据库配置
			$this->config = $databases[$databaseconfig];

			//获取数据库实例
			$this->db = database::instance($this->config);
		}
	}

	/**
	 * 配置中的数据库
	 *
	 */
	public function action_Index()
    {
		$databases = @include(ZOTOP_PATH_DATA.DS.'database.php');

		$this->assign('title',t('数据库管理'));
		$this->assign('databases',$databases);
		$this->display();
	}

	/**
	 * 当前数据库的数据表列表
	 *
	 */
	public function action_Table()
    {
		//如果数据库不存在自动创建
		if ( !$this->db->exists() )
		{
			$this->db->create();
		}

		// 尝试创建一个新表
		/*
		$table = $this->db->table('test');

		if ( !$table->exists() )
		{
			$table->create(array(
				'fields' => array(
					'id'        => array('type' => 'int', 'length'=>10, 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE),
					'vid'       => array('type' => 'int', 'length'=>10, 'unsigned' => TRUE, 'notnull' => TRUE,'default' => 0),
					'type'      => array('type' => 'char','length' => 32,'notnull' => TRUE, 'default' => ''),
					'language'  => array('type' => 'char','length' => 12,'notnull' => TRUE,'default' => ''),
					'title'     => array('type' => 'varchar','length' => 255,'notnull' => TRUE, 'default' => ''),
					'uid'       => array('type' => 'int', 'length'=>10, 'notnull' => TRUE, 'default' => 0),
					'nid'      => array('type' => 'tinyint', 'length'=>3, 'unsigned' => TRUE, 'notnull' => TRUE, 'default' => 0),
				),
				'index' => array(
					'uid'		=> array('uid'),
					'nid'	=> array('vid','nid'),
					'types'	=> array(array('type', 4)),
					'title_type'  => array('title', array('type', 4)),
				),
				'unique' => array(
					'vid' => array('vid'),
				),
				'foreign' => array(
					'uid' => array('users' => 'uid'),
				),
				'primary' => array('id'),
				'comment' => '测试表',
			));
		}
		*/

		// database
		$database = str_replace(ZOTOP_PATH,'zotop:\\',$this->db->database);

		// 获取全部数据表
		$tables = $this->db->tables();

		$this->assign('title',t('数据库管理'));
		$this->assign('config',$this->config);
		$this->assign('database',$database);
		$this->assign('tables',$tables);
		$this->display();
    }

	/**
	 * 数据表多选操作
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_Operate($operation)
    {
		if ( $post = $this->post() )
		{
			foreach($post['id'] as $id)
			{
				switch($operation)
				{
					case 'repair' :
						$this->db->table($id)->repair();
						break;
					case 'optimize' :
						$this->db->table($id)->optimize();
						break;
					case 'check' :
						$this->db->table($id)->check();
						break;
				}
			}

			return $this->success(t('%s成功',$post['operation']));
		}
    }

	/**
	 * 新建数据表，默认将插入一个编号为id的项
	 *
	 * @return mixed
	 */
    public function action_Create()
    {
		if ( $post = $this->post() )
		{
			$schema = array(
				'fields' => array(
					'id'        => array('type' => 'int', 'length' => 10, 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE),
				),
				'primary' => array('id'),
				'comment' => '',
			);

			if (  $this->db->table($post['newvalue'])->exists() )
			{
				return $this->error(t('%s 已经存在', $post['newvalue']));
			}

			if ( $this->db->table($post['newvalue'])->create($schema) )
			{
				return $this->success(t('%s成功',t('新建数据表')),zotop::url('database/index/table'));
			}

			return $this->error(t('%s失败',t('新建数据表')));
		}
	}

	/**
	 * 重命名数据表
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_Rename($table)
    {
		if ( $post = $this->post() )
		{
			if (  $this->db->table($post['newvalue'])->exists() )
			{
				return $this->error(t('%s 已经存在', $post['newvalue']));
			}

			if ( $this->db->table($table)->rename($post['newvalue']) )
			{
				return $this->success(t('%s成功',t('重命名')),zotop::url('database/index/table'));
			}

			return $this->error(t('%s失败',t('重命名')));
		}
    }

	/**
	 * 重命名数据表
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_Comment($table)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->table($table)->comment($post['newvalue']) )
			{
				return $this->success(t('%s成功',t('操作')),zotop::url('database/index/table'));
			}

			return $this->error(t('%s失败',t('操作')));
		}
    }

	/**
	 * 当前数据库的数据表删除
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_Delete($table)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->table($table)->drop() )
			{
				return $this->success(t('%s成功',t('删除')) ,zotop::url('database/index/table'));
			}

			return $this->error(t('%s失败',t('删除')));
		}
    }

}
?>