<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * Mysql 数据库操作类
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop.util
 * @author     zotop team
 * @license    http://zotop.com/license.html
*/

class database_table_mysql extends database_table
{
	/**
	 * Thanks to Drupal's amazing works.
	 *
	 * 字段类型映射，统一不同数据库的字段类型表达形式
	 * date 或者 datetime 请使用INT字段存储时间戳
	 */
	public function types()
	{
		static $types = array(
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

		return $types;
	}

	/**
	 * 获取数据表结构信息，返回数组
	 *
	 * @param $table 数据表名称
	 * @return array
	 */
	public function schema($table='')
	{
		// 初始化schema
		$schema = array(
			'fields'	=> array(), // 字段
			'index'	=> array(),	// 索引		
			'unique'	=> array(), // 唯一
			'primary'	=> array(), // 主键
			'comment'	=> '' //注释
		);

		// 获取字段信息
		$schema['fields'] = $this->fields($table);

		foreach( $schema['fields'] as $name => $field )
		{
			if ( $field['primary'] ) $schema['primary'][] = $name;
		}

		// 获取索引信息
		$indexes = $this->indexes($table);
		
		foreach ($indexes as $index)
		{	
			if ( $index['type'] == 'primary' )
			{
				$schema['primary'] = $index['column']; continue;
			}
			$schema[$index['type']][$index['name']] = $index['column'];
		}
		
		// 获取 额外信息
		$infos = $this->db->getRow("SELECT * FROM information_schema.tables WHERE `table_schema`='".$this->db->config['database']."' AND `table_name`='".$this->table($table)."'");

		$schema['comment'] = $infos['TABLE_COMMENT'];
		$schema['engine'] = $infos['ENGINE'];

		return $schema;
	}


	/**
	 * 返回全部的数据表字段信息
	 *
	 * @param $table 数据表名称
	 * @return mixed
	 */	
	public function fields($table='')
	{
		$fields = array();
		
		// 获取字段类型
		$types = array_flip($this->types());

		// 获取字段信息
		$result = $this->db->getAll("SHOW FULL FIELDS FROM `".$this->table($table)."`");
		//debug::dump($result);
		//exit();		
		/*
			[Field] => gid
			[Type] => mediumint(8) unsigned || float(5,2) || int(10)
			[Collation] => 
			[Null] => NO
			[Key] => PRI|MUL
			[Default] => 
			[Extra] => auto_increment
			[Privileges] => select,insert,update,references
			[Comment] => 主键			
		*/

		foreach ((array)$result as $row)
		{
			// 所有的字段名称都小写，2013-04-24
			$field = strtolower($row['Field']);

			// 分解原始Type，获取type及length
			if ( preg_match('/^([^(]+)\((.*)\)/', $row['Type'], $matches) )
			{
				$type = $matches[1];
				$length = $matches[2];
			}
			else
			{
				$type = $row['Type'];
				$length = NULL;
			}


			// 返回 zotop 内置数据类型
			$fields[$field]['type'] = isset($types[strtoupper($type)]) ? $types[strtoupper($type)] : strtoupper($type);

			// length
			if ($length)
			{
				$fields[$field]['length'] = $length; //2012.03.02 修正 float 和 DECIMAL 的精度问题
			}
			
			// notnull
			$fields[$field]['notnull'] = (bool) ($row['Null'] === 'NO'); // not null is NO, null is YES

			// default
			if ( '' !== $default = trim($row['Default'], "'")  )
			{
				$fields[$field]['default'] = $default;
			}
			elseif ( !isset($row['Default']) ) 
			{
				if ($row['Null'] == 'YES') $fields[$field]['default'] = NULL;
			}				

			// unsigned
			if ( false !== strpos($row['Type'], 'unsigned')  )
			{
				$fields[$field]['unsigned'] = true;
			}
			
			//primary 2012-12-06，唯一索引也会显示为 PRI，所以改为根据索引判断是否为主键
			//$fields[$field]['primary'] = (strtolower($row['Key']) == 'pri');

			//autoinc
			$fields[$field]['autoinc'] = (strtolower($row['Extra']) == 'auto_increment');

			// comment
			$fields[$field]['comment'] = $row['Comment'];

		}			

        return $fields;		
	}

	/**
	 * 获取索性
	 *
	 * @access public
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function indexes($table='')
	{
		$indexes = array();

		$result = $this->db->getAll('SHOW INDEX FROM `'.$this->table($table).'`');
		
		/*
		[Table] => zotop_test
		[Non_unique] => 1||0  1 为唯一索引
		[Key_name] => title_2 || PRIMARY
		[Seq_in_index] => 2
		[Column_name] => money
		[Collation] => A
		[Cardinality] => 
		[Sub_part] => 
		[Packed] => 
		[Null] => YES
		[Index_type] => BTREE
		[Comment] =>		
		*/

		//debug::dump($result);

		$indexlist = array();

		foreach ($result as $row)
		{
			$indexlist[] = array(
			  'type'	=> $row['Non_unique'] ? 'index' : ($row['Key_name'] == 'PRIMARY' ? 'primary': 'unique'),
			  'name'	=> $row['Key_name'],
			  'column'	=> ( $row['Sub_part'] ) ? array($row['Column_name'], (int)$row['Sub_part']) : $row['Column_name'],
			);
		}
		
		foreach( $indexlist as $index )
		{
			$indexes[$index['name']]['name'] = $index['name'];
			$indexes[$index['name']]['type'] = $index['type'];
			$indexes[$index['name']]['column'][] = $index['column'];
		}

		return $indexes;
	}

