<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_INSTALL') OR die('No direct access allowed.');

/**
 * 安装程序
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */

// [shop_goods] 创建
$this->db->dropTable('shop_goods');
$this->db->createTable('shop_goods', array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'sn'		=> array ( 'type'=>'char', 'length'=>14, 'notnull'=>true, 'comment' => t('商品货号') ),
		'typeid'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'comment' => t('商品类型') ),
		'brandid'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>null, 'unsigned'=>true, 'comment' => t('品牌ID') ),
		'categoryid'=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'unsigned'=>true, 'comment' => t('分类编号') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('商品名称') ),
		'keywords'	=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('关键词，多个用逗号隔开') ),
		'description'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('商品卖点') ),
		'thumb'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('商品主图') ),
		'gallery'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('商品图集') ),
		'content'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('详细介绍') ),
		'status'	=> array ( 'type'=>'char', 'length'=>10, 'default'=>'publish', 'comment' => t('状态，publish：上架，disabled：下架，draft：草稿，trash：回收站') ),
		'updatetime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('更新时间') ),
		'createtime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('创建时间') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'unsigned'=>true, 'comment' => t('发布者') ),
		'weight'	=> array ( 'type'=>'tinyint', 'length'=>3, 'default'=>'0', 'unsigned'=>true, 'comment' => t('权重，用于排序') ),
	),
	'index'=>array(
		'typeid_categoryid_createtime_weight' => array ( 'typeid',  'categoryid',  'createtime',  'weight' ),
		'keywords'	 => array ( 'keywords' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('商品数据表')
));

// [shop_goods_attr] 创建
$this->db->dropTable('shop_goods_attr');
$this->db->createTable('shop_goods_attr', array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'char', 'length'=>15, 'notnull'=>true, 'comment' => t('商品属性编号，商品ID-属性ID') ),
		'typeid'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('类型ID') ),
		'goodsid'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('商品ID') ),
		'attrid'	=> array ( 'type'=>'tinyint', 'length'=>3, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('属性编号') ),
		'attrvalue'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('属性值') ),
	),
	'index'=>array(
		'goodsid_attrval' => array ( 'goodsid',  'attrvalue' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('商品详细属性表')
));

// [shop_category] 创建
$this->db->dropTable('shop_category');
$this->db->createTable('shop_category', array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('编号') ),
		'rootid'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('根编号') ),
		'parentid'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('父编号') ),
		'childid'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('子栏目编号') ),
		'parentids'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('全部父编号') ),
		'childids'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('全部子编号') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>50, 'notnull'=>true, 'comment' => t('名称') ),
		'alias'		=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('别名/英文名') ),
		'title'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('标题') ),
		'image'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('图片') ),
		'keywords'	=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('关键词') ),
		'description'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('描述') ),
		'content'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('简介') ),
		'settings'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('其它设置') ),
		'listorder'	=> array ( 'type'=>'tinyint', 'length'=>3, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('禁用') ),
	),
	'index'=>array(
		'parentid_listorder_disabled' => array ( 'parentid',  'listorder',  'disabled' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('商品分类')
));

// [shop_type] 创建
$this->db->dropTable('shop_type');
$this->db->createTable('shop_type', array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('类型名称') ),
		'physical'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'1', 'unsigned'=>true, 'comment' => t('是否为实体商品，1：实体，0：虚拟') ),
		'attrs'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('属性，商品属性，如货号、重量、颜色等') ),
		'params'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('参数，允许分组') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否禁用，0：启用，1：禁用') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('商品类型')
));

// [shop_attr] 创建
/*
$this->db->dropTable('shop_attr');
$this->db->createTable('shop_attr', array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('属性ID') ),
		'typeid'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('类型编号') ),
		'input'		=> array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('输入控件类型,radio:单选,checkbox:复选,select:下拉,text:文本') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('名称') ),
		'value'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('属性值(逗号分隔)') ),
		'search'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'comment' => t('是否支持搜索0不支持1支持') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('商品属性')
));
*/

// [shop_spec] 创建
$this->db->dropTable('shop_spec');
$this->db->createTable('shop_spec', array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'name'		=> array ( 'type'=>'char', 'length'=>50, 'notnull'=>true, 'comment' => t('规格名称') ),
		'value'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('规格数据') ),
		'show'		=> array ( 'type'=>'char', 'length'=>10, 'notnull'=>true, 'default'=>'flat', 'comment' => t('展示方式，flat:平铺，select:下拉') ),
		'type'		=> array ( 'type'=>'char', 'length'=>10, 'notnull'=>true, 'default'=>'text', 'comment' => t('类型，text:文字，image:图片') ),
		'description'=> array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('描述') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否禁用，0：启用，1：禁用') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
	),
	'index'=>array(
		'disabled'	 => array ( 'disabled' ),
		'listorder'	 => array ( 'listorder' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('商品规格')
));



// [shop_brand] 创建
$this->db->dropTable('shop_brand');
$this->db->createTable('shop_brand', array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>50, 'notnull'=>true, 'comment' => t('品牌名称') ),
		'logo'		=> array ( 'type'=>'varchar', 'length'=>200, 'default'=>null, 'comment' => t('品牌logo图片地址') ),
		'url'		=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('品牌网址') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('品牌介绍') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否禁用，0：启用，1：禁用') ),
	),
	'index'=>array(
		'listorder'	 => array ( 'listorder' ),
		'disabled'	 => array ( 'disabled' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('商品品牌')
));
?>