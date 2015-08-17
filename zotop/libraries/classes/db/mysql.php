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
        static $tables = array();

        if ( empty($tables) )
        {
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
        $sql .= "CREATE TABLE IF NOT EXISTS " . $this->escapeTable($table) . " (\n";
        
        // 字段创建语句
        //$sql .= $this->createColumsSql($table, $schema). ", \n";
        
        foreach ( $schema['fields'] as $fieldname => $field )
        {
            // 自动增长
            if ( $field['autoinc'] )
            {
                if (isset($schema['primary']) && ($key = array_search($fieldname, $schema['primary'])) !== FALSE)
                {
                    unset($schema['primary'][$key]);
                }
            }

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


    public function fields($tablename)
    {

    }
}
?>