	/**
	 * 根据scheam生成创建表的sql语句，含创建字段，创建索引等
	 *
	 * @access public
	 * @param string $schema  表描述数据数组
	 * @return bool
	 * 
	 */	
	public function createTableSql($schema, $table)
	{
		// 默认值 MyISAM
		$schema += array('engine' => 'MyISAM', 'charset' => 'utf8');
		
		// SQL
		$sql = '';
		$sql .= "CREATE TABLE IF NOT EXISTS `" . $this->table($table) . "` (\n";
		
		// 字段创建语句
		$sql .= $this->createColumsSql($schema, $table). ", \n";

		// PRIMARY KEY & UNIQUE KEY & KEY
		if ( $keys = $this->createKeysSql($schema) )
		{
		  $sql .= implode(", \n", $keys) . ", \n";
		}

		// 删除最后多余的逗号、空格及换行
		$sql = substr($sql, 0, -3);
		
		// 引擎及字符串
		$sql .= "\n) ENGINE = " . $schema['engine'] . " DEFAULT CHARACTER SET " . $schema['charset'];

		// 数据表说明
		if ( !empty($schema['comment']) )
		{
			$sql .= " COMMENT '" . $schema['comment'] . "'";
		}

		return array($sql);	
	}

	/**
	* Build the SQL expression for creating columns.
	*/
	protected function createColumsSql($schema, $table)
	{
		$sql = array();

		// Add the SQL statement for each field.
		foreach ( $schema['fields'] as $name => $field )
		{
			// 自动增长
			if ( $field['autoinc'] )
			{
				if (isset($schema['primary']) && ($key = array_search($name, $schema['primary'])) !== FALSE)
				{
					unset($schema['primary'][$key]);
				}
			}
			$sql[] = $this->createFieldSql($name, $this->processField($field));
		}

		return implode(", \n", $sql);
	}

	/**
	* 将字段信息转化为mysql字段信息
	*
	* @param $field  字段描述数组
	* @return array 转化后的数组
	*/
	protected function processField($field)
	{
		$types = $this->types();

		$field['mysql_type'] = isset($field['mysql_type']) ? strtoupper($field['mysql_type']) : $types[strtolower($field['type'])];

		return $field;
	}

