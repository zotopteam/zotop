<?php defined('ZOTOP') or die('No direct script access.');

/**
 * 数据库操作类基类，各种数据库的表操作类将从此类继承
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop.util
 * @author     zotop team
 * @license    http://zotop.com/license.html
 * 
 * @code
 *   
 *   // 数据库实例化
 *   $db = db::instance(array(
 *        'driver'    => 'mysql', //类型
 *        'hostname'  => 'localhost', //地址
 *        'hostport'  => '3306', //端口
 *        'username'  => 'root', //用户名
 *        'password'  => '',  //密码        
 *        'database'  => '', //数据库名称
 *        'charset'   => 'utf8', //编码
 *        'collation' => 'utf8_unicode_ci',
 *        'prefix'    => 'zotop_',// 数据库表前缀
 *        'pconnect'  => false,// 持久连接
 *        'params'    => array()
 *   ));
 *
 *   // 数据库是否存在
 *   $db->exists();
 *   
 *   // 数据库创建
 *   $db->create();
 *
 *   // 数据库删除
 *   $db->drop();
 * 
 * 
 *   // 创建数据表,返回表是否存在
 *   $db->createTable('testtable',array(
 *      'fields' => array(
 *          'id'        => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE),
 *          'vid'       => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE,'default' => 0),
 *          'type'      => array('type' => 'char','length' => 32,'notnull' => TRUE, 'default' => ''),
 *          'language'  => array('type' => 'char','length' => 12,'notnull' => TRUE,'default' => ''),
 *          'title'     => array('type' => 'varchar','length' => 255,'notnull' => TRUE, 'default' => ''),
 *          'uid'       => array('type' => 'int', 'notnull' => TRUE, 'default' => 0),
 *          'nid'       => array('type' => 'tinyint', 'unsigned' => TRUE, 'notnull' => TRUE, 'default' => 0),
 *      ),
 *      'index' => array(
 *          'uid'       => array('uid'),
 *          'nid'   => array('vid','nid'),
 *          'types' => array(array('type', 4)),
 *          'title_type'  => array('title', array('type', 4)),
 *      ),
 *      'unique' => array(
 *          'vid' => array('vid'),
 *      ),
 *      'foreign' => array(
 *          'uid' => array('users' => 'uid'),
 *      ),
 *      'primary' => array('id'),
 *      'comment' => 'The description for table.',
 *   ));
 *
 *   // 获取数据表主键
 *   $db->primary('testtable');
 *
 *   // 删除数据表主键
 *   $db->dropPrimary('testtable');
 *
 *   // 添加主键
 *   $db->addPrimary('testtable',array('id,vid'));
 *
 *   // 重命名数据包
 *   $db->renameTable('testtable','testtable2');
 *
 *   // 添加字段
 *   $db->addField('testtable',array('name'=>'order','type'=>'int','length'=>10,'default'=>0));
 *
 *   // 更改字段
 *   $db->changeField('testtable','title2',array('name'=>'title','type'=>'varchar','length'=>50));
 *
 *   // 删除字段
 *   $db->dropField('testtable','title2');
 *  
 * @endcode
*/

abstract class db
{
    // PDO操作实例
    protected $PDOStatement = null; 
    //当期数据库链接
    protected $connect      = null;
    //查询语句容器
    protected $sql          = null;
    //查询语句构建容器
    protected $sqlBuilder   = array();
    //查询对象
    protected $query        = null;
    // 参数绑定
    protected $bind         =   array();    
     //影响的数据条数
    protected $numRows      = 0;
    // 最后插入ID
    protected $lastInsertID = null;
    // 事务指令数
    protected $transTimes   = 0;
    //错误
    protected $error        = '';
    //查询语句
    protected $selectSql    = 'SELECT%DISTINCT% %FIELDS% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT%';

