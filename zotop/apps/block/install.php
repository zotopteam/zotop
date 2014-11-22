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
$this->db->table('block_category')->drop();
$this->db->table('block_category')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('编号') ),
		'name'		=> array ( 'type'=>'char', 'length'=>50, 'notnull'=>true, 'comment' => t('名称') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('说明') ),
		'listorder'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'posts'		=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('数量') ),
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
	'1'	=>	array('id' => 1, 'name' => t('首页区块'), 'description' => t('网站首页区块'), 'listorder' => 1, 'posts' => 0 ),
);

// 插入数据
foreach( $default_category as $category )
{
	$this->db->insert('block_category',$category);
}


// [block] 创建
$this->db->table('block')->drop();
$this->db->table('block')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'categoryid'=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('分类编号') ),
		'type'		=> array ( 'type'=>'char', 'length'=>20, 'notnull'=>true, 'default'=>'html', 'comment' => t('类型，html:内容,list:列表,hand:手动,text:文本') ),
		'name'		=> array ( 'type'=>'char', 'length'=>50, 'notnull'=>true, 'comment' => t('名称') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('说明') ),
		'rows'		=> array ( 'type'=>'tinyint', 'length'=>3, 'default'=>'0', 'comment' => t('行数，0为无限制') ),
		'data'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('数据') ),
		'template'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('模版') ),
		'interval'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'3600', 'comment' => t('更新频率，单位秒，0：手动更新') ),
		'fields'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('字段设置') ),
		'commend'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否允许推送，0：不允许，1：允许且需审核，2：允许且无需审核') ),
		'listorder'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'comment' => t('排序') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'comment' => t('用户编号') ),
		'createtime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('创建时间') ),
		'updatetime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('更新时间') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('区块表') 
));
// [block_datalist] 创建
$this->db->table('block_datalist')->drop();
$this->db->table('block_datalist')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'blockid'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'comment' => t('区块编号') ),
		'app'		=> array ( 'type'=>'char', 'length'=>64, 'default'=>null, 'comment' => t('应用编号') ),
		'contentid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'unsigned'=>true, 'comment' => t('内容编号') ),
		'title'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('标题') ),
		'style'		=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('标题样式') ),
		'url'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('链接') ),
		'image'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('图片') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('摘要') ),
		'createtime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('时间') ),
		'c1'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段1') ),
		'c2'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段2') ),
		'c3'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段3') ),
		'c4'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段4') ),
		'c5'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段5') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('创建人编号') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'stick'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否固顶，0：不固顶，1：固顶') ),
		'status'	=> array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('状态') ),
	),
	'index'=>array(
		'blockid_listorder' => array ( 'blockid',  'listorder' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('区块数据列表') 
));

// [block_datalist] 创建
$this->db->table('block_datalist')->drop();
$this->db->table('block_datalist')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'blockid'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'comment' => t('区块编号') ),
		'app'		=> array ( 'type'=>'char', 'length'=>64, 'default'=>null, 'comment' => t('应用编号') ),
		'contentid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'unsigned'=>true, 'comment' => t('内容编号') ),
		'title'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('标题') ),
		'style'		=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('标题样式') ),
		'url'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('链接') ),
		'image'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('图片') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('摘要') ),
		'createtime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('时间') ),
		'c1'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段1') ),
		'c2'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段2') ),
		'c3'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段3') ),
		'c4'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段4') ),
		'c5'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('自定义字段5') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('创建人编号') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'stick'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否固顶，0：不固顶，1：固顶') ),
		'status'	=> array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('状态') ),
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