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
                    $this->params[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES '.$this->config['charset'];
                }

                $dsn .= ';charset='.$this->config['charset'];
            }

            $this->config['dsn']  = $dsn;
        } 
    }

    /**
     * 创建数据库
     * 
     * @return bool
     */
    public function create()
    {
        try
        {
            $config = $this->config;

            unset($config['dsn'],$config['database']);

            $db = self::instance($config);
            $db->execute("CREATE DATABASE IF NOT EXISTS `".$this->config['database'] ."` DEFAULT CHARACTER SET ".$this->config['charset']." COLLATE ".$this->config['collation']."");
            $db->execute("USE `".$this->config['database'] ."`");

            return true; 
        }
        catch (PDOException $e)
        {
            return false;
        }               
    }
    
    /**
     * 数据库是否存在
     * 
     * @return bool
     */
    public function exists()
    {
        try
        {
            $this->connect();
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }        
    }

    /**
     * 删除数据库
     * 
     * @return bool
     */
    public function drop()
    {
        if ( $this->exists() )
        {
            return $this->execute("DROP DATABASE `".$this->config['database']."`");
        }

        return true;
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
     * 检查数据表是否存在
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    public function existsTable($tablename)
    {
        return in_array(strtolower($tablename), array_keys($this->tables()));
    }


    /**
     * 创建数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  array $schema    数据表的 schema
     * @return bool
     */
    public function createTable($tablename, $schema)
    {
        // 表已经存在则创建失败
        if ( $this->existsTable($tablename) ) return false;

        try
        {
            // 获取表的创建语句
            $sqls = $this->createTableSql($tablename, $schema);

            foreach( $sqls as $sql )
            {
                $this->execute($sql);
            }

            return true;            
        }
        catch (Exception $e)
        {
            throw new zotop_exception($e->getMessage().' [SQL:'.$this->sql().']');
        }

        return false;   
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

        return $this->execute('ALTER TABLE `' . $this->tablename($tablename) . '` RENAME TO `' . $this->tablename($newname). '`');
    }

    /**
     * 注释数据表
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

        return $this->execute('ALTER TABLE `'.$this->tablename($tablename).'` COMMENT=\''.$comment.'\'');
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

        return $this->execute('DROP TABLE `' . $this->tablename($tablename) . '`');        
    }


    /**
     * 清空数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    public function truncateTable($tablename)
    {
        return $this->execute('TRUNCATE TABLE `' . $this->tablename($tablename) . '`');
    }


    /**
     * 优化数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    public function optimizeTable($tablename)
    {
        return $this->execute('OPTIMIZE TABLE `' . $this->tablename($tablename) . '`');
    }

    /**
     * 检查数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    public function checkTable($tablename)
    {
        return $this->execute('CHECK TABLE `' . $this->tablename($tablename) . '`');
    }


    /**
     * 修复数据表
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return bool
     */
    public function repairTable($tablename)
    {
        return $this->execute('REPAIR TABLE `' . $this->tablename($tablename) . '`');
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
        $sql .= "CREATE TABLE IF NOT EXISTS `" . $this->tablename($tablename) . "` (\n";
        
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
        $result = $this->query("SHOW FULL FIELDS FROM `".$this->tablename($tablename)."`");

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
     * 检查字段是否存在
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $fieldname 字段名称
     * @return bool
     */
    public function existsField($tablename, $fieldname)
    {
        try 
        {
            $this->query("SELECT `{$fieldname}` FROM `" . $this->tablename($tablename) . "`");
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
     * @param string $tablename  数据表名，不含前缀
     * @param array $field  字段信息
     * @param string $keys_new  额外信息       
     * @return bool
     */
    public function addField($tablename, $field, $keys_new = array())
    {
        if ( !$this->existsTable($tablename) ) return false;

        if ( $this->existsField($tablename, $field['name']) ) return false;
                
        if ( !empty($field['notnull']) && !isset($field['default']) )
        {
            $field['notnull'] = false;
        }

        $sql = 'ALTER TABLE `' . $this->tablename($tablename) . '` ADD ' . $this->createFieldSql($field['name'], $this->processField($field));        
        
        if ( $keys_sql = $this->createKeysSql($keys_new) )
        {
            $sql .= ', ADD ' . implode(', ADD ', $keys_sql);
        }

        return $this->execute($sql);
    }

    /**
     * 修改字段属性
     *
     * @param string $tablename  数据表名，不含前缀
     * @param string $fieldname  字段名称
     * @param array $field  字段信息
     * @param string $keys_new  额外信息        
     * @return bool
     */
    public function changeField($tablename, $fieldname, $field, $keys_new = array())
    {
        if ( !$this->existsField($tablename,$fieldname) ) return false;

        $newfieldname = ( isset($field['name']) AND !empty($field['name']) ) ? $field['name'] : $fieldname;

        if ( ($fieldname != $newfieldname) && $this->existsField($tablename, $newfieldname) ) return false;

        $sql = 'ALTER TABLE `' . $this->tablename($tablename) . '` CHANGE `' . $fieldname . '` ' . $this->createFieldSql($newfieldname, $this->processField($field));
        
        if ( $keys_sql = $this->createKeysSql($keys_new) )
        {
            $sql .= ', ADD ' . implode(', ADD ', $keys_sql);
        }
        
        return $this->execute($sql);
    }

    /**
     * 删除字段
     * 
     * @param string $tablename  数据表名，不含前缀
     * @param string $fieldname  字段名称
     * @return bool
     */
    public function dropField($tablename, $fieldname)
    {       
        if ( $this->existsField($tablename, $fieldname) )
        {
            return $this->execute('ALTER TABLE `' . $this->tablename($tablename) . '` DROP `' . $fieldname . '`');
        }   

        return false;  
    } 

    /**
     * 获取数据表的索引信息，包含主键、唯一索引、索引等
     * 
     * @param string $tablename  数据表名，不含前缀
     * @return array
     */
    public function indexes($tablename)
    {
        $indexes   = array();
        $indexlist = array();       
        $result    = $this->query('SHOW INDEX FROM `'.$this->tablename($tablename).'`');       

        foreach ($result as $row)
        {
            $indexlist[] = array(
              'type'   => $row['non_unique'] ? 'index' : ($row['key_name'] == 'PRIMARY' ? 'primary': 'unique'),
              'name'   => $row['key_name'],
              'column' => ( $row['sub_part'] ) ? array($row['column_name'], (int)$row['sub_part']) : $row['column_name'],
            );
        }
        
        foreach( $indexlist as $index )
        {
            $indexes[$index['name']]['name']     = $index['name'];
            $indexes[$index['name']]['type']     = $index['type'];
            $indexes[$index['name']]['column'][] = $index['column'];
        }

        return $indexes;        
    }

    /**
     * 判断索引是否存在
     * 
     * @param string $tablename  数据表名，不含前缀
     * @param string $indexname  索引名称
     * @return array
     */    
    public function existsIndex($tablename, $indexname)
    {
        $result = $this->query('SHOW INDEX FROM `' . $this->tablename($tablename) . "` WHERE `key_name` = '". trim($indexname) ."'");
        
        return isset($result[0]['key_name']);
    }

    /**
     * 添加索引
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $indexname 索引名称
     * @param  array $fields  索引的字段
     * @param  string $type  索引类型
     */
    public function addIndex($tablename, $indexname, $fields, $type='index')
    {
        if ( !$this->existsTable($tablename) ) return false;

        if ( $this->existsIndex($tablename,$indexname) ) return false;

        switch ( strtolower($type) )
        {
            case 'unique':
                $type = 'UNIQUE KEY';
                break;
            case 'fulltext':
                $type = 'FULLTEXT';
                break;                           
            default:
                $type = 'INDEX';
                break;
        }

        return $this->execute('ALTER TABLE `' . $this->tablename($tablename) . '` ADD '. $type .' `' . trim($indexname) . '` (' . $this->createKeySql($fields) . ')');        
    }

    /**
     * 删除索引
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @param  string $indexname 索引名称
     * @return bool
     */
    public function dropIndex($tablename, $indexname)
    {
        if ( $this->existsIndex($tablename,$indexname) ) 
        {
            return $this->execute('ALTER TABLE `' . $this->tablename($tablename) . '` DROP INDEX `' . trim($indexname) . '`');
        }
        
        return false;       
    }

    /**
     * 获取主键
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    public function primary($tablename)
    {
        $primary = array();

        $indexes = $this->indexes($tablename);

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
     * $this->db->addPrimary('test_table','id');
     *
     * $this->db->addPrimary('test_table','id,nid');
     *
     * $this->db->addPrimary('test_table',array('id','nid'));
     *
     * @endcode
     *
     * @param string $table  数据表名，不含前缀
     * @param string|array $fields  主键字段名称
     * @return bool
     */
    public function addPrimary($tablename, $fields)
    {
        if ( !$this->existsTable($tablename) ) return false;

        if ( $this->existsIndex($tablename,'PRIMARY') ) return false;

        return $this->execute('ALTER TABLE `' . $this->tablename($tablename) . '` ADD PRIMARY KEY (' . $this->createKeySql($fields) . ')');        
    }


    /**
     * 删除主键
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    public function dropPrimary($tablename)
    {
        if ( $this->existsIndex($tablename,'PRIMARY') )
        {
            return $this->execute('ALTER TABLE `' . $this->tablename($tablename) . '` DROP PRIMARY KEY');        
        }

        return false;
    }



    /**
     * 获取数据表的结构数组
     * 
     * @param  string $tablename 数据表名称，不含前缀
     * @return array
     */
    public function schema($tablename)
    {
        // 初始化schema
        $schema = array(
            'fields'  => array(),
            'index'   => array(),      
            'unique'  => array(),
            'primary' => array(),
            'comment' => ''
        );

        if ( $this->existsTable($tablename) )
        {
            $schema['fields'] = $this->fields($tablename);

            foreach( $schema['fields'] as $name => $field )
            {
                if ( $field['primary'] ) $schema['primary'][] = $name;
            }

            $indexes = $this->indexes($tablename);
            
            foreach ($indexes as $index)
            {   
                if ( $index['type'] == 'primary' )
                {
                    $schema['primary'] = $index['column']; continue;
                }

                $schema[$index['type']][$index['name']] = $index['column'];
            }
            
            $infos = $this->query("SELECT * FROM information_schema.tables WHERE `table_schema`='".$this->config['database']."' AND `table_name`='".$this->tablename($tablename)."'");

            $schema['comment'] = $infos[0]['table_comment'];
            $schema['engine']  = $infos[0]['engine'];            
        }

        return $schema;        
    }

}
?>