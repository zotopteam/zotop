<?php defined('ZOTOP') or die('No direct script access.');
/**
 * 模型基类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class model
{
	protected $db       = null; // 当前数据库操作对象
	protected $dbconfig = array(); //默认的数据库配置，array
	protected $table    = ''; //数据表名称
	protected $alias    = ''; //数据表别名
	protected $pk       = 'id'; //主键名称
	protected $fields   = array(); //表的结构
	protected $data     = array(); //属性设置
	protected $error    = null; //错误

    /**
     * 架构函数，取得DB类的实例对象
     *
     * @param string $name 模型名称
     * @param string $_prefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
	public function __construct()
	{
		if ( !is_object($this->db) ) $this->db  = zotop::db($this->dbconfig);
	}

    /**
     * 设置属性的值
     *
     * @param string $name 名称
     * @param mixed $value 值
     * @return void
     */
    public function __set($name,$value)
    {
        $this->data[$name]  =   $value;
    }

    /**
     * 获取属性的值
     *
     * @param string $name 名称
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * 属性是否已经设置
     *
     * @param string $name 名称
     * @return bool
     */
	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

    /**
     * 注销属性
     *
     * @param string $name 名称
     * @return bool
     */
	public function __unset($name)
	{
		unset($this->data[$name]);
	}

    /**
     * 得到当前的数据表的主键名称
     *
     * @access public
     * @return string
     */
    public function pk()
    {
        if( empty($this->pk) )
        {
            $this->pk = $this->db->primary($this->table);
        }

        return $this->pk;
    }

    /**
     * 得到当前的数据表的结构
     *
     * @access public
     * @return string
     */
	public function fields($refresh=false)
	{
		//已经读取，并且不为空，且未强制刷新，直接返回字段信息
		if( $this->fields && is_array($this->fields) && $refresh === false )
		{
			return $this->fields;
		}

		//从缓存读取
		if ( $refresh === false and $this->fields = zotop::cache("{$this->table}.fields") )
		{
			return $this->fields;
		}

		//强制刷新
		if ( $fields = $this->db->fields($this->table) )
		{
			$this->fields = array_keys($fields);
			zotop::cache("{$this->table}.fields", $this->fields); //只存储字段名称数组
			return $this->fields;
		}

		return array();
	}


    /**
     * 返回错误
	 *
	 * <code>
	 * return $this->error(content);
	 * </code>
     *
     */
	public function error($error = '')
	{
		if ( empty($error) )
		{
			return $this->error;
		}

		$this->error = $error;
		return false;
	}

    /**
     * 当前数据表的别名,链式操作的一部分
     *
     * @param string $alias 数据表别名
     * @return mixed
     */
	public function alias($alias)
	{
		$this->alias = $alias;
		
		return $this;
	}

    /**
     * 获取当前的db对象,并默认设置链式查询的from($table)属性
     *
     * @return mixed
     */
    public function db()
    {
		$table = $this->table;

		if ( is_string($this->alias) && !empty($this->alias) )
		{
			$table = $table.' AS '.$this->alias;
			$this->alias='';
		}

		return $this->db->table($table);
    }

    /**
     * 启动事务
	 *
     * @return void
     */
    public function begin()
	{
        return $this->db->begin();
    }

    /**
     * 提交事务
     *
     * @return boolean
     */
    public function commit()
	{
        return $this->db->commit();
    }

    /**
     * 事务回滚
     *
     * @return boolean
     */
    public function rollback()
	{
        return $this->db->rollback();
    }

	/**
	 * 获取全部数据
	 *
     * @return mixed
	 */
    public function select()
    {
        return $this->db()->select();
    }

	/**
	 * 返回limit限制的数据,用于带分页的查询数据
	 *
	 * @param $page int 页码
	 * @param $pagesize int 每页显示条数
	 * @param $num int|bool 总条数|缓存查询条数，$toal = (false||0) 不缓存查询
	 * @return mixed
	 */
	public function getPage($page=0, $pagesize=20, $total = false)
	{
		return $this->db()->page($page,$pagesize,$total);
	}  

	/**
	 * 获取单条数据
	 *
     * @return mixed
	 */
    public function getRow()
    {
        return $this->db()->getRow();
    }

	/**
	 * 获取某个字段的数据
	 *
     * @param string $field 字段名称
     * @return mixed
	 */
    public function getField()
    {
        return $this->db()->getField();
    }


    /**
     * 根据主键获取数据
     *
	 * @code
	 * $this->get(1);
	 * $this->get(1, 'name');
	 * @endcode
	 *
	 * @param $id 主键键值
	 * @param $field 需要查询的字段
     * @return array
     */
	public function get($id, $field='')
	{
		static $data = array();

		if ( !isset($data[$id]) )
		{
			$data[$id] = $this->where($this->pk(), '=', $id)->getRow();
		}

		return empty($field) ? $data[$id] : $data[$id][$field];
	}


    /**
     * 缓存数据, TODO ，这儿需要优化
	 *
     * @return array
     */
	public function cache($refresh=false)
	{
		return $this->select();
	}


    /**
     * 用于添加及更新数据时处理数据，并过滤掉不符合要求的数据
     *
     * @param array $data 传入的数据
	 * @param string $method 操作方法，可以是insert或者update
     * @return array
     */
	public function _filter_data($data, $method='')
	{
		// 过滤数据表中没有的字段
		if ( is_array($data) )
		{
			foreach( $data as $key=>$field )
			{
				if ( !in_array(strtolower($key), $this->fields()) )
				{
					unset($data[$key]);
				}
			}

			unset($keys);
		}
		else
		{
			$data = array();
		}

		return $data;
	}


    /**
     * 新增数据
     *
     * @param mixed $data 数据
     * @param boolean $replace 是否replace
     * @return mixed
     */
    public function insert($data='', $replace=false)
	{
		if ( $data === true or $data === false )
		{
			$replace = $data;$data=array();
		}

		if ( empty($data) )
		{
			$data = empty($this->data) ? $this->db->data() : $this->data;
		}

		// 前置保存
		if ( false === $this->_before_insert($data, $replace) )
		{
			return false;
		}

		// 如果有数据
		if ( $insertdata = $this->_filter_data($data) )
		{
			// 插入成功
			if ( false !== $insertid = $this->db()->data(null)->data($insertdata)->insert($replace) )
			{
				// 自增的返回的id
				if ( $insertid !== true ) $data[$this->pk()]  = $insertid;

				// 后置方法
				$this->_after_insert($data,$replace);

				// 返回id
				return $data[$this->pk()];
			}
		}

		return false;
	}

    // 保存数据前的回调方法
    protected function _before_insert(&$data,$where) {}

    // 保存数据后的回调方法
    protected function _after_insert(&$data,$where) {}


    /**
     * 更新数据
     *
	 * <code>
	 *
	 * 更新主键为 1 的数据
	 *
  	 * 方法一：
	 *
	 * $model->id = 1;
	 * $model->name = 'test';
	 * $model->title = 'test title';
	 * $model->update();
	 *
	 * 方法二：
	 *
	 * $model->data(array('id'=>1,'name'=>'test','title'=>'test title'))->update();
	 *
	 * 方法三：
	 *
	 * $model->where('id',1)->data('name','test')->data('title','test')->update()
	 *
	 * 自增，自减
	 *
	 * $model->where('id',1)->data('logintime',ZOTOP_TIME)->data('loginnum',array('loginnum','+',1))->update();
	 *
	 * </code>
	 *
     * @param mix $data 待更新的数据
     * @param mix $where 条件
     *
     * @return array
     */
    public function update($data=array(), $where = '')
    {
		if ( !is_array($data) or empty($data) )
		{
			$data = empty($this->data) ? $this->db->data() : $this->data;
		}

		if ( empty($where) )
		{
			$where = isset($data[$this->pk()]) ? array($this->pk(),'=',$data[$this->pk()]) : $this->db->where();
		}
		elseif( is_numeric($where) or is_string($where) )
		{
			// 生成where
			$where = array($this->pk(),'=',$where);
		}



		// 前置保存
		if ( false === $this->_before_update($data, $where) )
		{
			return false;
		}

		// 如果有数据
		if ( $updatedata = $this->_filter_data($data) )
		{
			// 更新
			if ( $this->db()->data(null)->data($updatedata)->where(null)->where($where)->update() )
			{
				// 后置方法
				$this->_after_update($data, $where);

				return true;
			}

			return false;
		}

		return true;
	}

    // 保存数据前的回调方法
    protected function _before_update(&$data,$where) {}

    // 保存数据后的回调方法
    protected function _after_update(&$data,$where) {}

    /**
     * 删除数据
     *
	 * @param mix $where 条件
     * @return bool
     */
    public function delete($where = '')
    {
		if ( empty($where) )
		{
			if ( !empty($this->data[$this->pk()]) )
			{
				// $this->id = 1;$this->delete();
				$data[$this->pk()] = $this->data[$this->pk()];
				$where = array($this->pk(),'=',$this->data[$this->pk()]);
			}
			else
			{
				$where = $this->db->where();
			}
		}
		elseif( is_numeric($where) or is_string($where) )
		{
			//$this->delete(1);
			$data[$this->pk()] = $where;

			$where = strpos($options,',') ? array($this->pk(),'IN',$where) : array($this->pk(),'=',$where);
		}

        if(false === $this->_before_delete($data,$where))
		{
            return false;
        }

		// 更新
		if ( $this->db()->where(null)->where($where)->delete() )
		{
			// 后置方法
			$this->_after_delete($data,$where);

			return true;
		}

		return false;

	}

    // 删除前的回调方法
    protected function _before_delete($data,$where) {}
    // 删除成功后的回调方法
    protected function _after_delete($data,$where) {}


    /**
     * 判断是否存在
     *
	 * @code
	 * $this->where('id',1)->exists();
	 * @endcode
	 *
     * @return bool
     */
    public function exists()
    {
        return $this->count() ? true : false;
    }

    /**
     * 利用__call方法实现一些特殊的Model方法
     *
     * @param string $method 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
    public function __call($method, $args)
	{
		$method = strtolower($method);

		if ( in_array($method, array('distinct','field','data','join','where','orderby','having','groupby','limit','offset'),true) )
		{
			//链式查询
			call_user_func_array(array($this->db, $method), $args);
			return $this;
		}
		elseif( substr($method, 0, 5) == 'getby' )
		{
			// 根据某个字段获取数据，实现getbyid 或者 getbyname等方法 ,只获取一条记录
			// $model->getbyid(1);
			// $user->getbyusername('username');
            $field = substr($method,5);

			if ( in_array($field, $this->fields()) )
			{
				return $this->db()->where($field,'=', $args[0])->getrow();
			}
		}
		elseif( substr($method, 0, 8) == 'deleteby' )
		{
			// 根据某个字段删除数据，实现deletebyid 或者 deletebyname等方法
			// $model->deletebyid(1);
			// $model->deletebycid(1);
            $field = substr($method,8);

			if ( in_array($field, $this->fields()) )
			{
				return $this->db()->where($field, '=', $args[0])->delete();
			}
		}
		elseif ( in_array($method, array('count','sum','min','max','avg'), true) )
		{
			// 实现 'count','sum','min','max','avg' 方法
			// $user->where('status','=',1)->count();
			// $user->sum('hits');
			$field =  isset($args[0]) ? $args[0] : '*';

			$result = $this->field(strtoupper($method).'('.$field.') AS zotop_'.$method)->getField();

			return is_numeric($result) ? $result : 0;
		}

		throw new zotop_exception(t('Method [ %s ] not exists', $method));
	}

}
?>