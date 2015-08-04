<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_INSTALL') OR die('No direct access allowed.');

// [dbimport] 创建
$this->db->schema('dbimport')->drop();
$this->db->schema('dbimport')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('规则名称') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('规则说明') ),
		'source'	=> array ( 'type'=>'text', 'notnull'=>true, 'comment' => t('数据源') ),
		'table'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('目标数据表') ),
		'maps'		=> array ( 'type'=>'text', 'notnull'=>true, 'comment' => t('字段对应关系') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('数据导入规则表') 
));
?>