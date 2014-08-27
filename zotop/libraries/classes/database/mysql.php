<?php defined('ZOTOP') or die('No direct script access.');
/**
 * Mysql 数据库驱动
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class database_mysql extends database
{
    /**
     * 数据库类初始化
     *
     * @param array|string $config
     */
    public function __construct($config = array())
    {
		if ( !extension_loaded('mysql') )
		{
			throw new zotop_exception(t('Not support `%s`','mysql'));
		}

        $default = array(
			//'driver' => 'mysql',
			'username' => 'root',
			'password' => '',
			'hostname' => 'localhost',
			'hostport' => '3306',
			'database' => 'zotop',
			'charset' => 'utf8',
			'prefix'=>'zotop_',
			'pconnect' => false
        );

		$this->config = array_merge($default, $config);
		$this->database = $config['hostname'].':'.$config['hostport'].'/'.$config['database'];
    }

	/**
	 * 数据库类对象销毁
	 */
	public function __destruct()
	{
		$this->free();	//释放查询结果
		$this->close(); //关闭连接
	}

	/**
	 * 数据库连接，创建数据库连接对象
	 */
	public function connect()
	{
		if ( !is_resource($this->connect) )
	    {

			//是否持久连接
			$connect = ( $this->config['pconnect'] == TRUE ) ? 'mysql_pconnect' : 'mysql_connect';

			//创建数据库连接
			if ( $this->connect = @$connect($this->config['hostname'].':'.$this->config['hostport'], $this->config['username'], $this->config['password'], true) )
			{

				if ( @mysql_select_db($this->config['database'], $this->connect) )
				{
					$version = $this->version();

					if ( $version > '4.1' && $charset = $this->config['charset'] )
					{
						@mysql_query("SET NAMES '".$charset."'" , $this->connect);//使用UTF8存取数据库 需要mysql 4.1.0以上支持
					}

					if ($version > '5.0.1')
					{
						@mysql_query("SET sql_mode=''",$this->connect);//设置 sql_model
					}
				}
				else
				{
					throw new zotop_exception(t('Cannot use database `%s`',$this->config['database']));
				}
			}
			else
			{
				throw new zotop_exception(t('Cannot connect database `%s`',$this->config['hostname'].':'.$this->config['hostport']));
			}
        }

        return $this->connect;
	}

    /**
     * 关闭数据库
     * @access public
     * @return void
     */
    public function close()
	{
        if ( $this->connect )
		{
            mysql_close($this->connect);
        }
        $this->connect = null;
    }

	/**
	 * 执行一个sql语句 ，等价于 mysql_query
	 *
	 * @param $sql 查询语句
	 * @param $silent 不显示错误
	 * @return object||bool
	 */
	public function query($sql, $silent=false)
    {
		if ( $sql = $this->parseSql($sql) )
		{
			//释放前次的查询结果
			if ( $this->query ) $this->free();

			//查询数据
			if ( $this->query = @mysql_query($sql, $this->connect()) )
			{
				//记录查询次数
				n('db',1);

				//查询影响记录数
				$this->numRows = @mysql_num_rows($this->query);

				return $this->query;
			}
			elseif ( !$silent OR ZOTOP_DEBUG )
			{
				throw new zotop_exception(t('Database error: %s <br/> Sql: %s', @mysql_error(), $sql));
			}
		}

		return false;
	}

	/**
	 * 执行一个sql语句，如：update，insert，delete
	 * 当使用 UPDATE 查询，MySQL 不会将原值与新值一样的列更新。这样使得 mysql_affected_rows() 函数返回值不一定就是查询条件所符合的记录数，只有真正被修改的记录数才会被返回
	 *
	 * @param $sql
	 * @param $silent
	 * @return bool||number
	 */
	public function execute($sql, $silent=false)
	{
		if( false !== $this->query($sql,$silent) )
		{
			//返回受影响的行数
			$this->numRows = mysql_affected_rows($this->connect);

			//返回最后插入的ID
			$this->lastInsertID = mysql_insert_id($this->connect);

			return true;
		}

		return false;
	}


	/**
	 * 释放一个Query
	 */
	public function free()
	{
		if ( $this->query )
		{
			@mysql_free_result($this->query);
		}

        $this->query = null;
	}

    /**
     * 启动事务
     *
     * @return void
     */
    public function begin()
	{
        //数据rollback 支持
        if ($this->transTimes == 0)
		{
            @mysql_query('START TRANSACTION', $this->connect());
        }
        $this->transTimes++;
        return ;
    }

    /**
     * 用于非自动提交状态下面的查询提交
     *
     * @return boolen
     */
    public function commit()
	{
        if ($this->transTimes > 0)
		{
            $result = @mysql_query('COMMIT', $this->connect());

			$this->transTimes = 0;

			if(!$result)
			{
                return false;
            }
        }
        return true;
    }

    /**
     * 事务回滚
     *
     * @return boolen
     */
    public function rollback()
	{
        if ($this->transTimes > 0)
		{
            $result = mysql_query('ROLLBACK', $this->connect());
            $this->transTimes = 0;
            if(!$result)
			{
                return false;
            }
        }
        return true;
    }

	/**
	 * 执行一个sql语句并返回结果数组
	 *
	 * @param $sql
	 * @return array
	 */
	public function getAll($sql='')
	{
		$result = array();

		if ( $query = $this->query($sql) )
		{
			if ( $this->numRows >0 )
			{
			    while( $row = mysql_fetch_assoc($query) )
			    {
					$result[] = $row;
				}

				mysql_data_seek($this->query,0);
			}
		}

		return $result;
	}

	/**
	 * 从查询句柄提取一条记录
	 *
	 * @param $sql
	 * @return array
	 */
	public function getRow($sql='')
	{
		$result = array();

		if ( $query = $this->limit(1)->query($sql) )
		{
			if( $row = mysql_fetch_assoc($query) )
			{
                return $row;
			}
		}

		return $result;
	}


	/**
	 * 从查询句柄提取一条记录，并返回该记录的第一个字段
	 *
	 * @param $sql
	 * @return mixed
	 */
	public function getField($sql='')
	{
		if( $row = $this->getRow($sql) )
	    {
	       return is_array($row) ? reset($row) : null;
	    }
	    return false;
	}

	/**
	 * 返回全部的数据表信息
	 *
	 * @param $refresh 是否强制刷新数据表
	 * @return mixed
	 */
	public function tables($refresh=false)
	{
		static $tables = array();

		if ( empty($tables) OR $refresh == true )
		{
			$results = $this->getAll('SHOW TABLE STATUS');

			if ( is_array($results) )
			{
				foreach($results as $table)
				{
					$id = substr($table['Name'],0,strlen($this->config['prefix'])) == $this->config['prefix'] ? substr($table['Name'],strlen($this->config['prefix'])) : $table['Name'];

					$tables[$id] = array(
						'name' => $table['Name'],
						'size' => $table['Data_length'] + $table['Index_length'],
						'datalength' => $table['Data_length'],
						'indexlength' => $table['Index_length'],
						'rows' => $table['Rows'],
						'engine' => $table['Engine'],
						'collation' => $table['Collation'],
						'createtime' => $table['Create_time'],
						'updatetime' => $table['Update_time'],
						'comment' => $table['Comment'],
					);
				}
			}
		}
		return $tables;
	}

	/*
	 * 判断数据库是否存在
	 *
	 * @return mixed
	 */
	public function exists()
	{
		if ( $connect = @mysql_connect($this->config['hostname'].':'.$this->config['hostport'], $this->config['username'], $this->config['password'], true) )
		{
			if ( @mysql_select_db($this->config['database'], $connect) )
			{
				return true;
			}

			return false;
		}

		throw new zotop_exception(t('Cannot connect database host `%s`',$this->config['hostname'].':'.$this->config['hostport']));
	}

	/*
	 * 创建一个新的数据库
	 *
	 * @return mixed
	 */
	public function create()
	{
		if ( $connect = @mysql_connect($this->config['hostname'].':'.$this->config['hostport'], $this->config['username'], $this->config['password'], true) )
		{
			if ( @mysql_select_db($this->config['database'], $connect) )
			{
				return false;
			}

			if ( @mysql_query("CREATE DATABASE IF NOT EXISTS `".$this->config['database']."` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;", $connect) )
			{
				return true;
			}

			return false;
		}

		throw new zotop_exception(t('Cannot connect database host `%s`',$this->config['hostname'].':'.$this->config['hostport']));
	}

	/**
	 * 删除一个已经存在的数据库
	 *
	 * @return mixed
	 */
	public function drop()
	{
		if ( $connect = @mysql_connect($this->config['hostname'].':'.$this->config['hostport'], $this->config['username'], $this->config['password'], true) )
		{
			if ( @mysql_query("DROP DATABASE IF EXISTS `".$this->config['database']."`;", $connect) )
			{
				return true;
			}

			return false;
		}

		throw new zotop_exception(t('Cannot connect database host `%s`',$this->config['hostname'].':'.$this->config['hostport']));
	}



    /**
     * SQL指令安全过滤
	 *
     * @access public
     * @param string $str  SQL字符串
     * @return string
     */
	public function escape($str)
	{
		if ( is_array($str) )
		{
			foreach ($str as $key => $val)
	   		{
				$str[$key] = $this->escape($val);
	   		}
	   		return $str;
		}

		if ( function_exists('mysql_real_escape_string') AND is_resource($this->connect) )
		{
			$str = mysql_real_escape_string($str, $this->connect);
		}
		elseif ( function_exists('mysql_escape_string') )
		{
			$str = mysql_escape_string($str);
		}
		else
		{
			$str = addslashes($str);
		}

		return $str;
	}



	/**
	 * 返回最后插入的id
	 * @return int
	 */
	public function lastInsertID()
	{
		return $this->lastInsertID;
	}

    /**
     * 数据库错误信息
	 *
     * @return string
     */
	public function error() {
		return (($this->connect) ? mysql_error($this->connect) : mysql_error());
	}

    /**
     * 数据库错误行数
	 *
     * @return string
     */
	public function errno() {
		return intval(($this->connect) ? mysql_errno($this->connect) : mysql_errno());
	}

	/**
	 * 返回数据库的大小
	 * @return int
	 */
	public function size()
	{
		$tables = $this->tables();

		foreach($tables as $table)
		{
			$size  +=  $table['size'];
		}

		return $size;
	}

	/**
	 * 返回数据库的版本
	 *
	 * @return string
	 */
	public function version()
	{
	    return @mysql_get_server_info($this->connect());
	}
}
?>