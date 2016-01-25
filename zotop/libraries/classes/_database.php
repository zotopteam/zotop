<?php defined('ZOTOP') or die('No direct script access.');
/**
 * 数据库操作
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
abstract class database
{
    public $config = array(); //数据库配置
	public $database; // 数据库

    protected $connect = null; //当期数据库链接
    protected $sql = null; //查询语句容器
    protected $sqlBuilder = array(); //查询语句构建容器
    protected $query = null; //查询对象
    protected $numRows = 0; //影响的数据条数
    protected $lastInsertID	= null;// 最后插入ID
    protected $transTimes = 0;// 事务指令数
	protected $error = ''; //错误
	protected $selectSql = 'SELECT%DISTINCT% %FIELDS% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT%';

	/**
	 * 数据库实例
	 *
	 *     $db = database::instance($config);
	 *
	 * @param   array   数据库配置项
	 * @return  object
	 */
	public function &instance(array $config)
	{
		static $instances = array();

        //数据库实例的唯一编号
        $id = md5(serialize($config));

        if ( !isset($instances[$id]) )
        {
            if ( empty($config['driver']) )
            {
               throw new zotop_exception(t('Error database config', $config));
            }

            //数据库驱动程序
            $driver = 'database_'.strtolower($config['driver']);

            //加载驱动程序
            if ( !zotop::autoload($driver) )
            {
				throw new zotop_exception(t('Cannot find database driver "%s"',$driver));
            }

            //取得驱动实例
            $instance = new $driver($config);

            //存储实例
            $instances[$id] = &$instance;
        }

        return $instances[$id];
	}

    /**
     * 解析并记录sql语句
	 *
	 * @param string $sql sql语句
     * @return string
     */
    public function parseSql($sql)
    {
        $sql = $this->compileSql($sql);

		if( is_string($sql) and !empty($sql) )
        {
            $this->sql = $sql;
        }

		$this->reset();

        return $sql;
    }

    /**
     * 生成查询语句
     *
	 * <code>
	 *
	 * </code>
	 *
	 * @param array $sql sql数组
     * @return string
     */
	public function compileSql($sql)
    {
        if ( is_array($sql) OR empty($sql) )
        {
            $sqlBuilder = empty($sql) ? $this->sqlBuilder : array_merge($this->sqlBuilder, $sql);

 			$sql = str_replace(
				array('%TABLE%','%DISTINCT%','%FIELDS%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%'),
				array(
					$this->parseFrom($sqlBuilder['from']),
					$this->parseDistinct($sqlBuilder['distinct']),
					$this->parseSelect($sqlBuilder['select']),
					$this->parseJoin($sqlBuilder['join']),
					$this->parseWhere($sqlBuilder['where']),
					$this->parseGroupBy($sqlBuilder['groupby']),
					$this->parseHaving($sqlBuilder['having']),
					$this->parseOrderBy($sqlBuilder['orderby']),
					$this->parseLimit($sqlBuilder['limit'], $sqlBuilder['offset']),
				),
				$this->selectSql
			);
        }

        return $sql;
    }

    /**
     * 获取全部查询语句
     *
     * @return array
     */
    public function sql()
    {
        return $this->sql;
    }

    /**
     * 对字符串进行安全处理
     *
     * @return string
     */
    public function escape($str)
    {
        return addslashes($str);
    }

	/**
	 * escape 数据表名称
	 *
	 * @param   mixed   value to escape
	 * @return  string
	 */
	public function escapeTable($table)
	{
		$table = substr($table,0,strlen($this->config['prefix'])) == $this->config['prefix'] ?  $table :  $this->config['prefix'].$table;

		if ( stripos($table, ' AS ') !== FALSE )
		{
			$table = str_ireplace(' as ', ' AS ', $table);
			$table = array_map(array($this, __FUNCTION__), explode(' AS ', $table));
			return implode(' AS ', $table);
		}
		return '`'.str_replace('.', '`.`', $table).'`';
	}

    /**
     * 对字段名称进行安全处理
     *
     * @return string
     */
	public function escapeColumn($field)
	{
		if ( $field=='*' )
		{
			return $field;
		}

	    if ( preg_match('/(avg|count|sum|max|min)\(\s*(.*)\s*\)(\s*as\s*(.+)?)?/i', $field, $matches))
		{
		    if ( count($matches) == 3)
			{
				return $matches[1].'('.$this->escapeColumn($matches[2]).')';
			}
			else if ( count($matches) == 5)
			{
				return $matches[1].'('.$this->escapeColumn($matches[2]).') AS '.$this->escapeColumn($matches[4]);
			}
		}

		if ( strpos($field,'.') !==false )
		{
			$field = $this->config['prefix'].$field;

			$field = str_replace('.', '`.`', $field);

		}

		if ( stripos($field,' as ') !==false )
		{
			$field = str_replace(' as ', '` AS `', $field);
		}

		$field =  '`'.$field.'`';
		$field = str_replace('`*`', '*', $field);
		return $field;
	}

	/**
	 * 对输入的值进行处理
	 *
	 * @param   mixed   value to escape
	 * @return  string
	 */
	public function escapeValue($value)
	{
	    switch (gettype($value))
		{
			case 'string':
				$value = '\''.$this->escape($value).'\'';
			    break;
			case 'boolean':
				//$value = (int) $value;
				$value = ($value === FALSE) ? 0 : 1;
			    break;
			case 'double':
				$value = sprintf('%F', $value);
			    break;
			case 'array':
				//序列化数据
				$value = $this->escapeValue(serialize($value));
				break;
			default:
				$value = ($value === NULL) ? 'NULL' : $value;
			    break;
		}

		return (string) $value;
	}

    /**
    * sql语句构建
    *
    * @return array
    */
    public function sqlBuilder($key='', $value=null)
    {
		if ( empty($key) ) return $this->sqlBuilder;

		if ( is_array($key) )
        {
            $this->sqlBuilder = array_merge($this->sqlBuilder , $key);

            return $this->sqlBuilder;
        }

        if ( isset($value) )
        {
            $this->sqlBuilder[$key] = $value;

            return $this->sqlBuilder;
        }

        return isset($this->sqlBuilder[$key])? $this->sqlBuilder[$key] : false;
    }

    /**
     * 重置链式查询的全部参数，参与链式查询
	 *
     * @return object 
     */
    public function reset()
    {
        $this->sqlBuilder = array();
		return $this;
    }

	/**
	 * 链式查询，设置读取的字段 “SELECT ...”
	 *
	 *
	 * <code>
	 * $this->field('id','username','password');
	 * $this->field('id,username,password');
	 * $this->field('*');
	 * </code>
	 *
	 * @param  mixed  $fields 要选取的字段，默认为选取全部字段
	 * @param   ...
	 * @return  $this
	 */
	public function select($fields = '*')
	{
	    if ( func_num_args() > 1 )
		{
		    $fields = func_get_args(); //select('id','username','password')
		}
		elseif ( is_string($fields) )
		{
		    $fields = explode(',', $fields); //select('id,username,password')
		}

		$this->sqlBuilder['select'] = (array) $fields;

		return $this;
	}

	/**
	 * 链式查询，设置查询数据表 "FROM ..."
	 * <code>
	 * $this->table('user');
	 * $this->table('user','config');
	 * $this->table('user,config');
	 * $this->table('user as u, config as c');
	 * </code>
	 *
	 * @param   mixed  table name
	 * @param   ...
	 * @return  $this
	 */
	public function from($tables='')
	{
	    if ( !empty($tables) and $tables !== false and $tables !== true )
	    {
    	    if (  func_num_args()>1 )
    		{
    		    $tables = func_get_args(); //from('user','config')
    		}
    		elseif ( is_string($tables) )
    		{

    		    $tables = explode(',', $tables); //from('user as u,config as c')
    		}

    		$this->sqlBuilder['from'] = (array)$tables;
	    }
		return $this;
	}

	/**
	 * 链式查询，设置数据，用于‘insert’ 或者 ‘update’ "SET... VALUES ..."
	 *
	 * <code>
	 * data('status',1) //设置‘status’为‘1’
	 * data(array('status'=>1,'updatetime'=>'2010-09-23 21:45:30')) //设置多个数据
	 * data('num',array('num','-',1)) 或者 set(array('num' => array('num','+',1))) //支持运算
	 * </code>
	 *
	 * @param   mixed  name 字段名称或者数组数据
	 * @param   mixed  value 字段值或者字段运算
	 * @return  $this
	 *
	 */
	public function data($name='', $value='')
	{
		if ( $name === '' ) return $this->sqlBuilder['data'];

		//清空全部的where条件
		if ( $name === null )
		{
			$this->sqlBuilder['data'] = array();
		}
		elseif ( is_string($name) )
		{
			$data = array($name=>$value);
		}
		elseif ( is_array($name) )
		{
			$data = $name;
		}

		if ( is_array($data) )
		{
			$this->sqlBuilder['data'] = array_merge((array)$this->sqlBuilder['data'], $data);
		}
	    return $this;
	}

	/* 链式查询：设置查询条件 "WHERE ..."
	 *
	 * <code>
	 * where('id',1) //获取‘id’ 为 ‘1’的 数据
	 * where('status','<',1) //获取‘status’ 小于 ‘1’的 数据
	 * where(array('id','<',1),'and',array('status','>',1)) //获取‘id’小于‘1’并且‘status’ 大于 ‘1’的 数据
	 * where(array('id','like',1),'and',array(array('id','>',1),'or',array('id','>',1))) //复杂查询
	 *
	 * 标准的where数组为 array('字段名称','条件','值')
	 * </code>
	 *
	 * @param   mixed  key 字段名称
	 * @param   mixed  value 字段值
	 * @return  $this
	 */

	public function where($key='', $value=null)
	{
		if ( $key === '' ) return $this->sqlBuilder['where'];

		//清空全部的where条件
		if ( $key === null )
		{
			$this->sqlBuilder['where'] = array();
		}
		elseif ( is_string($key) )
		{
		    $where = func_get_args();

			switch ( count($where) )
			{
				case 2:
					$where = array($where[0],'=',$where[1]); //where('id',1)  => array('id','=',1)
					break;
				case 3:
					$where = array($where[0],$where[1],$where[2]); //where('id','<',1)  => array('id','<',1)
					break;
			}
		}
		elseif ( is_array($key) )
		{
			$where = (func_num_args() == 1) ? $key : func_get_args(); //where(array('id','=','1'),'and',array('status','>','0'))
		}

		if ( is_array($where) AND !empty($where) )
		{
    		// where(…)->where(…) 级联时默认为and
			if ( count($this->sqlBuilder['where']) >0 )
    		{
    			$this->sqlBuilder['where'][] = 'AND';
    		}
    		$this->sqlBuilder['where'][] = $where;
		}

		return $this;
	}

	/**
	 * 链式查询，设置查询排序  "ORDER BY ..."
	 *
	 * <code>
	 * orderby('creattime','desc')
	 * orderby('creattime desc')
	 * orderby('creattime desc,updatetime asc')
	 * orderby(array('creattime'=>'desc','updatetime'=>'asc'))
	 * </code>
	 *
	 * @param   string  orderby 排序字段
	 * @param   string  direction 排序 asc 或者 desc
	 * @return  $this
	 */
	public function orderby($orderby, $direction=null)
	{
		if ( $orderby === null )
		{
			$this->sqlBuilder['orderby'] = array(); //清除排序
		}
		elseif ( is_array($orderby) )
		{
			$this->sqlBuilder['orderby'] = array_merge((array)$this->sqlBuilder['orderby'], $orderby); //清除排序
		}
		elseif ( is_string($orderby) )
		{
			$orderby = trim($orderby);

			if ( strpos($orderby,',') === false )
			{
				if ( strpos($orderby,' ') === false )
				{
					$orders = array($orderby => $direction); //orderby('creattime','desc')
				}
				else
				{
					list($key, $dir) = explode(' ', trim($orderby)); //orderby('creattime desc')
					$orders[$key] = $dir;
				}
			}
			else
			{
				//orderby('order asc,creattime desc')

				$orderbys = explode(',', $orderby);

				$orders = array();

				foreach($orderbys as $order)
				{
					list($key, $dir) = explode(' ', trim($order));

					$orders[$key] = $dir;
				}
			}

			$this->sqlBuilder['orderby'] = array_merge((array)$this->sqlBuilder['orderby'], $orders);
		}

		return $this;
	}

	/**
	 * 链式查询，设置连接查询数据表 "JOIN ..."
	 *
	 * <code>
	 * from('content as c')->join('user as u', 'c.userid', 'u.id','LEFT')
	 * </code>
	 *
	 * @param   string  table 连接的表名称
	 * @param   string  key 连接的键设置
	 * @param   string  value 连接的外键键设置
	 * @param   string  type 连接类型，'LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER'
	 * @return  $this
	 */
	public function join($table, $key, $value, $type='')
	{
		if ( ! empty($type))
		{
			$type = strtoupper(trim($type));

			if ( ! in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER'), TRUE))
			{
				$type = '';
			}
			else
			{
				$type .= ' ';
			}
		}

		$join = array();

		$join['table'] = $table;
		$join['key'] = $key;
		$join['value'] = $value;
		$join['type'] = $type;

		$this->sqlBuilder['join'][] = $join;

		return $this;
	}

	/**
	 * 链式查询，设置查询范围， "LIMIT ..."
	 *
	 * @param   number  limit 条数限制
	 * @param   number  offset 起始位
	 * @return  $this
	 */
	public function limit($limit, $offset = 0)
	{
		$this->sqlBuilder['limit']  = (int) $limit;

		if ( $offset !== NULL || !is_int($this->sqlBuilder['offset']) )
		{
			$this->sqlBuilder['offset'] = (int) $offset;
		}

		return $this;
	}

	/**
	 * 链式查询，设置查询范围
	 *
	 * @param   number  offset 起始位
	 * @return  $this
	 */
	public function offset($value)
	{
		$this->sqlBuilder['offset'] = (int) $value;

		return $this;
	}

    /**
    * 查询构建器  FROM 解析
	*
    * @param mixed $tables 来源于sqlbuilder的数据表
    * @return string
    */
    public function parseFrom($tables)
    {
        $array = array();

        if ( is_string($tables) )
        {
            $tables = explode(',',$tables);
        }

        foreach ($tables as $key=>$table)
        {
			$array[] = $this->escapeTable($table);
        }

        return implode(',',$array);
    }

    /**
     * 查询语句构建器 DISTINCT 解析，生成"DISTINCT ..."
	 *
     * @param mixed $distinct
     * @return string
     */
    public function parseDistinct($distinct)
    {
        return empty($distinct) ? '' : ' DISTINCT ';
    }

    /**
     * 查询语句构建器 SELECT字段 解析
     *
     * @param mixed $fields
     * @return string
     */
    public function parseSelect($fields)
    {
          if ( !empty($fields) )
          {
              if ( is_string($fields) )
              {
                  $fields = explode('.',$fields);
              }

			  $array = array();

			  foreach($fields as $key=>$filed)
			  {
				$array[] = $this->escapeColumn($filed);
			  }

			  return implode(',',$array);
          }
          return '*';
    }

    /**
     * 查询语句构建器 JOIN 解析
     *
     * @param mixed $join
     * @return string
     */
    public function parseJoin($joins)
    {
        $str = '';

		if ( is_array($joins) )
		{
			foreach( $joins as $join )
			{
				if ( is_array($join) )
				{
					$str .= ' '.$join['type'].'JOIN '. $this->escapeTable($join['table']) .' ON '. $this->escapeColumn($join['key']) .' = '. $this->escapeColumn($join['value']);
				}
			}
		}

		return $str;
    }

   /**
     * 查询语句构建器 WHERE 解析
     *
     * @param array $where
     * @return string
     */
    public function parseWhere($where)
    {
        $str = '';
        if ( is_array($where) )
        {
            $str = $this->parseWhereArray($where);
        }
        else
        {
            $str = $where;
        }

        return empty($str) ? '' : ' WHERE '.$str;
    }

    /**
     * 查询语句构建器 WHERE 解析
     *
     * @param array $where
     * @return string
     */
    public function parseWhereArray($where)
    {
        if ( !empty($where) )
        {
            if ( is_string($where[0]) && count($where)==3 )
            {
				$where[1] = trim(strtoupper($where[1]));
				switch($where[1])
				{
					case '=':
					case '!=':
					case '<>':
					case '>':
					case '<':
					case '>=':
					case '<=':
					case 'IS':
					case 'IS NOT':
						return $this->escapeColumn($where[0]).' '.$where[1].' '.$this->escapeValue($where[2]);
						break;
					case 'BETWEEN':
					case 'IN':
					case 'NOT IN':
						//字符串默认被分解
						if ( is_string($where[2]) ) $where[2] = explode(',',$where[2]);

						if ( is_array($where[2]) )
						{
							$escaped = array();

							foreach( $where[2] as $v )
							{
								if (is_numeric($v))
								{
									$escaped[] = $v;
								}
								else
								{
									$escaped[] = $this->escapeValue($v);
								}
							}

							$where[2] = implode(",", $escaped);
						}

						return $this->escapeColumn($where[0]).' '.$where[1].' ('.$where[2].')';

						break;
					case 'LIKE':
					case '%LIKE%':
						return $this->escapeColumn($where[0]).' '.trim($where[1],'%').' '.$this->escapeValue('%'.trim($where[2],'%').'%');
						break;
					case 'LIKE%':
						return $this->escapeColumn($where[0]).' '.trim($where[1],'%').' '.$this->escapeValue(''.trim($where[2],'%').'%');
						break;
					case '%LIKE':
						return $this->escapeColumn($where[0]).' '.trim($where[1],'%').' '.$this->escapeValue('%'.trim($where[2],'%').'');
						break;
					default :
						//die("错误的SQL参数");
						return '';
				}
            }

			$str = '';

			for ( $i=0,$j=count($where); $i<$j ; $i++ )
			{
				if ( is_array($where[$i][0]) )
				{
					$str .= '('.$this->parseWhereArray($where[$i]).')';
				}
				elseif ( is_array($where[$i]) )
				{
					$str .= $this->parseWhereArray($where[$i]);
				}
				elseif ( is_string($where[$i]) )
				{
					$str .= ' '.strtoupper(trim($where[$i])).' ';
				}

			}
        }

        return $str;
    }

    /**
     * 查询语句构建器  GROUP BY 解析
	 *
     * @param string $group
     * @return string
     */
    public function parseGroupBy($group)
    {
        return empty($group) ? '' : ' GROUP BY '.$group;
    }

    /**
     * 查询语句构建器  HAVING 解析
	 *
     * @param string $having
     * @return string
     */
    public function parseHaving($having)
    {
        return empty($having)? '' : ' HAVING '.$having;
    }

	/**
     * 查询语句构建器  ORDER BY 解析
	 *
     * @param string $orderby
     * @return string
	 */
	public function parseOrderBy($orderby)
	{
		$str = '';

		if ( is_array($orderby) )
		{			
			foreach ( $orderby as $key=>$direction )
			{
				$direction = strtoupper(trim($direction));
				if ( !in_array($direction, array('ASC', 'DESC', 'RAND()', 'RANDOM()', 'NULL')) )
				{
					$direction = 'ASC';
				}
				$str .= ','.$this->escapeColumn($key).' '.$direction;
			}
		}
		else
		{
			$str = $orderby;
		}

		$str = trim($str,',');

		return empty($str) ? '' : ' ORDER BY '.$str;
	}

    /**
     * 查询语句构建器  LIMIT 解析
	 *
	 * @param $offset int 起始位置
	 * @param  $limit  int 条数限制
	 * @return string
	 *
     */
    public function parseLimit($limit, $offset=0)
	{
		$str = '';

		if( is_int($offset) )
		{
			$str .= (int)$offset.',';
		}
		if( is_int($limit) )
		{
		    $str .= $limit;
		}
		return empty($str) ? '' : ' LIMIT '.$str;
    }

    /**
     * 查询语句构建器 DATA 解析
	 *
	 * @param $offset int 起始位置
	 * @param  $limit  int 条数限制
	 * @return string
	 *
     */
    public function parseData($data)
    {
        $str = '';

        foreach((array)$data as $key=>$val)
        {
            //解析值中的如：num = array('num','+',1) 或者array('num','-',1)
			if ( is_array($val) && count($val)==3 && in_array($val[1],array('+','-','*','%')) && is_numeric($val[2]) )
			{
				 $str .= ','.$this->escapeColumn($key).' = '.$this->escapeColumn($val[0]).$val[1].(int)$val[2];
			}
			else
			{
				$str .= ','.$this->escapeColumn($key).' = '.$this->escapeValue($val);
			}

        }

        $str = trim($str,',');

        return empty($str) ? '' : ' SET '.$str;
    }


    /**
     * 插入记录
     *
	 * @code
	 *
	 * //不使用链式查询
	 * $db->insert('user',array(),false)
	 *
	 * // 使用链式查询
	 * $db->table('content')->data(array())->insert(true)
	 * @endcode
     *
	 * @param string $table 数据表
     * @param array $data 数据
     * @param boolean $replace 是否replace
     * @return false | integer
     */
    public function insert($table='', $data=array(), $replace=false)
	{
		if ( $table === true or $table === false ) $replace = $table;

        //设置查询
        $this->table($table)->data($data);

		$table = $this->sqlBuilder['from'];
		$data = $this->sqlBuilder['data'];

        // 没有插入的数据或者插入表
		if ( !is_array($data) or empty($table) ) return false;

        //处理插入数据
        foreach( $data as $field=>$value )
        {
            $fields[] = $this->escapeColumn($field);
            $values[] = $this->escapeValue($value);
        }

        //sql
        $sql = ($replace ? 'REPLACE' : 'INSERT').' INTO %TABLE% (%FIELDS%) VALUES (%VALUES%)';
        $sql = str_replace(
            array('%TABLE%','%FIELDS%','%VALUES%'),
            array(
				$this->parseFrom($table),
				implode(',', $fields),
				implode(',', $values)
            ),
            $sql
        );

		if ( $this->execute($sql) )
		{
			return $this->lastInsertID ? $this->lastInsertID : true;
		}

        return false;
    }

    /**
     * 数据更新，UPDATE
     *
	 * <code>
	 * $db->update('user',array())
	 * $db->table('user')->data(array())->where('id','=',1)->update()
	 * </code>
	 *
	 * @param string $table 数据表
	 * @param array $data 更新的数据
	 * @param array $data 更新的条件
     * @return bool
     */
    public function update($table='', $data=array(), $where=array())
    {
		//设置查询
        $this->table($table)->data($data)->where($where);

        //必须设置更新条件
        if( empty($this->sqlBuilder['where']) OR empty($this->sqlBuilder['from']) ) return false;

		//未更新任何数据
		if ( !is_array($this->sqlBuilder['data']) ) return true;

        //sql
        $sql = 'UPDATE %TABLE%%SET%%WHERE%';
        $sql = str_replace(
            array('%TABLE%','%SET%','%WHERE%'),
            array(
				$this->parseFrom($this->sqlBuilder['from']),
				$this->parseData($this->sqlBuilder['data']),
				$this->parseWhere($this->sqlBuilder['where']),
            ),
            $sql
        );

        return $this->execute($sql);
    }

    /**
     * 数据删除，DELETE
     *
	 * <code>
	 * delete('user',array())
	 * from('user')->where('id','=',1)->delete()
	 * </code>
	 *
	 * @param string $table 数据表
	 * @param array $data 更新的数据
	 * @param array $data 更新的条件
     * @return bool
     */
    public function delete($table='', $where=array())
    {
        //设置查询
        $this->table($table)->where($where);

        //必须设置删除条件
        if( empty($this->sqlBuilder['where']) )
        {
            return false;
        }

        $sql = 'DELETE FROM %TABLE%%WHERE%';
        $sql = str_replace(
            array('%TABLE%','%WHERE%'),
            array(
				$this->parseFrom($this->sqlBuilder['from']),
				$this->parseWhere($this->sqlBuilder['where']),
            ),
            $sql
        );

        //返回查询结果
        return $this->execute($sql);
    }

	/**
	 * 返回limit限制的数据,用于带分页的查询数据
	 *
	 * @param $page int 页码
	 * @param $pagesize int 每页显示条数
	 * @param $num int|bool 总条数|缓存查询条数，$toal = (false||0) 不缓存查询
	 * @return mixed
	 */
	public function getPage($page=0, $pagesize=10, $total = false)
	{

		$page = (int)$page <=0 ? (int)$_GET['page'] : $page;
		$page = (int)$page <=0 ? 1 : $page;

		$pagesize = intval($pagesize) > 0 ? intval($pagesize) : 10;

		$sqlBuilder = $this->sqlBuilder;

		if ( !is_int($total) or $total <=0 )
		{
			$hash = md5(serialize($sqlBuilder['where']));

			if ( $page == 1 or !is_numeric(zotop::cookie($hash)))
			{
				//获取符合条件数据条数
				$total = $this->reset()->table($sqlBuilder['from'])->where($sqlBuilder['where'])->count();

				zotop::cookie($hash,$total);
			}
			else
			{
				$total = (int)zotop::cookie($hash);
			}

		}

		// 上一次执行的时候会清空$sqlBuilder，所以必须重新赋值
		$this->sqlBuilder($sqlBuilder);

		// 计算$offset
		$offset = intval($page) > 0 ? (intval($page)-1)*intval($pagesize) : 0;

		//获取指定条件的数据
		$data = $this->limit($pagesize, $offset)->select();

		// 获取分页信息
		$totalpage = @ceil($total / $pagesize);
		$prevpage =  $page-1 > 0 ? $page-1 : 1;
		$nextpage =  $page+1 < $totalpage ? $page+1 : $totalpage;

		return array(
			'data' => $data, //返回数据
			'total' => $total, //总条数
			'page' => $page, //当前页码
			'pagesize' => $pagesize, //每页显示
			'firstpage' => 1, //首页页码
			'lastpage' => $totalpage, //最后一页页码
			'prevpage' => $prevpage, //下一页页码
			'nextpage' => $nextpage, //下一页页码
			'totalpage'=> $totalpage, //总页数
		);
	}

    /**
     * 魔术方法
	 *
     * @param string $method 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
    public function __call($method, $args)
	{
        if( in_array(strtolower($method), array('distinct','having','group','lock'), true))
		{
            // 未定义的连贯操作的实现
            $this->sqlBuilder[strtolower($method)] =   $args[0];
            return $this;
		}
		elseif ( in_array(strtolower($method), array('count','sum','min','max','avg'), true) )
		{
			// 实现 'count','sum','min','max','avg' 方法
			// from('user')->where('status','=',1)->count();
			// from('user')->sum('hits');
			$field =  isset($args[0]) ? $args[0] : '*';
			$result = $this->select(strtoupper($method).'('.$field.') AS zotop_'.$method)->orderby(null)->getField();
			return is_numeric($result) ? $result : 0;
		}
		elseif ( in_array(strtolower($method), array('exists','create','drop','size','version','connect','close','query','free','execute','begin','commit','rollback'), true) )
		{
			throw new zotop_exception(t('Database method [ %s ] must be extend', $method));
		}


		throw new zotop_exception(t('Database method [ %s ] not exists', $method));
	}


	/**
	 * 实例化数据表结构，用于数据表的结构处理
	 *
	 *
	 * @param string $table 不含前缀的数据表名称
	 * @return object
	 */
	public function schema($table)
	{
		$driver = "database_schema_{$this->config['driver']}";

		if ( zotop::autoload($driver) )
		{
			return new $driver($this, $table);
		}

		throw new zotop_exception(t('Cannot find database schema driver "%s"',$driver));
	}

	/**
	 * 监测并记录程序
	 * 
	 * @param  boolean $start 开始或者结束
	 * @return null
	 */
	public function profile($start='')
	{
		if ( $start )
		{
			zotop::profile('db_query_start');
			return true;                                       
		}

		zotop::profile('db_query_end');
		zotop::counter('db',1);

		$info = $this->sql.' ( '.zotop::profile('db_query_start','db_query_end').' )';
		zotop::trace('SQL',$info);
		return $info;
	}
}
?>