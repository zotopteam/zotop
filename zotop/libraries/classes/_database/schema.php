<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 数据库操作类基类，各种数据库的表操作类将从此类继承
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop.util
 * @author     zotop team
 * @license    http://zotop.com/license.html
 * @code
 * // 创建数据表,返回表是否存在
 *
 *   $table->create(array(
 *		'fields' => array(
 *			'id'        => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE),
 *			'vid'       => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE,'default' => 0),
 *			'type'      => array('type' => 'char','length' => 32,'notnull' => TRUE, 'default' => ''),
 *			'language'  => array('type' => 'char','length' => 12,'notnull' => TRUE,'default' => ''),
 *			'title'     => array('type' => 'varchar','length' => 255,'notnull' => TRUE, 'default' => ''),
 *			'uid'       => array('type' => 'int', 'notnull' => TRUE, 'default' => 0),
 *			'nid'      => array('type' => 'tinyint', 'unsigned' => TRUE, 'notnull' => TRUE, 'default' => 0),
 *		),
 *		'index' => array(
 *			'uid'		=> array('uid'),
 *			'nid'	=> array('vid','nid'),
 *			'types'	=> array(array('type', 4)),
 *			'title_type'  => array('title', array('type', 4)),
 *		),
 *		'unique' => array(
 *			'vid' => array('vid'),
 *		),
 *		'foreign' => array(
 *			'uid' => array('users' => 'uid'),
 *		),
 *		'primary' => array('id'),
 *		'comment' => 'The description for table.',
 *   ));
 *
 * // 设置主键，设置主键之前必须删除主键
 * $table->dropPrimary();
 * $table->addPrimary('id,vid');
 *
 * // 重命名数据包
 * $table->rename('test2');
 *
 * // 添加字段
 * $table->addField(array('table'=>'order','type'=>'int','length'=>10,'default'=>0));
 *
 * // 更改字段
 * $table->changeField('title2',array('table'=>'title','type'=>'varchar','length'=>50,'pk'=>false));
 * @endcode
*/

abstract class database_schema
{
	protected $db = null; //表隶属于的db
	protected $table = ''; //表的名称
	protected $prefix = ''; //表的前缀名称prefix

	public function __construct(&$db , $table)
	{
		$this->db 		= &$db;
		$this->table 	= $table;
		$this->prefix 	= $this->db->config['prefix'] ;
	}

	/**
	 * 返回表的真实名称，表名称以"#"开始将自动替换"#"为表前缀
	 *
     * @access public
     * @param string $table  表名称，不含前缀
     * @return string 返回带前缀的真实表名称
	 *
	 */
	public function table($table='')
	{
		$table = empty($table) ? $this->table : $table;

		if ( $table[0] == '#' )
		{
			$table = $this->prefix . substr($table,1);
		}

		if ( substr($table,0,strlen($this->prefix)) != $this->prefix )
		{
			$table = $this->prefix . $table;
		}

		return $table;
	}

	/**
	 * 表名称是否有效
	 *
     * @access public
     * @param string $table  表名称，不含前缀
     * @return bool
	 */
    public function valid($table='')
    {
        $table = empty($table) ? $this->table : $table;

        //禁止前后含有空格
		if ( $table !== trim($table) ) return false;

        // zero length
		if ( !strlen($table) ) return false;

		// illegal char . / \
        if (preg_match('/[.\/\\\\]+/i', $table))
		{
            return false;
        }
        return true;
    }

	/**
	 * 获取数据类型
	 *
     * @access public
     * @return array
	 *
	 */
	abstract public function types();

	/**
	 * 获取表的schema数据
	 *
     * @access public
     * @param string $schema  表描述数据数组
     * @return bool
	 *
	 */
	abstract public function schema($table='');

	/**
	 * 获取表的schema数据的array字符串
	 *
     * @access public
     * @param string $schema  表描述数据数组
     * @return bool
	 *
	 */
	public function toString($table='')
	{
		$schema = $this->schema($table);

		$str  = "";
		$str .= "array(\n";
		$str .= "	'fields'=>array(\n";
		foreach ( $schema['fields'] as $key=>$field )
		{
			$tab = strlen($key) > 5 ? (strlen($key) > 9? "" : "	") : "		" ;

			$str .= "		'{$key}'{$tab}=> array ( ";
			foreach($field as $k=>$v)
			{
				if( $v === true )
				{
					$str .= "'{$k}'=>true, ";
				}
				elseif ( $v === false )
				{
					if( !in_array($k,array('primary','autoinc','notnull')) ) $str .= "'{$k}'=>false,";
				}
				elseif ( $v === null )
				{
					$str .= "'{$k}'=>null, ";
				}
				elseif ( $k == 'length' and is_numeric($v) )
				{
					$str .= "'{$k}'=>$v, ";
				}
				else
				{
					$str .= "'{$k}'=>'{$v}', ";
				}
			}
			$str .= " ),\n";
		}
		$str .= "	),\n";
		$str .= "	'index'=>array(\n";
		foreach ( $schema['index'] as $key=>$field )
		{
			$tab = strlen($key) > 5 ? (strlen($key) > 9? "" : "	") : "		" ;
			$str .= "		'{$key}'{$tab} => ".preg_replace("/[\n\r]/s","",var_export($field,true)).",\n";
		}
		$str .= "	),\n";
		$str .= "	'unique'=>array(\n";
		foreach ( $schema['unique'] as $key=>$field )
		{
			$tab = strlen($key) > 5 ? (strlen($key) > 9? "" : "	") : "		" ;
			$str .= "		'{$key}'{$tab} => ".preg_replace("/[\n\r]/s","",var_export($field,true)).",\n";
		}
		$str .= "	),\n";
		$str .= "	'primary'=>".preg_replace("/[\n\r]/s","",var_export($schema['primary'],true)).",\n";
		$str .= "	'comment'=>".preg_replace("/[\n\r]/s","",var_export($schema['comment'],true))." \n";
		$str .= ")";

		$str = str_replace('0 => ','',$str);
		$str = str_replace('1 => ','',$str);
		$str = str_replace('2 => ','',$str);
		$str = str_replace('3 => ','',$str);
		$str = str_replace('4 => ','',$str);
		$str = str_replace('5 => ','',$str);
		$str = str_replace(',   ',',',$str);
		$str = str_replace(',  )',' )',$str);
		$str = str_replace(',)',' )',$str);
		$str = str_replace('array (  ','array ( ',$str);
		$str = str_replace('array (   ','array ( ',$str);

		// 替换comment为可翻译
		//$str = preg_replace("/'comment'\s+=>\s+'(.*?)'/","'comment' => t('$1')",$str);
		$str = preg_replace("/'comment'=>'(.*?)'/","'comment' => t('$1')",$str);

		return $str;
	}