	/**
	* Create an SQL string for a field to be used in table creation or alteration.
	*
	* Before passing a field out of a schema definition into this function it has
	* to be processed by db_processField().
	*
	* @param $name  Name of the field.
	* @param $spec  The field specification, as per the schema data structure format.
	*/
	protected function createFieldSql($name, $spec)
	{
		$sql = '`'.$name . '` ' . $spec['mysql_type'];

		// 长度值支持 数字，及 flaot的 4,2 类型
		if ( !empty($spec['length']) && !preg_match('@^(DATE|DATETIME|TIME|TINYBLOB|TINYTEXT|BLOB|TEXT|MEDIUMBLOB|MEDIUMTEXT|LONGBLOB|LONGTEXT)$@i', $spec['mysql_type']) )
		{
			$sql .= '(' . $spec['length'] . ')';
		}

		// 无符号只支持数字类型
		if ( !empty($spec['unsigned']) AND in_array($spec['type'],array('tinyint','smallint','mediumint','bigint','int')) )
		{
			$sql .= ' unsigned';
		}
		
		// NOT NULL
		if ( isset($spec['notnull']) )
		{
			$sql .= $spec['notnull'] ? ' NOT NULL' : ' NULL';
		}
		
		// 自动增长 只有数字类型才允许被设为自增
		if ( $spec['autoinc'] and preg_match('@^(TINYINT|SMALLINT|MEDIUMINT|INT|BIGINT)$@i', $spec['mysql_type']) )
		{
			$sql .= ' AUTO_INCREMENT';
		}
		
		// 删除空的 default
		// BLOB|TEXT column  can't have a default value
		if ( $spec['autoinc'] OR $spec['default'] === '' OR preg_match('@^(TINYBLOB|TINYTEXT|BLOB|TEXT|MEDIUMBLOB|MEDIUMTEXT|LONGBLOB|LONGTEXT)$@i', $spec['mysql_type']) ) 
		{
			unset($spec['default']);
		}
		
		//default null 时isset为false，所以判断key是不是存在
		if ( array_key_exists('default', $spec) )
		{
			if ( is_string($spec['default']) )
			{
				$spec['default'] = "'" . $spec['default'] . "'";
			}
			elseif ( !isset($spec['default']) )
			{
				$spec['default'] = 'NULL';
			}

			$sql .= ' DEFAULT ' . $spec['default'];
		}
		elseif ( empty($spec['notnull']) )
		{
			$sql .= ' DEFAULT NULL';
		}

		// Add column comment.
		if ( !empty($spec['comment']) )
		{
		  $sql .= " COMMENT '" . $spec['comment'] . "'";
		}

		if ( strtolower($spec['position']) == 'first' )
		{
			$sql .= ' FIRST';
		}
		elseif( strtolower(substr($spec['position'],0,5)) == 'after' )
		{
			$sql .= ' AFTER `'.substr($spec['position'],6).'`';
		}

		return $sql;
	}

  /**
   * Build the SQL expression for indexes.
   */
	protected function createKeysSql($schema)
	{
		$keys = array();

		if ( !empty($schema['primary']) )
		{
			$keys[] = 'PRIMARY KEY (' . $this->createKeysSqlHelper($schema['primary']) . ')';
		}
		if ( !empty($schema['unique']) )
		{
			foreach ($schema['unique'] as $key => $fields)
			{
				$keys[] = 'UNIQUE KEY `' . $key . '` (' . $this->createKeysSqlHelper($fields) . ')';
			}
		}
		if ( !empty($schema['index']) )
		{
			foreach ($schema['index'] as $index => $fields)
			{
				$keys[] = 'INDEX `' . $index . '` (' . $this->createKeysSqlHelper($fields) . ')';
			}
		}

		return $keys;
	}
	
	// keys sqlhelper
	protected function createKeysSqlHelper($fields)
	{
		$return = array();

		foreach ($fields as $field)
		{
			if (is_array($field))
			{
				$return[] = '`' . $field[0] . '`(' . $field[1] . ')';
			}
			else
			{
				$return[] = '`' . $field . '`';
			}
		}

		return implode(', ', $return);
	}

	// key sql
	protected function createKeySql($fields)
	{
		$return = array();

		//添加对多个字段使用“,”隔开的支持
		if ( is_string($fields) )
		{
			$fields = explode(',',$fields);
		}

		foreach ($fields as $field)
		{
			if (is_array($field))
			{
				$return[] = '`' . $field[0] . '`(' . $field[1] . ')';
			}
			else 
			{
				$return[] = '`' . $field . '`';
			}
		}
		return implode(', ', $return);
	}
	
	/**
	 * 判断数据表是否存在
	 *
     * @access public
     * @param string $table  数据表名，不含前缀
     * @return bool
	 */	
	public function exists($table='')
	{	
		try
		{
			$this->db->query("SELECT 1 FROM `".$this->table($table)."` LIMIT 1;");
			return true;
		}
		catch (Exception $e) 
		{
			return false;
		}
	}

