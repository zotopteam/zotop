<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_INSTALL') OR die('No direct access allowed.');

// [kefu] 创建
$this->db->dropTable('kefu');
$this->db->createTable('kefu',array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'type'		=> array ( 'type'=>'char', 'length'=>20, 'notnull'=>true, 'comment' => t('在线客服类型，如：qq，mobile') ),
		'account'	=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('账号，如qq号码') ),
		'text'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('说明、文字、任意代码') ),
		'style'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('样式') ),
		'listorder'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'comment' => t('排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('禁用：1') ),
	),
	'index'=>array(
		'listorder'	 => array ( 'listorder' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('客服数据表') 
));
?>