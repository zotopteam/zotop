<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * wechat_model_account
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class wechat_model_account extends model
{
	protected $pk = 'id';
	protected $table = 'wechat_account';

	
	/**
	 * 公众号类型
	 * 
	 * @param  int $type 类型编号
	 * @return array
	 */
	public function type($type=null)
	{
		$types = array(
			1 => t('普通订阅号'),
			2 => t('普通服务号'),
			3 => t('认证订阅号'),
			4 => t('认证服务号'),
		);

		return $type ? $types[$type] : $types;
	}

	/**
	 * 添加模型
	 * 
	 * @param array $data 模型数据
	 * @return mixed
	 */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('微信名称不能为空'));		
		
		$data['listorder']	= $this->max('listorder') + 1;
		
		if ( $id = $this->insert($data) )
		{
			$this->cache(true);
			return $id;
		}
		
		return false;
	}

	/**
	 * 修改模型
	 * 
	 * @param array $data 模型数据
	 * @param  string $id  模型编号
	 * @return mixed
	 */
	public function edit($data, $id)
	{
		if ( empty($data['name']) ) return $this->error(t('微信名称不能为空'));

		if ( $this->update($data, $id) )
		{
			$this->cache(true);
			return $id;
		}

		return false;
	}

    /**
     * 删除
     *
     */
	public function delete($id)
	{
		if ( parent::delete($id) )
		{
			$this->cache(true);
			return true;
		}

		return false;
	}


	/**
	 * 获取排序过的全部数据
	 * 
	 * @return array
	 */
	public function getAll()
	{
		$result = array();

		$data =  $this->db()->orderby('listorder','asc')->getAll();

		foreach( $data as &$d )
		{
			$result[$d['id']] = $d;
		}

		return $result;
	}

    /**
     * 获取信息
     * 
     * @param  int $id    编号
     * @param  string $field 字段名
     * @return mixed
     */
	public function get($id, $field=null)
	{
		static $data = array();

		if ( empty($data) )
		{
			$data = $this->cache();
		}

		if ( empty($field) )
		{
			return isset($data[$id]) ? $data[$id] : array();
		}

		if ( isset($data[$id]) )
		{
			
			return $data[$id][$field];
		}

		return '';
	}

	/**
	 * 排序
	 *
	 */
	public function order($ids)
	{
		foreach( (array)$ids as $i=>$id )
		{
			$this->update(array('listorder'=>$i+1), $id);
		}

		$this->cache(true);
		return true;
	}

	/**
	 * 根据ID设置状态
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function status($id)
	{
		$data = $this->select('disabled')->getbyid($id);

		$disabled = $data['disabled'] ? 0 : 1;

		if ( in_array($id, array('page','link')) )
		{
			return $this->error(t('该模型不能被禁用'));
		}

		if ( $this->update(array('disabled'=>$disabled),$id) )
		{
			$this->cache(true);
			return true;
		}

		return false;
	}

	public function connect($id, $connect=1)
	{

		if (  $this->where('id',$id)->data('connect',$connect)->update() )
		{
			$this->cache(true);			
			return true;
		}
		
		return false;
	}

	/*
	 * 缓存数据和设置缓存
	 *
	 * @param string $refresh 强制刷新
	 * @return string 缓存数据
	 */
	public function cache($refresh=false)
	{
		$cache = zotop::cache("wechat.account");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->getAll();

			zotop::cache("wechat.account", $cache, false);
		}

		return $cache;
	}	
}
?>