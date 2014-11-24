<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_INSTALL') OR die('No direct access allowed.');


// [content_model_solution] 创建
$this->db->table('content_model_solution')->drop();
$this->db->table('content_model_solution')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容编号') ),
		'content'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('方案内容') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('方案表')
));


// 该应用依赖于内容应用
$this->db->insert('content_model',array(
	'id'			=> 'solution',
	'name'			=> t('方案'),
	'description'	=> t('解决方案'),
	'tablename'		=> 'content_model_solution',
	'app'			=> 'solution',
	'template'		=> 'solution/detail.php',
	'listorder' 	=> 99,
));
?>