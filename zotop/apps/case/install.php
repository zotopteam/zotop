<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_INSTALL') OR die('No direct access allowed.');

// [content_model_case] 创建
$this->db->table('content_model_case')->drop();
$this->db->table('content_model_case')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容编号') ),
		'content'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('案例内容') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('案例表')
));


// 该应用依赖于内容应用
$this->db->insert('content_model',array(
	'id'			=> 'case',
	'name'			=> t('案例'),
	'description'	=> t('工程案例'),
	'tablename'		=> 'content_model_case',
	'app'			=> 'case',
	'template'		=> 'case/detail.php',
	'listorder' 	=> 99,
));
?>