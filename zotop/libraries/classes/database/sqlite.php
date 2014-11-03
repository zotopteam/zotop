<?php defined('ZOTOP') or die('No direct script access.');
/**
 * Mysql 数据库驱动
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class database_sqlite extends database
{
    /**
     * 数据库类初始化
     *
     * @param array|string $config
     */
    public function __construct($config = array())
    {
		if ( !class_exists('PDO') )
		{
			throw new zotop_exception(t('Not support `%s`','PDO'));
		}

		//默认的数据库配置
        $default = array(
			'driver'	=> 'sqlite',
			'hostname'	=> ZOTOP_PATH_DATA,
			'database'	=> 'zotop.db3',
			'charset'	=> 'utf8',
			'prefix'	=> 'zotop_',
			'pconnect'	=> true
		);

		// 合并配置
		$this->config = array_merge($default, $config);

		if ( !is_array( $this->config['params'] ) )
		{
			$this->config['params'] = array();
		}

		// 持久链接
		if( $this->config['pconnect'] === true )
		{
			$this->config['params'][PDO::ATTR_PERSISTENT] = true;
		}

		// 数据库文件
		$this->database = trim($this->config['hostname'],DS).DS.$this->config['database'];
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
		if ( !isset($this->connect) )
	    {
			//debug::dump($this->database);

			//找不到数据库文件则报错
			if ( !file_exists($this->database) )
			{
				throw new zotop_exception(t('Cannot connect database `%s`',$this->database));
			}

			//创建数据库连接
			try
			{
				// PDO 连接SQLITE
				$this->connect = new PDO('sqlite:'.$this->database, $this->config['username'], $this->config['password'], $this->config['params']);
				$this->connect->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
				$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//设置字符集
				if ( $charset = $this->config['charset'] )
				{
					$this->connect->query('PRAGMA encoding = '.$this->escape($charset));
				}
			}
			catch(PDOException $e)
			{
				throw new zotop_exception($e->getMessage());
			}
        }

        return $this->connect;
	}

    /**
     * 关闭数据库
     *
     * @return void
     */
    public function close()
	{
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

			try
			{
				//记录查询次数
				n('db',1);
				
				// 执行查询
				$this->query = $this->connect()->prepare($sql);
				$this->query->execute();

				return $this->query;
			}
			catch(PDOException $e)
			{
				if ( !$silent OR ZOTOP_DEBUG )
				{
					throw new zotop_exception(t('Database error: %s <br/> Sql: %s', $e->errorInfo[2], $sql));
				}

				return false;
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
		if( false !== $result = $this->query($sql,$silent) )
		{
			//返回受影响的行数
			$this->numRows = $this->query->rowCount();

			//返回lastInsertId
			if ( preg_match('/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i', $sql) )
			{
				$this->lastInsertID = $this->connect->lastInsertId();
			}

			return true;
		}

		return false;
	}


	/**
	 * 释放一个Query
	 */
	public function free()
	{
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
            $this->connect()->beginTransaction();
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
        if ( $this->transTimes > 0 )
		{
            $result = $this->connect->commit();
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
            $result = $this->connect->rollback();
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
		if ( $query = $this->query($sql) )
		{
			//返回数据集
			if ( $result =   $query->fetchAll(constant('PDO::FETCH_ASSOC')) )
			{
				//记录数据
				$this->numRows = count( $result );
				return $result;
			}
		}
		return array();
	}

	/**
	 * 从查询句柄提取一条记录
	 *
	 * @param $sql
	 * @return array
	 */
	public function getRow($sql='')
	{
		$this->limit(1);

		if ( $query = $this->query($sql) )
		{
			//返回数据集
			if ( $result =   $query->fetch(constant('PDO::FETCH_ASSOC')) )
			{
				//记录数据
				$this->numRows = count( $result );
				return $result;
			}
		}

		return false;
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
	 * @return mixed
	 */
	public function tables()
	{
		$tables = array();

		$results = $this->getAll("SELECT * FROM `sqlite_master` WHERE `type`='table' ORDER BY `name`;");

		foreach((array)$results as $table)
		{
			//过滤掉系统创建的表
			if ( substr($table['name'],0,7) == 'sqlite_'  ) continue;			

			if ( $this->config['prefix'] and substr($table['Name'],0,strlen($this->config['prefix'])) != $this->config['prefix'] ) continue;

			$id = substr($table['Name'],strlen($this->config['prefix']));

			$tables[$id] = array(
				'name' 			=> $table['name'],
				'size' 			=> false,
				'datalength' 	=> false,
				'indexlength' 	=> false,
				'rows' 			=> false,
				'engine' 		=> false,
				'collation' 	=> false,
				'createtime' 	=> false,
				'updatetime' 	=> false,
				'comment' 		=> false,
			);
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
		return @file_exists($this->database);
	}

	/*
	 * 创建一个新的数据库
	 *
	 * @return mixed
	 */
	public function create()
	{
		if ( !file_exists($this->database) )
		{
			try
			{
				// PDO 连接SQLITE数据库
				$connect=new PDO('sqlite:'.$this->database, $this->config['username'], $this->config['password'], $this->config['params']);
				$connect->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// 设置数据库字符集
				if ( $charset = $this->config['charset'] )
				{
					$connect->query('PRAGMA encoding = '.$this->escape($charset));
				}

				return true;
			}
			catch(PDOException $e)
			{
				throw new zotop_exception($e->getMessage());
			}
		}

		return false;
	}

	/**
	 * 删除一个已经存在的数据库
	 *
	 * @return mixed
	 */
	public function drop()
	{
		if ( $this->exists() )
		{
			return @unlink($this->database);
		}

		throw new zotop_exception(t('Cannot connect database `%s`',$this->database));
	}



    /**
     * SQL指令安全过滤
	 *
     *
     * @param string $str  SQL字符串
     * @return string
     */
	public function escape($str)
	{
 		if (function_exists('sqlite_escape_string'))
		{
			return sqlite_escape_string($str);
		}

		return str_replace("'", "''", $str);
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
	public function error()
	{
		if( $this->query )
		{
			$error = $this->query->errorInfo();

            return $error[2];
        }

        return '';
	}

    /**
     * 数据库错误行数
	 *
     * @return string
     */
	public function errno()
	{

	}

	/**
	 * 返回数据库的大小
	 * @return int
	 */
	public function size()
	{
		return @filesize($this->database);
	}

	/**
	 * 返回数据库的版本
	 *
	 * @return string
	 */
	public function version()
	{
		return $this->connect()->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
	}
}
?>