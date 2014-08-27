<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 规格
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class shop_model_spec extends model
{
	protected $pk = 'id';
	protected $table = 'shop_spec';

	public $types;
	public $shows;

	public function __construct()
	{
		parent::__construct();

		$this->types = array('text'=>t('文字'),'image'=>t('图片'));
		$this->shows = array('flat'=>t('平铺'),'select'=>t('下拉'));
	}


	/*
	 *  获取数据集
	 */
	public function getall()
	{
		$data = array();

		$rows = $this->db()->orderby('listorder','asc')->getAll();

		foreach( $rows as &$r )
		{
			$r['value'] = json_decode($r['value'],true);
			$r['value'] = is_array($r['value']) ? $r['value'] : array();

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

			$data['value'] = json_decode($data['value'],true);
			$data['value'] = is_array($data['value']) ? $data['value'] : array();

			// 保存关联附件
            $data['dataid'] = "shop-spec-{$data['id']}";

			return $data;
		}

		return array();
	}

	/*
	 *  添加数据
	 */
	public function add($data)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));

		$data['listorder'] = $this->max('listorder') + 1;

		if ( $id = $this->insert($data) )
		{
            // 保存关联附件
            m('system.attachment')->setRelated("shop-spec-{$id}");

			return $id;
		}

		return false;
	}

	/*
	 *  编辑数据
	 */
	public function edit($data,$id)
	{
		if ( empty($data['name']) ) return $this->error(t('名称不能为空'));


		if ( $this->update($data,$id) )
		{
            // 保存关联附件
            m('system.attachment')->setRelated("shop-spec-{$id}");

			return $id;
		}

		return false;
	}

	/*
	 *  删除数据
	 */
	public function delete($id)
	{
		if ( parent::delete($id) )
		{
            // 删除关联附件
            m('system.attachment')->delRelated("shop-spec-{$id}");

			return true;
		}

		return false;
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

		return true;
	}
}
?>