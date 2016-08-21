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


// 创建 [block_category] 数据表
$this->db->dropTable('block_category');
$this->db->createTable('block_category',array(
	'fields'=>array(
		'id'          => array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('编号') ),
		'name'        => array ( 'type'=>'char', 'length'=>50, 'notnull'=>true, 'comment' => t('名称') ),
		'description' => array ( 'type'=>'text', 'default'=>null, 'comment' => t('说明') ),
		'listorder'   => array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'posts'       => array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('数量') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('区块分类')
));

// 默认插入的数据
$default_category = array(
	'1'	=>	array('id' => 1, 'name' => t('全局区块'), 'description' => t('网站全局公用的区块'), 'listorder' => 1, 'posts' => 0 ),
);

// 插入数据
foreach( $default_category as $category )
{
	$this->db->table('block_category')->data($category)->insert();
}


// [block] 创建
$this->db->dropTable('block');
$this->db->createTable('block',array(
	'fields'=>array(
		'id'          => array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'categoryid'  => array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('分类编号') ),
		'type'        => array ( 'type'=>'char', 'length'=>20, 'notnull'=>true, 'default'=>'html', 'comment' => t('类型，html:内容,list:列表,hand:手动,text:文本') ),
		'name'        => array ( 'type'=>'char', 'length'=>50, 'notnull'=>true, 'comment' => t('名称') ),
		'description' => array ( 'type'=>'text', 'default'=>null, 'comment' => t('说明') ),
		'rows'        => array ( 'type'=>'tinyint', 'length'=>3, 'default'=>'0', 'comment' => t('行数，0为无限制') ),
		'data'        => array ( 'type'=>'text', 'default'=>null, 'comment' => t('数据') ),
		'template'    => array ( 'type'=>'text', 'default'=>null, 'comment' => t('模版') ),
		'interval'    => array ( 'type'=>'smallint', 'length'=>5, 'default'=>'3600', 'comment' => t('更新频率，单位秒，0：手动更新') ),
		'fields'      => array ( 'type'=>'text', 'default'=>null, 'comment' => t('字段设置') ),
		'commend'     => array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否允许推送，0：不允许，1：允许且需审核，2：允许且无需审核') ),
		'listorder'   => array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'comment' => t('排序') ),
		'userid'      => array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'comment' => t('用户编号') ),
		'createtime'  => array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('创建时间') ),
		'updatetime'  => array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('更新时间') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('区块表') 
));

// [block_datalist] 创建
$this->db->dropTable('block_datalist');
$this->db->createTable('block_datalist',array(
	'fields'=>array(
		'id'        => array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'dataid'    => array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('数据编号') ),
		'blockid'   => array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'comment' => t('区块编号') ),
		'app'       => array ( 'type'=>'char', 'length'=>64, 'default'=>null, 'comment' => t('应用编号') ),		
		'data'      => array ( 'type'=>'text', 'notnull'=>true, 'comment' => t('数据') ),
		'userid'    => array ( 'type'=>'mediumint', 'length'=>8, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('创建人编号') ),
		'listorder' => array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'stick'     => array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否固顶，0：不固顶，1：固顶') ),
		'status'    => array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('状态') ),
	),
	'index'=>array(
		'blockid_listorder' => array ( 'blockid',  'listorder' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('区块数据列表') 
));
?>