<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 属性
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class shop_model_goods_attr extends model
{
	protected $pk = 'id';
	protected $table = 'shop_goods_attr';


	public function get($goodsid)
	{
		$data = $this->field('attrid','attrvalue')->where('goodsid', $goodsid)->select();

		$data = arr::hashmap($data, 'attrid', 'attrvalue');

		return $data;
	}

	public function set($goodsid, $attrs)
	{
		// 删除全部属性
		$this->where('goodsid', $goodsid)->delete();

		// 保存属性
		if ( is_array($attrs) )
		{
			$typeid = m('shop.goods')->where('id',$goodsid)->getField('typeid');

			foreach( $attrs as $id=>$value )
			{
				$this->insert(array(
					'id'		=> $goodsid.'-'.$id,
					'typeid'	=> $typeid,
					'goodsid'	=> $goodsid,
					'attrid'	=> $id,
					'attrvalue'	=> is_array($value) ? implode(',', $value) : $value,
				));
			}

			return true;
		}

		return false;
	}
}
?>