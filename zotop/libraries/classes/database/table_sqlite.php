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

class database_table_sqlite extends database_table
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

			'tinytext'		=> 'TEXT',
			'mediumtext'	=> 'TEXT',
			'longtext'		=> 'TEXT',
			'text'			=> 'TEXT',

			'tinyint'		=> 'TINYINT',
			'smallint'		=> 'SMALLINT',
			'mediumint'		=> 'INTEGER',
			'bigint'		=> 'INTEGER',
			'int'			=> 'INTEGER',

			'float'			=> 'FLOAT',

			'double'		=> 'DOUBLE', //big float			
			
			'decimal'		=> 'NUMERIC', //NUMERIC

			'longblob'		=> 'BLOB',
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
			'comment'	=> $this->table($table) //注释
		);

		// 获取字段信息
		$schema['fields'] = $this->fields($table);

		foreach( $schema['fields'] as $name => &$field )
		{
			if ( $field['primary'] )
			{
				$schema['primary'][] = $name;unset($field['primary']);
			}
		}

		// 获取索引信息
		$indexes = $this->indexes($table);
		
		foreach ($indexes as $index)
		{
			$schema[$index['type']][$index['name']] = $index['column'];
		}
		
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

		//获取创建sql，用于测试unsigned是否存在 check(id>0)
		$createsql = $this->db->getField("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$this->table($table)}'");

		//debug::dump($createsql);

		// 获取字段信息
		$result = $this->db->select("PRAGMA table_info(". $this->table($table) .")");

		//debug::dump($result);
		//exit();

		/*
			[cid] => 0
			[name] => id
			[type] => int(10)
			[notnull] => 0
			[dflt_value] => 
			[pk] => 1			
		*/

		if ( is_array($result) )
		{
			foreach ($result as $row)
			{
				// 所有的字段名称都小写，2013-04-24
				$field = strtolower($row['name']);

				// 分解原始Type，获取type及length
				if ( preg_match('/^([^(]+)\((.*)\)/', $row['type'], $matches) )
				{
					$type = $matches[1];
					$length = $matches[2];
				}
				else
				{
					$type = $row['type'];
					$length = NULL;
				}

				// 只支持内置的数据类型
				if ( isset($types[strtoupper($type)]) )
				{
					// 返回 zotop 内置数据类型
					$fields[$field]['type'] = $types[strtoupper($type)];

					// length
					if ($length)
					{
						$fields[$field]['length'] = $length; //2012.03.02 修正 float 和 DECIMAL 的精度问题
					}
					
					// notnull
					$fields[$field]['notnull'] = !empty($row['notnull']);

					// default
					if ( $row['dflt_value'] === 'NULL' ) 
					{
						$fields[$field]['default'] = NULL;
					}				
					elseif ( '' !== $default = trim($row['dflt_value'], "'")  )
					{
						$fields[$field]['default'] = $default; //2012-03-02修正
					}			

					// unsigned
					if ( false !== strpos($createsql, "{$field}>= 0")  )
					{
						$fields[$field]['unsigned'] = true;
					}
					
					// primary
					if ( $row['pk'] )
					{
						$fields[$field]['primary'] = true;
					}

					// autoinc 测试createsql是否含有 [id] INTEGER PRIMARY KEY AUTOINCREMENT
					if ( false !== strpos($createsql, "{$field} INTEGER PRIMARY KEY AUTOINCREMENT")  )
					{
						$fields[$field]['autoinc'] = true;
					}

					// comment
					$fields[$field]['comment'] = $field;
					
				}
				else
				{
					throw new zotop_exception("Unable to parse the column type " . $row['type']);
				}
			}
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

		// 获取表实际名称
		$table = $this->table($table);
		
		// 获取索引列表
		$result = $this->db->select("PRAGMA index_list(". $table .")");

		//debug::dump($result);

		/*
            [seq] => 0
            [name] => title
            [unique] => 1
		*/

		if ( is_array($result) )
		{
			// 索引
			foreach ($result as $row)
			{
				if (strpos($row['name'], 'sqlite_autoindex_') !== 0)
				{
					$name = $row['name'];

					// 去除索引名称中的表前缀
					if ( strlen($table) < strlen($name) )
					{
						$name = ( substr($name, 0, strlen($table)) == $table ) ? substr($name, strlen($table) + 1) : $name;
					}

					$indexes[$row['name']] = array(
					  'type' => $row['unique'] ? 'unique' : 'index',
					  'name' => $name,  //索引名称,不含表前缀
					);
				}
			}

			// 获取column
			foreach ($indexes as $name => $index)
			{
				// 获取索引信息
				$result = $this->db->select("PRAGMA index_info(". $name .")");

				if ( is_array($result ) )
				{
					foreach ($result as $row)
					{
						$indexes[$name]['column'][] = $row['name']; //字段名称
					}
				}
			}
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
		$sql = array("CREATE TABLE " . $this->table($table) . " (\n" . $this->createColumsSql($schema, $table) . "\n);\n");
		
		foreach ( (array)$schema['index'] as $name =>$column )
		{
			$sql[] = $this->createIndexSql($name, $column, 'index', $table);
		}

		foreach ( (array)$schema['unique'] as $name =>$column )
		{
			$sql[] = $this->createIndexSql($name, $column, 'unique', $table);
		}

		return $sql;
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
			// 自动增长 createFieldSql 会自动设为主键，所以必须删除主键设置，重复设定
			if ( $field['autoinc'] )
			{
				if (isset($schema['primary']) && ($key = array_search($name, $schema['primary'])) !== FALSE)
				{
					unset($schema['primary'][$key]);
				}
			}
			
			// 主键字段必须删除长度
			//if ( in_array($name, (array)$schema['primary'])  )
			//{
				//unset($field['length']);
			//}

			$sql[] = $this->createFieldSql($name, $this->processField($field));
		}

		if ( !empty($schema['primary']) ) 
		{
			$sql[] = " PRIMARY KEY (" . $this->createKeySql($schema['primary']) . ")";
		}

		return implode(", \n", $sql);
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
		
		if ( !empty($spec['autoinc']) )
		{
			$sql = '`'.$name . "` INTEGER PRIMARY KEY AUTOINCREMENT";

			if ( !empty($spec['unsigned']) )
			{
				$sql .= ' CHECK (' . $name . '>= 0)';
			}
		}
		else
		{
			$sql = '`'.$name . '` ' . $spec['sqlite_type'];

			if ( !empty($spec['length']) and in_array($spec['sqlite_type'], array('CHAR', 'VARCHAR','FLOAT', 'DOUBLE', 'NUMERIC', 'TINYINT', 'SMALLINT', 'INTEGER')) )
			{
				$sql .= '(' . $spec['length'] . ')';
			}

			if ( isset($spec['notnull']) )
			{
				$sql .= $spec['notnull'] ? ' NOT NULL' : ' NULL';
			}

			if ( !empty($spec['unsigned']) AND in_array($spec['sqlite_type'], array('TINYINT', 'SMALLINT', 'INTEGER')) )
			{
				$sql .= ' CHECK (`' . $name . '` >= 0)';
			}

			if ( isset($spec['default']) )
			{
				if ( is_string($spec['default']) AND trim($spec['default']) !== '' )
				{
				  $spec['default'] = "'" . $spec['default'] . "'";
				}

				if ( trim($spec['default']) !== '' )
				{
					$sql .= ' DEFAULT ' . $spec['default'];
				}
			}

			if ( empty($spec['notnull']) && !isset($spec['default']) )
			{
				$sql .= ' DEFAULT NULL';
			}
		}
		return $sql;
	}

	/**
	* Set database-engine specific properties for a field.
	*
	* @param $field
	*   A field description array, as specified in the schema documentation.
	*/
	/**
	* 将字段信息转化为mysql字段信息
	*
	* @param $field  字段描述数组
	* @return array 转化后的数组
	*/
	protected function processField($field)
	{
		$types = $this->types();

		$field['sqlite_type'] = isset($field['sqlite_type']) ? strtoupper($field['sqlite_type']) : $types[strtolower($field['type'])];

		return $field;
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
			$return[] = is_array($field) ? $field[0] : $field;
		}

		return implode(', ', $return);
	}

  /**
   * Build the SQL expression for indexes.
   *
	 * @access public
	 * @param string $index  索引名称
	 * @param string|array $column  索引字段名称
	 * @param bool $type  索引类型
	 * @param string $table  数据表名，不含前缀
	 * @return string
   */
	protected function createIndexSql($index, $column, $type, $table)
	{
		$table = $this->table($table);

		$type =  ( $type == 'unique' ) ? 'UNIQUE INDEX' : 'INDEX';
		
		$sql = 'CREATE '.$type.' ' . $table.'_'.$index. ' ON ' . $table . ' (' . $this->createKeySql($column) . "); \n";

		return $sql;
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
		if ( false === $this->db->getField("SELECT 1 FROM `sqlite_master` WHERE `type`='table' AND `name`='{$this->table($table)}';") )
		{
			return false;
		}
		return true;
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

		// 获取表的索引信息
		$indexes = $this->indexes($table);
		
		// 重命名数据表 SQLITE不支持索引的重命名，必须手动迁移索引		
		if ( false !== $this->db->execute('ALTER TABLE ' . $this->table($table) . ' RENAME TO ' . $this->table($newname). '') )
		{
			// 删除原表的索引信息, SQLITE 无法 RENAME INDEX
			foreach ( $indexes as $index )
			{
				// 删除原有索引
				$this->dropIndex($index['name'],$table);
				
				// 还原索引到新表
				$this->db->execute($this->createIndexSql($index['name'],$index['column'],$index['type'],$newname));
			}

			return true;
		}

		return false;
	}

	/**
	 * 设置或者获取表的comment属性
	 *
	 */
	public function comment($comment=null, $table='')
	{
		return true;		
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

		if ( false !== $this->db->execute('DROP TABLE ' . $this->table($table) . '') )
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
		
		try
		{	
			$this->db->begin();
			$this->db->execute('DELETE FROM `' . $this->table($table) . '`');
			$this->db->execute('DELETE FROM `sqlite_sequence` WHERE `name` = "' . $this->table($table) . '"');
			$this->db->commit();
		}
		catch(Exception $e)
		{
			$this->db->rollback();
			return false;
		}
		return true;
	}


	/**
	 * 优化数据表
	 */
	public function optimize()
	{
		try
		{
			return $this->db->execute('VACUUM `' . $this->table() . '`');
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
	/**
	 * Sqlite 不支持检查数据表
	 */
	public function check()
	{
		return true;
	}

	/**
	 * Sqlite 不支持修复数据表
	 */
	public function repair()
	{
		return true;
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
		try
		{
			// 开始事物
			$this->db->begin();	

			$table = empty($table) ? $this->table : $table;

			// 临时表名称
			$new_table = 'temp_'.time();

			// 创建临时表
			$this->create($new_schema, $new_table);

			$old_fields = array();
			$new_fields = array_keys($new_schema['fields']);
			
			$mapping = array_flip($mapping);
			
			foreach( $new_fields as $field )
			{
				$old_fields[] = isset($mapping[$field]) ? $mapping[$field] : $field;
			}
			
			$this->db->execute("INSERT INTO ". $this->table($new_table) ." (".implode(", ",$new_fields).") SELECT ".implode(", ", $old_fields)." FROM ". $this->table($table) .";\n");

			$old_count = $this->db->table($table)->count();
			$new_count = $this->db->table($new_table)->count();

			if ( $old_count == $new_count )
			{
				$this->drop($table); //删除原表
				$this->rename($table, $new_table); //将新表重命名为原表
			}
			
			// 提交事务
			$this->db->commit();
		}
		catch(PDOException $e)
		{
			$this->db->rollback();
			return false;
			//throw new zotop_exception($e);
		}

		return true;
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
		$fields = $this->fields($table);

		foreach( $fields as $name => $field )
		{
			if ( $field['primary'] ) $primary[] = $name;
		}

		return $primary;
	}

	/**
	 * 添加主键，如果表已经存在主键则返回false
	 * 
	 * @code
	 * 
	 * $this->db->schema('test')->addPrimary('id');
	 *
	 * $this->db->schema('test')->addPrimary('id,nid');
	 *
	 * $this->db->schema('test')->addPrimary(array('id','nid'));
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
		// 判读表是否存在
		if ( !$this->exists($table) ) return false;

		// 获取表信息
		$old_schema = $this->schema($table);
		$new_schema = $old_schema;
		
		// 已经存在主键
		if ( !empty($new_schema['primary']) ) return false;
		
		// 将字符串格式: id,nid 转化为主键数组
		if ( is_string($fields) ) 
		{
			$fields = explode(',', $fields);
		}
		
		$new_schema['primary'] = $fields;
		
		return $this->alter($table, $old_schema, $new_schema);
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
		$table = empty($table) ? $this->table : $table;

		//获取表信息
		$old_schema = $this->schema($table);
		$new_schema = $old_schema;

		if ( empty($new_schema['primary']) ) 
		{
			return true;
		}

		//foreach ( $new_schema['fields'] as &$field )
		//{
			//如果是自增字段，必定会是主键，所以如果删除主键，同时会删除自增
		//	unset($field['primary'],$field['autoinc']);
		//}

		$new_schema['primary'] = array();

		return $this->alter($table, $old_schema, $new_schema);
	}
	
	/**
	 * 添加索引
	 *
	 * addIndex('testttile','id')
	 * addIndex('testttile2',array('id','nid'))
	 * 
	 * @access public
	 * @param string $index  索引名称
	 * @param string|array $column  索引字段名称
	 * @param bool $type  索引类型
	 * @param string $table  数据表名，不含前缀
	 * @return bool
	 */
	public function addIndex($index, $column, $type='index', $table='')
	{
		if ( !$this->exists($table) ) return false;

		if ( $this->existsIndex($index,$table) ) return false;

		$sql = $this->createIndexSql($index, $column, $type, $table);
		
		return $this->db->execute($sql);
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
		if ( false !== $this->db->select("PRAGMA index_info(". $this->table($table) . '_' . trim($index) .")") )
		{
			return true;
		}
		
		return false;
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

		return $this->db->execute("DROP INDEX ". $this->table($table) . '_' . trim($index) ."");
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
		$result = $this->db->select("PRAGMA table_info(". $this->table($table) .")");

		foreach( $result as $row )
		{
			if ( $row['name'] == $column ) return true;
		}

		return false;
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
		
		// SQLite 不支持高级 ALTER TABLE 模式，如果字段存在非空或者默认值或者索引等信息，必须使用新建表复制数据的方法
		// SQLite ALTER TABLE  [database.] table ADD [COLUMN]语法用于在已有表中添加新的字段。新字段总是添加到已有字段列表的末尾。 Column可以是CREATE TABLE中允许出现的任何形式，且须符合如下限制：
		// 字段不能有主键或唯一约束。
		// 字段不能有这些缺省值：CURRENT_TIME, CURRENT_DATE 或CURRENT_TIMESTAMP
		// 若定义了NOT NULL约束，则字段必须有一个非空的缺省值。
		if ( empty($keys_new) && ( empty($field['notnull']) || isset($field['default']) ) )
		{
			$sql = 'ALTER TABLE ' . $this->table($table) . ' ADD ' . $this->createFieldSql($column, $this->processField($field));

			//debug::dump($sql);
			return $this->db->execute($sql);
		}
		else
		{
			
		}

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

		//映射
		$mapping = array();

		//重命名
		if ( $column != $new_column )
		{
			unset($field['name']);

			//新字段名称是否存在
			if ( $this->existsField($new_column, $table) ) return false;
			
			//添加映射
			$mapping[$column] = $new_column;
		}
		
		//获取schema
		$old_schema = $this->schema($table);
		$new_schema = $old_schema;
		
		//重设schema中字段，并保持位置不变
		$fields = array();
		foreach($new_schema['fields'] as $key=>$value )
		{
			if ( $key == $column )
			{
				$fields[$new_column] = $field + $old_schema['fields'][$column];
			}
			else
			{
				$fields[$key] = $value;
			}
		}
		$new_schema['fields'] = $fields;

		// Map the former indexes to the new column name.
		$new_schema['primary'] = $this->mapKeyDefinition($new_schema['primary'], $mapping);

		foreach (array('unique', 'index') as $k)
		{
			foreach ($new_schema[$k] as &$key_definition)
			{
				$key_definition = $this->mapKeyDefinition($key_definition, $mapping);
			}
		}

		// Add in the keys from $keys_new.
		if ( isset($keys_new['primary']) )
		{
			$new_schema['primary'] = $keys_new['primary'];
		}

		foreach (array('unique', 'index') as $k)
		{
			if (!empty($keys_new[$k]))
			{
				$new_schema[$k] = $keys_new[$k] + $new_schema[$k];
			}
		}

		return $this->alter($table, $old_schema, $new_schema, $mapping);	
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
		
		$old_schema = $this->schema($table);
		$new_schema = $old_schema;

		unset($new_schema['fields'][$column]);

		//删除索引中的相关字段
		foreach ($new_schema['index'] as $index => $fields)
		{
			foreach ($fields as $key => $field_name)
			{
				if ($field_name == $column)
				{
					unset($new_schema['index'][$index][$key]);
				}
			}

			// If this index has no more fields then remove it.
			if ( empty($new_schema['index'][$index])) 
			{
				unset($new_schema['index'][$index]);
			}
		}
		
		//删除外键约束中的相关字段
		foreach ($new_schema['unique'] as $index => $fields)
		{
			foreach ($fields as $key => $field_name)
			{
				if ($field_name == $column)
				{
					unset($new_schema['unique'][$index][$key]);
				}
			}

			// If this index has no more fields then remove it.
			if ( empty($new_schema['unique'][$index])) 
			{
				unset($new_schema['unique'][$index]);
			}
		}

		return $this->alter($table, $old_schema, $new_schema);
	}

	/**
	 * Utility method: rename columns in an index definition according to a new mapping.
	 *
	 * @param $key_definition  The key definition.
	 * @param $mapping The new mapping.
	 */
	protected function mapKeyDefinition(array $key_definition, array $mapping)
	{
		foreach ($key_definition as &$field)
		{
			// The key definition can be an array($field, $length).
			if (is_array($field))
			{
				$field = &$field[0];
			}
			if (isset($mapping[$field]))
			{
				$field = $mapping[$field];
			}
		}
		
		return $key_definition;
	}
}