    // PDO连接参数
    protected $params       = array(
        PDO::ATTR_CASE              =>  PDO::CASE_LOWER, //强制列名小写
        PDO::ATTR_ERRMODE           =>  PDO::ERRMODE_EXCEPTION, //错误时抛出 exceptions 异常
        PDO::ATTR_ORACLE_NULLS      =>  PDO::NULL_NATURAL,  //不转换 NULL 和空字符串
        PDO::ATTR_STRINGIFY_FETCHES =>  false, //提取的时候将数值转换为字符串
    );  

    //默认数据库配置
    public $config          = array(
        'driver'    => 'mysql', //类型
        'hostname'  => 'localhost', //地址
        'hostport'  => '3306', //端口
        'username'  => 'root', //用户名
        'password'  => '',  //密码        
        'database'  => '', //数据库名称
        'charset'   => 'utf8', //编码
        'collation' => 'utf8_unicode_ci',
        'prefix'    => 'zotop_',// 数据库表前缀
        'pconnect'  => false,// 持久连接
        'params'    => array()
    );

    // 数据库
    public $database;

    /**
     * 数据库实例
     *
     *     $db = db::instance($config);
     *
     * @param   mixed   数据库配置项
     * @return  object
     */
    public static function instance($config=array())
    {
        static $instances = array();

        $id = md5(serialize($config));

        if ( !isset($instances[$id]) )
        {
            $driver = 'db_'.strtolower($config['driver']);

            if ( !zotop::autoload($driver) )
            {
                throw new zotop_exception(t('Cannot find database driver "%s"', $driver));
            }

            $instances[$id] = new $driver($config);
        }

        return $instances[$id];
    }


    /**
     * 初始化构造函数
     * 
     * @param array $config 数据库配置数组
     */
    public function __construct($config=array())
    {
        if(!empty($config))
        {
            $this->config = array_merge($this->config, $config);

            if(is_array($this->config['params']))
            {
                $this->params  =  $this->config['params'] + $this->params;
            }
        }
    }


    /**
     * 连接数据库
     * 
     * @return [type] [description]
     */
    public function connect()
    {
        if( !$this->connect )
        {
            try
            {
                if( version_compare(PHP_VERSION,'5.3.6','<=') )
                { 
                    $this->params[PDO::ATTR_EMULATE_PREPARES] = false;
                }

                $this->connect = new PDO($this->config['dsn'], $this->config['username'], $this->config['password'], $this->params);
            }
            catch (PDOException $e)
            {
                throw new zotop_exception($e->getMessage());
            }
        }

        return $this->connect;
    }

    /**
     * SQL语句预执行
     * 
     * @param  string $sql sql语句
     * @return PDOStatement
     */
    protected function exec($sql)
    {
        if ( $sql and $this->connect() )
        {
            $this->free();

            $this->profile(true);

            $this->sql = $sql;

            $this->PDOStatement = $this->connect->prepare($sql);

            if( $this->PDOStatement === false )
            {
                throw new zotop_exception($this->errorinfo());
            }

            foreach ($this->bind as $key => $val)
            {
                if( is_array($val) )
                {
                    $this->PDOStatement->bindValue($key, $val[0], $val[1]);
                }
                else
                {
                    $this->PDOStatement->bindValue($key, $val);
                }
            }

            $this->bind = array();

            $result = $this->PDOStatement->execute();

            $this->reset();

            $this->profile(false);

            if ( $result === false )
            {
                $this->errorinfo();
                return false;
            }

            return true;
        }

        return false;  
    }

    /**
     * 执行一个SQL查询并返回数据集
     * 
     * @param string $sql  sql指令
     * @return mixed
     */    
    public function query($sql)
    {
        if ( $this->exec($sql) )
        {
            zotop::counter('db.query',1);

            $result = $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);

            $this->numRows = count($result);

            return $result;
        }