	/**
	 * 重命名数据表
	 *
     * @access public
     * @param string $table  新名称，不含前缀
	 * @param string $table  原名称，不含前缀，空时为默认表
     * @return bool
	 */	
	public function rename($newname, $table='')
	{
		if ( !$this->exists($table) ) return false;

		if ( !$this->valid($newname) OR $this->exists($newname) ) return false;	

		return $this->db->execute('ALTER TABLE `' . $this->table($table) . '` RENAME TO `' . $this->table($newname). '`');
	}

	/**
	 * 设置或者获取表的comment属性
	 *
	 */
	public function comment($comment=null, $table='')
	{

		if ( !$this->exists($table) ) return false;

		if ( $comment === null )
		{
			return $this->db->getOne("SELECT table_comment FROM information_schema.tables WHERE `table_name`='".$this->table($table)."'");
		}
		else if ( false !== $this->db->execute('ALTER TABLE `'.$this->table($table).'` COMMENT=\''.$comment.'\'') )
		{
			return true;
		}

		return false;		
	}

	/**
	 * 删除数据表
	 *
	 * @access public
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */	
	public function drop($table='')
	{
		if ( !$this->exists($table) )  return false;

		if ( false !== $this->db->execute('DROP TABLE `' . $this->table($table) . '`') )
		{
			return true;
		}

		return false;
	}

	/**
	 * 清空表
	 *
	 * @access public
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */	
	public function clear($table='')
	{
		if ( !$this->exists($table) )  return false;		

		if( false !==  $this->db->execute('TRUNCATE TABLE `' . $this->table($table) . '`') )
		{
			return true;
		}

		return false;
	}


