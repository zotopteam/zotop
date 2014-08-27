<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_UNINSTALL') OR die('No direct access allowed.');

/**
 * 卸载程序
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */

// 卸载前检查数据表是否有数据，有则不允许删除
if ( $this->db->from('goods')->count() )
{
	return $this->error(t('卸载失败，尚有商品存在，请先清空全部商品', 'goods'));
}


/*
 * 删除数据表
 */
$this->db->table('goods')->drop();
$this->db->table('shop_goods_attr')->drop();

$this->db->table('goods_type')->drop();
?>