<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * dbimport_model
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class dbimport_model_dbimport extends model
{
	protected $pk 		= 'id';
	protected $table 	= 'dbimport';

	/*
	 *  获取数据集
	 */
	public function getall()
	{
		$data = array();

		$rows = $this->db()->orderby('id','asc')->getAll();

		foreach( $rows as &$r )
		{
			$r['source'] 	= unserialize($r['source']);
			$r['maps'] 		= unserialize($r['maps']);

			$data[$r['id']] = $r;
		}

		return $data;
	}

	/*
	 *  获取数据
	 */
	public function get($id, $field='')
	{
		$data = $this->getbyid($id);

		if ( is_array($data)  )
		{
			$data['source'] = unserialize($data['source']);
			$data['maps'] 	= unserialize($data['maps']);

		}

		if ( empty($field) )
		{
			return $data;
		}


		list($field, $key) = explode('.', $field);

		return ( $key and is_array($data[$field]) ) ? $data[$field][$key] : $data[$field];

	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));
		if ( empty($data['table']) ) return $this->error(t('目标数据表不能为空'));

		if ( $this->source_count($data) === false )
		{
			return $this->error(t('规则异常, {1}', $this->error()));
		}

		if ( $id = $this->insert($data) )
		{
			return $id;
		}

		return false;
	}

	/*
	 *  编辑数据
	 */
	public function edit($data, $id)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));
		if ( empty($data['table']) ) return $this->error(t('目标数据表不能为空'));	
		
		if ( $this->source_count($data) === false )
		{
			return $this->error(t('规则异常, {1}', $this->error()));
		}

		// 更新数据
		if ( $this->update($data, $id) )
		{
			return $id;
		}

		return false;
	}

	/*
	 *  删除数据
	 *
	 * @param int $id ID
	 * @return bool
	 */ 
	public function delete($id)
	{
		return parent::delete($id);
	}

	/**
	 * 获取复合导入规则的数据总条数
	 * 
	 * @param  array $config 导入规则
	 * @return int
	 */
	public function source_count($config)
	{
		
		try
		{
			return $this->source_db($config)->count();

		}
		catch (Exception $e)
		{
			return $this->error($e->getMessage());
		}
	}

	/**
	 * 获取复合导入规则的数据总条数
	 * 
	 * @param  array $config 导入规则
	 * @return int
	 */
	private function source_db($config)
	{
		try
		{
			zotop::db($config['source'])->connect();		
		}
		catch (Exception $e)
		{
			return $this->error($e->getMessage());
		}

		if ( $config['source']['condition'] )
		{
			return zotop::db($config['source'])->from($config['source']['table'])->where($config['source']['condition']);	
		}

		return zotop::db($config['source'])->from($config['source']['table']);
	}

	/**
	 * 导入数据
	 * 
	 * @param  array $config 导入规则
	 * @return int
	 */
	public function import($config)
	{
		// 禁用超时
		@set_time_limit(0);

		if ( $db = $this->source_db($config) )
		{
			$source_data = $db->getall();

			foreach ($source_data as $r)
			{
				$data = array();

				foreach ($config['maps'] as $k => $m)
				{
					if ( $m['field'] )
					{
						$data[$k] = $r[$m['field']];

						if ( $m['function'] )
						{
							$data[$k] = strpos($m['function'], '::') ? call_user_func_array($m['function'], $data[$k]) : call_user_func($m['function'], $data[$k]);	
						}						
					}
					else
					{
						$data[$k] = $m['default'];
					}					
				}

				$this->db->from($config['table'])->data($data)->insert(true);
			}

			return count($source_data);
		}

		return false;
	}
}
?>