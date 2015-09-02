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
	 * 数据表结构
	 *
	 * @param  string $table 数据表名称，不含前缀
	 * @return null
	 */
	public function action_index($table)
    {
		$app    = @include(ZOTOP_PATH_APPS . DS . zotop::cookie('project_dir') . DS .'app.php');

		$schema = $this->db->schema($table);

		$this->assign('title',t('数据表结构'));
		$this->assign('app',$app);
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
			switch($operation)
			{
				case 'index' :
				case 'unique' :
				case 'fulltext' :

					$indexname = implode('_',$post['id']);

					if ( $this->db->existsIndex($tablename, $indexname)  )
					{
						return $this->error(t('$1 [$2] 已存在', $post['operation'], $indexname));
					}

					$result = $this->db->addIndex($tablename, $indexname, $post['id'], $operation);

					break;
				case 'primary' :

					//必须先删除可能存在的主键才能创建主键
					$this->db->dropPrimary($tablename);

					$result = $this->db->addPrimary($tablename, $post['id']);

					break;
			}

			if ( $result )
			{
				return $this->success(t('$1成功',$post['operation']),u("developer/schema/{$tablename}"));
			}
			$this->error(t('$1失败',$post['operation']));
		}

		$this->error(t('$1失败',t('操作')));
    }

	/**
	 * 获取数据表schema
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_show($tablename)
    {
		// 获取全部数据
		$data      = $this->db->table($tablename)->select();

		// 获取数据表结构信息
		$schema    = $this->db->schema($tablename);

		// schema 格式化
		$schemastr = $this->schemaStringify($schema);

		$this->assign('title',t('数据表结构'));
		$this->assign('tablename',$tablename);
		$this->assign('schemastr',$schemastr);
		$this->assign('data',$data);
		$this->display();
    }

	/**
	 * 获取表的schema数据的array字符串
	 *
     * @access public
     * @param string $schema  表描述数据数组
     * @return bool
	 *
	 */
	public function schemaStringify($schema)
	{
		// 输出数组
		$schema = arr::export($schema);

		// 替换comment为可翻译
		$schema = preg_replace("/'comment'\s*=>\s*'(.*?)'/","'comment'=>t('$1')",$schema);

		return $schema;
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
		if( empty($oname) and $this->db->existsField($tablename,$_GET['name']) )
		{
			exit('"'.t('已经存在，请重新输入',$_GET['name']).'"');
		}

		if( $oname != $_GET['name'] and $this->db->existsField($tablename,$_GET['name']) )
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
		if ( $post = $this->post() )
		{
			if ( $this->db->existsField($tablename, $post['name']) )
			{
				return $this->error(t('$1已经存在', $post['name']));
			}

			if ( $this->db->addField($tablename, $post) )
			{
				return $this->success(t('$1成功',t('新建字段')),u("developer/schema/{$tablename}"));
			}

			return $this->error(t('$1失败',t('新建字段')));
		}

		$fields = $this->db->fields($tablename);

		$data = array(
			'primary'  => false,
			'autoinc'  => false,
			'notnull'  => false,
			'unsigned' => false,
			'position' => 'last',
		);

		$position['first'] = t('数据表开头');

		foreach($fields as $name=>$field)
		{
			$position["after {$name}"] = t('$1 之后', $name);
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
    public function action_editfield($tablename, $fieldname)
    {
		if ( $post = $this->post() )
		{
			if ( $fieldname != $post['name'] and $this->db->existsField($tablename, $post['name']) )
			{
				return $this->error(t('$1已经存在', $post['name']));
			}

			if ( $this->db->changeField($tablename, $fieldname, $post) )
			{
				return $this->success(t('$1成功',t('编辑字段')),u("developer/schema/{$tablename}"));
			}

			return $this->error(t('$1失败',t('编辑字段')));
		}

		$fields = $this->db->fields($tablename);

		$data = $fields[$fieldname] + array(
			'name'     => $fieldname,
			'primary'  => false,
			'autoinc'  => false,
			'notnull'  => false,
			'unsigned' => false,
			'position' => 'last',
		);

		$position['first'] = t('数据表开头');

		foreach($fields as $name=>$field)
		{
			$position["after {$name}"] = t('$1 之后', $name);
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
    public function action_dropField($tablename,$fieldname)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->dropField($tablename, $fieldname) )
			{
				return $this->success(t('$1成功',t('删除')) ,u("developer/schema/{$tablename}"));
			}

			return $this->error(t('$1失败',t('删除')));
		}
    }

	/**
	 * 删除索引
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_dropPrimary($tablename)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->dropPrimary($tablename) )
			{
				return $this->success(t('$1成功',t('删除')) ,u("developer/schema/{$tablename}"));
			}

			return $this->error(t('$1失败',t('删除')));
		}
    }

	/**
	 * 删除索引
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */
    public function action_dropIndex($tablename,$indexname)
    {
		if ( $post = $this->post() )
		{
			if ( $this->db->dropIndex($tablename,$indexname) )
			{
				return $this->success(t('$1成功',t('删除')) ,u("developer/schema/{$tablename}"));
			}

			return $this->error(t('$1失败',t('删除')));
		}
    }

}
?>