        return false;
    }

    /**
     * SQL执行，返回是否执行成功，并记录影响条数和最后插入ID
     * 
     * @param  [type] $sql [description]
     * @return bool 
     */
    public function execute($sql)
    {
        if ( $this->exec($sql) )
        {
            zotop::counter('db.execute',1);

            // 影响行数
            $this->numRows      = $this->PDOStatement->rowCount();          
            
            // 返回最后插入ID
            $this->lastInsertID = $this->connect->lastInsertId();

            return true;
        }

        return false;           
    }


    /**
     * 启动事务
     *
     * @return boolen
     */
    public function begin()
    {
        $this->connect();

        if ( $this->connect )
        {
            if ( $this->transTimes == 0 )
            {
                $this->connect->beginTransaction();
            }

            $this->transTimes++;

            return true;
        }

        return false;
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
            $this->transTimes = 0;

            if( !$this->connect->commit() )
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
        if ( $this->transTimes > 0 )
        {
            $this->transTimes = 0;
            
            if( !$this->connect->rollback() )
            {
                return false;
            }
        }
        
        return true;
    }    


    /**
     * 获取错误信息, 并显示当前的SQL语句
     * 
     * @return error
     */
    public function errorinfo()
    {
        $this->error = '';

        if( $this->PDOStatement and $error = $this->PDOStatement->errorInfo() )
        {
            $this->error .= "\n".$error[1].':'.$error[2];
        }    

        if( $this->sql )
        {
            $this->error .= "\nSQL:".$this->sql;
        }

        zotop::trace('error',$this->error);

        if ( ZOTOP_DEBUG )
        {
            throw new zotop_exception($this->error);
        }              

        return $this->error;
    }   

    /**
     * 释放查询结果
     *
     * @return void
     */
    public function free() 
    {
        if ($this->PDOStatement)
        {
            $this->PDOStatement = null;
        }        
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
     * 数据库类对象销毁
     *
     * @return void
     */
    public function __destruct()
    {
        $this->free();  //释放查询结果
        $this->close(); //关闭连接
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
     * 获取完整的表名
     * 
     * @param  string $tablename 表名称，有无前缀均可
     * @return string
     */
    public function tablename($tablename)
    {
        if ( $tablename[0] == '#' )
        {
            $tablename = $this->config['prefix'] . substr($tablename,1);
        }

        if ( substr($tablename, 0, strlen($this->config['prefix'])) != $this->config['prefix'] )
        {
            $tablename = $this->config['prefix'] . $tablename;
        }

        return $tablename;
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
        $table = $this->tablename($table);

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
     * 链式查询，标识要读取或者写入的字段
     *
     *
     * <code>
     * $this->field('id,username,password');
     * $this->field('*');
     * $this->field('content',false);
     * </code>
     *
     * @param  mixed  $fields 要选取的字段，默认为选取全部字段
     * @return  $this
     */
    public function field($fields = '*', $select=true)
    {
        if ( func_num_args() > 1 )
        {
            $fields = func_get_args(); //field('id','username','password')
        }
        elseif ( is_string($fields) )
        {
            $fields = explode(',', $fields); //field('id,username,password')
        }

        $this->sqlBuilder['field'] = (array) $fields;

        return $this;
    }

    /**
     * 链式查询，设置查询或者要操作的数据表
     * 
     * <code>
     * $this->table('user');
     * $this->table('user,config');
     * $this->table('user as u, config as c');
     * $this->table(array('user'=>'u','config'=>'c'));
     * </code>
     *
     * @param   mixed  table name
     * @param   string table alias  TODO实现第二个参数作为别名
     * @return  $this
     */
    public function table($table)
    {

        $this->sqlBuilder['table'] = $table;
        return $this;
    }

    /**
     * 链式查询，设置数据，用于‘insert’ 或者 ‘update’ "SET... VALUES ..."
     *
     * <code>
     * ->data('status',1) //设置‘status’为‘1’
     * ->data(array('status'=>1,'updatetime'=>'2010-09-23 21:45:30')) //设置多个数据
     * ->data('num',array('num','-',1)) 或者 set(array('num' => array('num','+',1))) //支持运算
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
     * table('content as c')->join('user as u', 'c.userid', 'u.id','LEFT')
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

        $join          = array();       
        $join['table'] = $table;
        $join['key']   = $key;
        $join['value'] = $value;
        $join['type']  = $type;

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
        if ( $limit === null )
        {
            unset($this->sqlBuilder['limit']);
            unset($this->sqlBuilder['offset']);
        }

        $this->sqlBuilder['limit']  = (int) $limit;
        $this->sqlBuilder['offset'] = (int) $offset;

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
    * 查询构建器  table 解析
    *
    * @param mixed $table 来源于sqlbuilder的数据表
    * @return string
    */
    public function parseTable($table)
    {
        $tables = array();

        // 别名或者数组模式
        if  ( is_array($table) )
        {
            foreach ($table as $name => $alias)
            {
               $tables[] = is_int($name) ? $name .' as '. $alias : $alias;
            }
        }

        // 字符串模式
        if ( is_string($table) )
        {
            $tables = explode(',',$table);
        }

        // TODO 这里有错误       
        array_walk($tables, array(&$this, 'escapeTable'));

        return implode(',',$tables);
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
     * 查询语句构建器 field字段 解析
     *
     * @param mixed $fields
     * @return string
     */
    public function parseField($fields)
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
     * 查询语句构建器,解析设置当前要操作的数据对象的值
     *
     * @param $data array
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
     * 生成查询语句
     *
     * <code>
     *
     * </code>
     *
     * @param array $sql sql数组
     * @return string
     */
    public function selectSql($sql='')
    {
        if ( empty($sql) or is_array($sql) )
        {
            $sqlBuilder = empty($sql) ? $this->sqlBuilder : array_merge($this->sqlBuilder, $sql);

            $sql = str_replace(
                array('%TABLE%','%DISTINCT%','%FIELDS%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%'),
                array(
                    $this->parseTable($sqlBuilder['table']),
                    $this->parseDistinct($sqlBuilder['distinct']),
                    $this->parseField($sqlBuilder['field']),
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
     * 链式查询之读取数据
     * 
     * @return array
     */
    public function select()
    {
        // 生成sql语句并获取数据
        if ( $selectSql = $this->selectSql() )
        {
            return $this->query($selectSql);
        }

        return array();
    }


    /**
     * 链式查询 获取分页数据
     * 
     * @param  [type]  $page     页码，page=0 时自动获取当前页面的 $_GET['page']
     * @param  integer $pagesize 每页显示数据
     * @param  [type]  $total    总共查询数据条数，0 计算真实的数据量
     * @return $this
     */
    public function getpage($page=0, $pagesize=20, $total=0)
    {
        $page       = intval($page)>0 ? intval($page) : ( intval($_GET['page'])>0 ? intval($_GET['page']) : 1 );
        $pagesize   = intval($pagesize)>0 ? intval($pagesize) : 20;
        $total      = intval($total)>0 ? intval($total) : 0;
        $sqlBuilder = $this->sqlBuilder;

        
        // 计算真实数据量
        if ( $total == 0 )
        {
            $hash = md5(serialize($sqlBuilder));

            if ( $page == 1 or !zotop::cache($hash) )
            {
                $total = $this->count();

                zotop::cache($hash, $total);
            }
            else
            {
                $total = zotop::cache($hash);
            }               
        }

        
        // 通过 page 和 pagesize 计算
        $sqlBuilder['limit']  = $pagesize;
        $sqlBuilder['offset'] = $pagesize*($page-1);

        // 写入sqlBuilder
        $this->sqlBuilder($sqlBuilder);

        // 生成查询语句并获取数据
        $data = $this->select();

        return array('data'=>$data,'page'=>$page,'pagesize' => $pagesize,'total'=>$total);
    }

    /**
     * 获取单行数据
     * 
     * @return array
     */
    public function getrow()
    {
        if ( $data = $this->limit(1)->select() )
        {
            return reset($data);
        }

        return array();
    }

    /**
     * 获取单个字段的数据
     * 
     * @return [type] [description]
     */
    public function getfield()
    {
        if ( $data = $this->getrow() )
        {
            return reset($data);
        }

        return null;
    } 


    /**
     * 链式查询之插入记录，支持 insert 和 replace 方式
     *
     * @code
     *
     * // 使用链式查询
     * $db->table('content')->data(……)->insert(true)
     * @endcode
     *
     * @param boolean $replace 是否为 replace
     * @return false | integer
     */
    public function insert($replace=false)
    {
        $table = $this->sqlBuilder['table'];
        $data  = $this->sqlBuilder['data'];

        // 没有插入的数据或者插入表
        if ( !is_array($data) or empty($table) ) return false;

        //处理插入数据
        foreach( $data as $field=>$value )
        {
            $fields[] = $this->escapeColumn($field);
            $values[] = $this->escapeValue($value);
        }

        //SQL
        $sql = ($replace ? 'REPLACE' : 'INSERT').' INTO %TABLE% (%FIELDS%) VALUES (%VALUES%)';
        $sql = str_replace(
            array('%TABLE%','%FIELDS%','%VALUES%'),
            array(
                $this->parseTable($table),
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
     * $db->table('user')->data('status',1)->where('id','=',1)->update()
     * </code>
     *
     * @param string $table 数据表
     * @param array $data 更新的数据
     * @param array $data 更新的条件
     * @return bool
     */
    public function update()
    {
        // 必须设置更新条件
        if( empty($this->sqlBuilder['table']) ) return false;

        // 未更新任何数据
        if ( !is_array($this->sqlBuilder['data']) ) return true;

        //sql
        $sql = 'UPDATE %TABLE%%SET%%WHERE%';
        $sql = str_replace(
            array('%TABLE%','%SET%','%WHERE%'),
            array(
                $this->parseTable($this->sqlBuilder['table']),
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
     * table('user')->where('id','=',1)->delete()
     * </code>
     *
     * @param string $table 数据表
     * @param array $data 更新的数据
     * @param array $data 更新的条件
     * @return bool
     */
    public function delete()
    {
        //必须设置删除条件
        if( empty($this->sqlBuilder['where']) )
        {
            return false;
        }

        $sql = 'DELETE FROM %TABLE%%WHERE%';
        $sql = str_replace(
            array('%TABLE%','%WHERE%'),
            array(
                $this->parseTable($this->sqlBuilder['table']),
                $this->parseWhere($this->sqlBuilder['where']),
            ),
            $sql
        );

        //返回查询结果
        return $this->execute($sql);
    }

    /**
     * 创建数据库
     * 
     * @return bool
     */
    abstract public function create();
    
    /**
     * 数据库是否存在
     * 
     * @return bool
     */
    abstract public function exists();

    /**
     * 删除数据库
     * 
     * @return bool
     */
    abstract public function drop();

    /**
     * 获取全部数据表
     * 
     * @return array
     */
    abstract public function tables();

    /**
     * 检查数据表是否存在
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    abstract public function existsTable($tablename);

    /**
     * 创建数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  array $schema    数据表的 schema
     * @return bool
     */
    abstract public function createTable($tablename, $schema);

    /**
     * 重命名数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $newname 数据表新名称，不含前缀
     * @return bool
     */
    abstract public function renameTable($tablename, $newname);

    /**
     * 更改数据表注释
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $comment 表注释
     * @return bool
     */
    abstract public function commentTable($tablename, $comment);

    /**
     * 删除数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    abstract public function dropTable($tablename);


    /**
     * 清空数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    abstract public function truncateTable($tablename);


    /**
     * 优化数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    abstract public function optimizeTable($tablename);

    /**
     * 检查数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    abstract public function checkTable($tablename);


    /**
     * 修复数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    abstract public function repairTable($tablename);
   

    /**
     * 返回表的结构数组
     *
     * <code>
     * 
     *  array(
     *      'fields' => array(
     *          'id'        => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE, 'autoinc'=>TRUE),
     *          'vid'       => array('type' => 'int', 'unsigned' => TRUE, 'notnull' => TRUE,'default' => 0),
     *          'type'      => array('type' => 'char','length' => 32,'notnull' => TRUE, 'default' => ''),
     *          'language'  => array('type' => 'char','length' => 12,'notnull' => TRUE,'default' => ''),
     *          'title'     => array('type' => 'varchar','length' => 255,'notnull' => TRUE, 'default' => ''),
     *          'uid'       => array('type' => 'int', 'notnull' => TRUE, 'default' => 0),
     *          'nid'      => array('type' => 'tinyint', 'unsigned' => TRUE, 'notnull' => TRUE, 'default' => 0),
     *      ),
     *      'index' => array(
     *          'uid'   => array('uid'),
     *          'nid'   => array('vid','nid'),
     *          'types' => array(array('type', 4)),
     *          'title_type'  => array('title', array('type', 4)),
     *      ),
     *      'unique' => array(
     *          'vid' => array('vid'),
     *      ),
     *      'foreign' => array(
     *          'uid' => array('users' => 'uid'),
     *      ),
     *      'primary' => array('id'),
     *      'comment' => 'The description for table.',
     *   );
     *
     * </code>
     * 
     * @return array
     */    
    abstract public function schema($tablename);

    /**
     * 字段类型映射，统一不同数据库的字段类型表达形式
     * 
     * date 或者 datetime 请使用INT字段存储时间戳
     * 
     * @return array
     */    
    abstract public function fieldtypes();

    /**
     * 获取表全部字段
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    abstract public function fields($tablename);

    /**
     * 判断表中字段是否存在
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $fieldname 字段名称
     * @return bool
     */
    abstract public function existsField($tablename, $fieldname);

    /**
     * 添加字段
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  array $field 字段属性数组
     * @return bool
     */
    abstract public function addField($tablename, $field);

    /**
     * 修改字段属性
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $fieldname 字段名称
     * @param  array $field 字段属性数组
     * @return bool
     */
    abstract public function changeField($tablename, $fieldname, $field);

    /**
     * 删除表中的字段
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $fieldname 字段名称
     * @return bool
     */
    abstract public function dropField($tablename, $fieldname);

    /**
     * 获取表全部索引
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    abstract public function indexes($tablename);

    /**
     * 判断索引是否存在
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $indexname 索引名称
     * @return bool
     */
    abstract public function existsIndex($tablename, $indexname);

    /**
     * 添加索引
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $indexname 索引名称
     * @param  array $fields  索引的字段
     * @param  string $type  索引类型
     */
    abstract public function addIndex($tablename, $indexname, $fields, $type='index');

    
    /**
     * 删除索引
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $indexname 索引名称
     * @return bool
     */
    abstract public function dropIndex($tablename, $indexname);


    /**
     * 获取主键
     *
     * <code>
     * ->primary('username'); // 获取表主键
     * </code>
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    abstract public function primary($tablename);

    /**
     * 添加主键，如果表已经存在主键则返回false
     * 
     * @code
     * 
     * $this->db->addPrimary('test_table','id');
     *
     * $this->db->addPrimary('test_table','id,nid');
     *
     * $this->db->addPrimary('test_table',array('id','nid'));
     *
     * @endcode
     *
     * @access public
     * @param string|array $fields  主键字段名称
     * @param string $table  数据表名，不含前缀
     * @return bool
     */
    abstract public function addPrimary($tablename,$fields);

    /**
     * 删除主键
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    abstract public function dropPrimary($tablename);

    /**
     * 魔术方法
     *
     * @param string $method 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
    public function __call($method, $args)
    {
        $method = strtolower($method);

        // 'distinct','having','group','lock' 连贯操作的实现
        if( in_array($method, array('distinct','having','group','lock'), true))
        {
            $this->sqlBuilder[$method] = $args[0];
            return $this;
        }
        
        // 实现 'count','sum','min','max','avg' 方法
        // table('user')->where('status','=',1)->count();
        // table('user')->sum('hits');
        if ( in_array($method, array('count','sum','min','max','avg'), true) )
        {       
            $field  = isset($args[0]) ? $args[0] : '*';
            
            $result = $this->field(strtoupper($method).'('.$field.') AS zotop_'.$method)->orderby(null)->getfield();

            return is_numeric($result) ? $result : 0;
        }

        throw new zotop_exception(t('Database method [ %s ] not exists', $method));
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
        zotop::trace('SQL', $info);
        return $info;
    }
}
?>