	/**
	 * 创建表的sql语句，含创建字段，创建索引等
	 *
     * @access public
     * @param string $schema  表描述数据数组
     * @return bool
	 *
	 */
	abstract public function createTableSql($schema, $table);



	/**
	 * 判断表是否存在
	 *
     * @access public
     * @param string $table  表名称，不含前缀
     * @return bool
	 */
	abstract public function exists($table='');


	/**
	 * 创建数据表,返回表是否存在
	 *
	 * @code
	 *   $table->create(array(
	 *		'fields' => array(
	 *			'id'        => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE),
	 *			'vid'       => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE,'default' => 0),
	 *			'type'      => array('type' => 'char','length' => 32,'notnull' => TRUE, 'default' => ''),
	 *			'language'  => array('type' => 'char','length' => 12,'notnull' => TRUE,'default' => ''),
	 *			'title'     => array('type' => 'varchar','length' => 255,'notnull' => TRUE, 'default' => ''),
	 *			'uid'       => array('type' => 'int', 'notnull' => TRUE, 'default' => 0),
	 *			'nid'      => array('type' => 'tinyint', 'unsigned' => TRUE, 'notnull' => TRUE, 'default' => 0),
	 *		),
	 *		'index' => array(
	 *			'uid'		=> array('uid'),
	 *			'nid'	=> array('vid','nid'),
	 *			'types'	=> array(array('type', 4)),
	 *			'title_type'  => array('title', array('type', 4)),
	 *		),
	 *		'unique' => array(
	 *			'vid' => array('vid'),
	 *		),
	 *		'foreign' => array(
	 *			'uid' => array('users' => 'uid'),
	 *		),
	 *		'primary' => array('id'),
	 *		'comment' => 'The description for table.',
	 *   ));
	 * @endcode
	 *
     * @access public
     * @param string $schema  表描述数据数组
     * @return bool
	 *
	 */
	public function create($schema, $table='')
	{
		// 表已经存在则创建失败
		if ( $this->exists($table) ) return false;

		try
		{
			// 获取表的创建语句
			$sqls = $this->createTableSql($schema, $table);

			foreach( $sqls as $sql )
			{
				$this->db->execute($sql);
			}

			return true;			
		}
		catch (Exception $e)
		{
			$this->drop($table);
		}

		return false;
	}

	/**
	 * 重命名数据表
	 */
	abstract public function rename($newtable, $table='');

	/**
	 * 删除数据表
	 */
	abstract public function drop($table='');


	/**
	 * 清空数据表
	 */
	abstract public function clear($table='');


	/**
	 * 设置或者获取表的comment属性
	 *
	 */
	public function comment($comment='', $table='')
	{
		return true;
	}


	/**
	 * 优化数据表
	 */
	public function optimize()
	{
		return true;
	}

	/**
	 * 检查数据表
	 */
	public function check()
	{
		return true;
	}

	/**
	 * 修复数据表
	 */
	public function repair()
	{
		return true;
	}

	/**
	 * 获取主键
	 *
	 * @param $key 字段名称
	 */
	abstract public function primary();

	/**
	 * 设置主键
	 *
	 * @param $key 字段名称
	 */
	abstract public function addPrimary($fields, $table='');

	/**
	 * 删除主键
	 *
	 * @param $key 字段名称
	 */
	abstract public function dropPrimary($table='');



	/**
	 * 获取或者设置字段索性属性
	 *
	 * @param $key 字段名称
	 * @param $action 索引类型
	 */
	abstract public function indexes($table='');

	/**
	 * 索引是否存在
	 *
	 * @param $key 字段名称
	 * @param $action 索引类型
	 */
	abstract public function existsIndex($index, $table='');
	/**
	 * 删除索引
	 *
	 * addIndex('testttile','id')
	 * addIndex('testttile2',array('id','nid'))
	 * addIndex('testttile2',array('nid'),true) unique keys
	 *
	 */
	abstract public function addIndex($index, $fields, $unique=false, $table='');


	/**
	 * 删除索引
	 */
	abstract public function dropIndex($index, $table='');


	/**
	 * 获取字段信息
	 */
	abstract public function fields($table='');


	/**
	 * 字段是否存在
	 */
	abstract public function existsField($column, $table='');

	/**
	 * 添加字段
	 */
	abstract public function addField($column, $field, $keys_new = array(), $table='');

	/**
	 * 修改字段属性
	 */
	abstract public function changeField($column, $field, $keys_new = array(), $table='');

	/**
	 * 删除字段属性
	 */
	abstract public function dropField($column, $table='');

}
