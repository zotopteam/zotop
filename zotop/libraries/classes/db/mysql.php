<?php defined('ZOTOP') or die('No direct script access.');
/**
 * Mysql 数据库驱动
 *
 * @package     zotop
 * @author      zotop team
 * @copyright   (c)2009-2012 zotop team
 * @license     http://zotop.com/license
 */
class db_mysql extends db
{

    /**
     * mysql 表注释最大长度
     */
    const COMMENT_MAX_TABLE = 60;

    /**
     * mysql字段注释最大长度
     */
    const COMMENT_MAX_COLUMN = 255; 
    
    /**
     * 初始化
     * 
     * @param array $config 数据库连接配置
     * 
     */
    public function __construct($config=array())
    {
        parent::__construct($config);

        if( empty($this->config['dsn']) )
        {
            $dsn = 'mysql:host='.$this->config['hostname'].';dbname='.$this->config['database'];

            if( $this->config['hostport'] )
            {
                $dsn .= ';port='.$this->config['hostport'];
            }

            if( $this->config['socket'] )
            {
                $dsn .= ';unix_socket='.$this->config['socket'];
            }

            if( $this->config['charset'] )
            {   
                // PHP 5.3.6及以前版本不支持在DSN中的charset
                if( version_compare(PHP_VERSION,'5.3.6','<=') )
                {
                    $this->params[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET nameS '.$this->config['charset'];
                }

                $dsn .= ';charset='.$this->config['charset'];
            }

            $this->config['dsn']  = $dsn;
        } 
    }

    /**
     * 字段类型映射，统一不同数据库的字段类型表达形式
     * date 或者 datetime 请使用INT字段存储时间戳
     * 
     * @return array
     */
    public function fieldtypes()
    {
        static $fieldtypes = array(
            'char'          => 'CHAR',
            'varchar'       => 'VARCHAR',

            'tinytext'      => 'TINYTEXT',
            'mediumtext'    => 'MEDIUMTEXT',
            'longtext'      => 'LONGTEXT',
            'text'          => 'TEXT',

            'tinyint'       => 'TINYINT',
            'smallint'      => 'SMALLINT',
            'mediumint'     => 'MEDIUMINT',
            'bigint'        => 'BIGINT',
            'int'           => 'INT',

            'double'        => 'DOUBLE',
            'float'         => 'FLOAT',

            'decimal'       => 'DECIMAL',

            'longblob'      => 'LONGBLOB',
            'blob'          => 'BLOB',
        );

        return $fieldtypes;
    }

    /**
     * 获取全部的数据表
     * 
     * @return array
     */
    public function tables()
    {
        $tables = array();

        $result = $this->query('SHOW TABLE STATUS');

        foreach ((array)$result as $r)
        {
            $id = $r['name'];

            if ( $this->config['prefix'] and substr($r['name'],0,strlen($this->config['prefix'])) == $this->config['prefix'] )
            {
                $id = substr($r['name'], strlen($this->config['prefix']));
            }

            $tables[$id] = array(
                'name'          => $r['name'],
                'size'          => $r['data_length'] + $r['index_length'],
                'datalength'    => $r['data_length'],
                'indexlength'   => $r['index_length'],
                'rows'          => $r['rows'],
                'engine'        => $r['engine'],
                'collation'     => $r['collation'],
                'createtime'    => $r['create_time'],
                'updatetime'    => $r['update_time'],
                'comment'       => $r['comment'],
            );          
        }

        return $tables;
    }

    /**
     * 根据scheam生成创建表的sql语句，含创建字段，创建索引等
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  array $schema    数据表的 schema
     * @return bool
     */ 
    protected function createTableSql($tablename, $schema)
    {
        // 默认值 MyISAM
        $schema += array('engine' => 'MyISAM', 'charset' => 'utf8');

        // SQL语句拼接
        $sql = '';
        $sql .= "CREATE TABLE IF NOT EXISTS " . $this->escapeTable($tablename) . " (\n";
        
        // 字段创建语句   
        foreach ( $schema['fields'] as $fieldname => $field )
        {
            $sql .= $this->createFieldSql($fieldname, $this->processField($field)).", \n";
        }

        // 主键及索引
        if ( $keys = $this->createKeysSql($schema) )
        {
            $sql .= implode(", \n", $keys) . ", \n";
        }

        // 删除最后多余的逗号、空格及换行
        $sql = substr($sql, 0, -3);
        
        // 引擎及字符串
        $sql .= "\n) ENGINE = " . $schema['engine'] . " DEFAULT CHARACTER SET " . $schema['charset'];
        
        // 默认采用UTF8
        if ( !empty($schema['collation']) )
        {
            $sql .= ' COLLATE ' . $schema['collation'];
        }

        // 数据表说明, TODO 处理注释超长问题
        if ( !empty($schema['comment']) )
        {
            $sql .= " COMMENT '" . $schema['comment'] . "'";
        }

        return array($sql);         
    }

    /**
     * 生成字段创建语句
     * 
     * @param  string $name 字段名称
     * @param  array $spec 字段描述数组
     * @return string
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
     * 根据 schema 生成 KEYS SQL 片段
     * 
     * @param  [type] $schema [description]
     * @return [type]         [description]
     */
    protected function createKeysSql($schema)
    {
        $keys = array();

        if ( !empty($schema['primary']) )
        {
            $keys[] = 'PRIMARY KEY (' . $this->createKeySql($schema['primary']) . ')';
        }

        if ( !empty($schema['unique']) )
        {
            foreach ($schema['unique'] as $key => $fields)
            {
                $keys[] = 'UNIQUE KEY `' . $key . '` (' . $this->createKeySql($fields) . ')';
            }
        }

        if ( !empty($schema['index']) )
        {
            foreach ($schema['index'] as $index => $fields)
            {
                $keys[] = 'INDEX `' . $index . '` (' . $this->createKeySql($fields) . ')';
            }
        }

        return $keys;
    }

    // keys sqlhelper
    protected function createKeySql($fields)
    {
        $return = array();

        //添加对多个字段使用“,”隔开的支持
        if ( is_string($fields) )
        {
            $fields = explode(',',$fields);
        }

        // 循环生成相应SQL片段
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
     * 将字段信息转化为mysql字段信息
     *
     * @param $field  字段描述数组
     * @return array 转化后的数组
     */
    protected function processField($field)
    {
        $types = $this->fieldtypes();

        $field['mysql_type'] = isset($field['mysql_type']) ? strtoupper($field['mysql_type']) : $types[strtolower($field['type'])];

        return $field;
    }    

    /**
     * 获取表的字段，返回标准化字段结构数组
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    public function fields($tablename)
    {
        $fields = array();
        
        // 获取字段类型
        $types  = array_flip($this->fieldtypes());
        
        // 获取字段信息
        $result = $this->query("SHOW FULL FIELDS FROM ".$this->escapeTable($tablename));

        foreach ($result as $row)
        {
            $field = $row['field'];

            // 分解原始Type，获取type及length
            if ( preg_match('/^([^(]+)\((.*)\)/', $row['type'], $matches) )
            {
                $type   = $matches[1];
                $length = $matches[2];
            }
            else
            {
                $type   = $row['type'];
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
            $fields[$field]['notnull'] = (bool) ($row['null'] === 'NO'); // not null is NO, null is YES

            // default
            if ( '' !== $default = trim($row['default'], "'")  )
            {
                $fields[$field]['default'] = $default;
            }
            elseif ( !isset($row['default']) ) 
            {
                if ($row['null'] == 'YES') $fields[$field]['default'] = NULL;
            }               

            // unsigned
            if ( false !== strpos($row['type'], 'unsigned')  )
            {
                $fields[$field]['unsigned'] = true;
            }
            
            //primary 2012-12-06，唯一索引也会显示为 PRI，所以改为根据索引判断是否为主键
            //$fields[$field]['primary'] = (strtolower($row['Key']) == 'pri');

            //autoinc
            $fields[$field]['autoinc'] = (strtolower($row['extra']) == 'auto_increment');

            // comment
            $fields[$field]['comment'] = $row['comment'];

        }

        return $fields;             
    }

    /**
     * 重命名数据表
     *
     * @access public
     * @param string $tablename  新名称，不含前缀
     * @param string $newname  原名称，不含前缀，空时为默认表
     * @return bool
     */ 
    public function renameTable($tablename, $newname)
    {
        if ( !$this->existsTable($tablename) )
        {
            return false;
        } 

        if ( $this->existsTable($newname) )
        {
            return false;  
        }  

        return $this->execute('ALTER TABLE ' . $this->escapeTable($tablename) . ' RENAME TO ' . $this->escapeTable($newname). '');
    }

    /**
     * 重命名数据表
     *
     * @access public
     * @param string $tablename 表名称，不含前缀
     * @param string $comment  表注释
     * @return bool
     */ 
    public function commentTable($tablename, $comment)
    {
        if ( !$this->existsTable($tablename) )
        {
            return false;
        } 

        return $this->execute('ALTER TABLE '.$this->escapeTable($tablename).' COMMENT=\''.$comment.'\'');
    }


    /**
     * 删除数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    public function dropTable($tablename)
    {
        if ( !$this->existsTable($tablename) )
        {
            return false;
        } 

        return $this->execute('DROP TABLE ' . $this->escapeTable($tablename));        
    }






}
?>