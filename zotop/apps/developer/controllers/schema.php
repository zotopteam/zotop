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
class developer_controller_schema extends admin_controller
{
	public $db;

	// 定义zotop支持的字段类型
	public $types = array(
			'char'			=> 'CHAR',
			'varchar'		=> 'VARCHAR',

			'tinytext'		=> 'TINYTEXT',
			'mediumtext'	=> 'MEDIUMTEXT',
			'longtext'		=> 'LONGTEXT',
			'text'			=> 'TEXT',

			'tinyint'		=> 'TINYINT',
			'smallint'		=> 'SMALLINT',
			'mediumint'		=> 'MEDIUMINT',
			'bigint'		=> 'BIGINT',
			'int'			=> 'INT',

			'double'		=> 'DOUBLE',
			'float'			=> 'FLOAT',
			'decimal'		=> 'DECIMAL',
			'longblob'		=> 'LONGBLOB',
			'blob'			=> 'BLOB',
	);

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		//获取数据库实例
		$this->db = zotop::db();
	}

	/**
	 * 当前数据库的数据表列表
	 *
	 */
	public function action_index($table)
    {
		$project = zotop::cookie('project');

		$project = @include(ZOTOP_PATH_APPS . DS . $project . DS .'_project.php');

		// 获取数据表结构信息
		$schema = $this->db->table($table)->schema();

		$this->assign('title',t('数据库管理'));
		$this->assign('project',$project);
		$this->assign('database',$database);
		$this->assign('table',$table);
		$this->assign('schema',$schema);
		$this->display();
    }

	/**
	 * 数据表多选操作
	 *
	 * @param $tablename 数据表名称
	 * @return mixed
	 */
    public function action_operate($tablename, $operation)
    {
		if ( $post = $this->post() )
		{
			$table = $this->db->table($tablename);

			switch($operation)
			{
				case 'index' :
				case 'unique' :
				case 'fulltext' :
					$index = implode('_',$post['id']);
					if ( $table->existsIndex($index)  )
					{
						return $this->error(t('%s [%s] 已存在',$post['operation'],$index));
					}
					$result = $table->addIndex($index,$post['id'],$operation);
					break;
				case 'primary' :
					//必须先删除可能存在的主键
					$table->dropPrimary();

					$result = $table->addPrimary($post['id']);
					break;
			}

			if ( $result )
			{
				return $this->success(t('%s成功',$post['operation']),u("developer/schema/{$tablename}"));
			}
			$this->error(t('%s失败',$post['operation']));
		}

		$this->error(t('%s失败',t('操作')));
    }

	/**
	 * 获取数据表schema
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_show($table)
    {
		// 获取全部数据
		$data = $this->db->from($table)->getAll();

		// 获取数据表结构信息
		$schemastr = $this->db->table($table)->toString();

		$this->assign('title',t('数据表结构数组'));
		$this->assign('table',$table);
		$this->assign('data',$data);
		$this->assign('schemastr',$schemastr);
		$this->display();
    }

	/**
	 * 新建字段
	 *
	 * @param $table 数据表名称
	 * @param $oname 字段原名称
	 * @return mixed
	 */
    public function action_checkfield($tablename, $oname='')
	{
		$table = $this->db->table($tablename);

		if( empty($oname) and $table->existsField($_GET['name']) )
		{
			exit('"'.t('已经存在，请重新输入',$_GET['name']).'"');
		}

		if( $oname != $_GET['name'] and $this->db->table($tablename)->existsField($_GET['name']) )
		{
			exit('"'.t('已经存在，请重新输入',$_GET['name']).'"');
		}

		exit('true');
	}

	/**
	 * 新建字段
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_addfield($tablename)
    {
		$table = $this->db->table($tablename);

		if ( $post = $this->post() )
		{
			if ( $table->existsField($post['name']) )
			{
				return $this->error(t('%s 已经存在', $post['name']));
			}

			if ( $table->addField($post['name'], $post) )
			{
				return $this->success(t('%s成功',t('新建字段')),u("developer/schema/{$tablename}"));
			}

			return $this->error(t('%s失败',t('新建字段')));
		}

		$fields = $table->fields();

		$data = array(
			'primary' => false,
			'autoinc' => false,
			'notnull' => false,
			'unsigned' => false,
			'position' => 'last',
		);

		$position['first'] = t('数据表开头');
		foreach($fields as $name=>$field)
		{
			$position["after {$name}"] = t('%s 之后', $name);
		}
		$position['last'] = t('数据表结尾');


		$this->assign('title',t('新建字段'));
		$this->assign('tablename',$tablename);
		$this->assign('position',$position);
		$this->assign('types',$this->types);
		$this->assign('data',$data);
		$this->display('developer/schema_post.php');
    }

	/**
	 * 新建字段
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_editfield($tablename, $field)
    {
		$table = $this->db->table($tablename);

		if ( $post = $this->post() )
		{
			if ( $field != $post['name'] and $table->existsField($post['name']) )
			{
				return $this->error(t('%s 已经存在', $post['name']));
			}

			if ( $table->changeField($field, $post) )
			{
				return $this->success(t('%s成功',t('编辑字段')),u("developer/schema/{$tablename}"));
			}

			return $this->error(t('%s失败',t('编辑字段')));
		}

		$fields = $table->fields();

		$data = $fields[$field] + array(
			'name' => $field,
			'primary' => false,
			'autoinc' => false,
			'notnull' => false,
			'unsigned' => false,
			'position' => 'last',
		);

		$position['first'] = t('数据表开头');
		foreach($fields as $name=>$field)
		{
			$position["after {$name}"] = t('%s 之后', $name);
		}
		$position['last'] = t('当前位置');


		$this->assign('title',t('编辑字段'));
		$this->assign('tablename',$tablename);
		$this->assign('position',$position);
		$this->assign('types',$this->types);
		$this->assign('data',$data);
		$this->display('developer/schema_post.php');
    }

	/**
	 * 删除字段
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_dropField($table,$field)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->table($table)->dropField($field) )
			{
				return $this->success(t('%s成功',t('删除')) ,u("developer/schema/{$table}"));
			}

			return $this->error(t('%s失败',t('删除')));
		}
    }

	/**
	 * 删除索引
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_dropPrimary($table)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->table($table)->dropPrimary() )
			{
				return $this->success(t('%s成功',t('删除')) ,u("developer/schema/{$table}"));
			}

			return $this->error(t('%s失败',t('删除')));
		}
    }

	/**
	 * 删除索引
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_dropIndex($table,$field)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->table($table)->dropIndex($field) )
			{
				return $this->success(t('%s成功',t('删除')) ,u("developer/schema/{$table}"));
			}

			return $this->error(t('%s失败',t('删除')));
		}
    }

}
?>