	/**
	 * 优化数据表
	 */
	public function optimize()
	{
		if( false !== $this->db->execute('OPTIMIZE TABLE `'.$this->table().'`') )
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 检查数据表
	 */
	public function check()
	{
		if( false !== $this->db->execute('CHECK TABLE `'.$this->table().'`') )
		{
			return true;
		}
		return false;
	}

	/**
	 * 修复数据表
	 */
	public function repair()
	{
		if( false !== $this->db->execute('REPAIR TABLE `'.$this->table().'`') )
		{
			return true;
		}
		return false;
	}

	/**
	 * alter数据表
	 *
	 * @access protected
	 * @param string $table  数据表名，不含前缀
	 * @param string $old_schema  旧结构
	 * @param string $new_schema  新结构
	 * @param string $mapping  映射
	 * @return bool
	 */	
	protected function alter($table, $old_schema, $new_schema, array $mapping = array())
	{

	}

	/**
	 * 获取主键
	 * 
	 * @access public
	 * @param string $table  数据表名，不含前缀
	 * @return array
	 */
	public function primary($table='')
	{
		$primary = array();

		// 获取字段信息
		$indexes = $this->indexes($table);

		foreach( $indexes as $name => $index )
		{
			if ( $index['type'] == 'primary' )
			{
				$primary = $index['column'];
			}
		}

		return $primary;
	}

	/**
	 * 添加主键，如果表已经存在主键则返回false
	 * 
	 * @code
	 * 
	 * $this->db->table('test')->addPrimary('id');
	 *
	 * $this->db->table('test')->addPrimary('id,nid');
	 *
	 * $this->db->table('test')->addPrimary(array('id','nid'));
	 *
	 * @endcode
	 *
	 * @access public
	 * @param string|array $fields  主键字段名称
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function addPrimary($fields, $table='')
	{
		if ( !$this->exists($table) ) return false;

		if ( $this->existsIndex('PRIMARY', $table) ) return false;

		return $this->db->execute('ALTER TABLE `' . $this->table($table) . '` ADD PRIMARY KEY (' . $this->createKeySql($fields) . ')');
	}

	/**
	 * 删除主键
	 * 
	 * @access public
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function dropPrimary($table='')
	{
		if ( !$this->existsIndex('PRIMARY', $table) )
		{
		  return FALSE;
		}

		return $this->db->execute('ALTER TABLE `' . $this->table($table) . '` DROP PRIMARY KEY');
	}
	
	/**
	 * 添加索引
	 *
	 * addIndex('testttile','id')
	 * addIndex('testttile2',array('id','nid'))
	 * 
	 * @access public
	 * @param string $index  索引名称
	 * @param string|array $fields  索引字段名称
	 * @param bool $type  索引类型
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function addIndex($index, $fields, $type='index', $table='')
	{
		if ( !$this->exists($table) ) return false;

		if ( $this->existsIndex($index,$table) ) return false;

		$type = ($type == 'unique') ? 'UNIQUE KEY' : (($type == 'fulltext') ? 'FULLTEXT' : 'INDEX');

		return $this->db->execute('ALTER TABLE `' . $this->table($table) . '` ADD '. $type .' `' . trim($index) . '` (' . $this->createKeySql($fields) . ')');
	}
	
	/**
	 * 索引是否存在
	 *
	 * @access public
	 * @param string $index  索引名称
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function existsIndex($index, $table='')
	{
		$result = $this->db->getRow('SHOW INDEX FROM `' . $this->table($table) . "` WHERE `key_name` = '". trim($index) ."'");
		
		return isset($result['Key_name']);
	}

	/**
	 * 删除索引
	 *
	 * @access public
	 * @param string $index  索引名称
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function dropIndex($index, $table='')
	{
		if ( !$this->existsIndex($index,$table) ) 
		{
			return false;
		}
		
		return $this->db->execute('ALTER TABLE `' . $this->table($table) . '` DROP INDEX `' . trim($index) . '`');
	}
	


	/**
	 * 字段是否存在
	 *
	 * @access public
	 * @param string $column  字段名称
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function existsField($column, $table='')
	{
		try 
		{
		  $this->db->getField("SELECT `{$column}` FROM `" . $this->table($table) . "`");
		  return true;
		}
		catch (Exception $e) 
		{
		  return false;
		}
	}

	/**
	 * 添加字段
	 *
	 * @access public
	 * @param string $column  字段名称
	 * @param array $specification  字段信息
	 * @param string $keys_new  额外信息  		
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function addField($column, $field, $keys_new = array(), $table='')
	{
		//判断表是否存在
		if ( !$this->exists($table) ) return false;

		//判断字段是否存在
		if ( $this->existsField($column,$table) ) return false;
		
		$fixnull = false;
		if ( !empty($field['notnull']) && !isset($field['default']))
		{
		  $fixnull = true;
		  $field['notnull'] = false;
		}

		$sql = 'ALTER TABLE `' . $this->table($table) . '` ADD ';
		$sql .= $this->createFieldSql($column, $this->processField($field));
		
		if ($keys_sql = $this->createKeysSql($keys_new))
		{
		  $sql .= ', ADD ' . implode(', ADD ', $keys_sql);
		}
		
		if ( false === $this->db->execute($sql) )
		{
			return false;
		}
		
		/*
		if ($fixnull)
		{
			$field['notnull'] = true;

			//Invalid default value 
			if ( false == $this->changeField($column, $field, array(), $table) )
			{
				return false;
			}
		}
		*/

		return true;
	}

	/**
	 * 修改字段属性
	 *
	 * @access public
	 * @param string $column  字段名称
	 * @param array $field  字段信息
	 * @param string $keys_new  额外信息		
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function changeField($column, $field, $keys_new = array(), $table='')
	{
		//判断字段是否存在
		if ( !$this->existsField($column,$table) ) return false;

		//获取新字段名称
		$new_column = ( isset($field['name']) AND !empty($field['name']) ) ? $field['name'] : $column;

		if (($column != $new_column) && $this->existsField($new_column,$table)) return false;

		$sql = 'ALTER TABLE `' . $this->table($table) . '` CHANGE `' . $column . '` ' . $this->createFieldSql($new_column, $this->processField($field));
		
		if ($keys_sql = $this->createKeysSql($keys_new))
		{
		  $sql .= ', ADD ' . implode(', ADD ', $keys_sql);
		}
		
		return $this->db->execute($sql);
	}

	/**
	 * 删除字段属性
	 * @access public
	 * @param string $column  字段名称
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function dropField($column, $table='')
	{		
		//判断字段是否存在
		if ( !$this->existsField($column,$table) ) return false;		

		return $this->db->execute('ALTER TABLE `' . $this->table($table) . '` DROP `' . $column . '`');
	}
}