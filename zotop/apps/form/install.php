<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_INSTALL') OR die('No direct access allowed.');

// [form] 创建
$this->db->table('form')->drop();
$this->db->table('form')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('表单名称') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('表单说明') ),
		'table'		=> array ( 'type'=>'char', 'length'=>20, 'notnull'=>true, 'comment' => t('数据表名称') ),
		'settings'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('表单设置') ),
		'listorder'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否禁用，0：启用，1：禁用') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('表单') 
));


// [form_fields] 创建
$this->db->table('form_fields')->drop();
$this->db->table('form_fields')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'formid'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('表单编号') ),
		'control'	=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('控件类型，如text，number等') ),
		'label'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('显示的标签名称') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('数据库中的字段名称') ),
		'type'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('字段类型，如char，varchar，int等') ),
		'length'	=> array ( 'type'=>'tinyint', 'length'=>3, 'default'=>null, 'unsigned'=>true, 'comment' => t('字段长度') ),
		'default'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('默认值') ),
		'notnull'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否允许空子，0：允许，1：不允许') ),
		'unique'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否唯一，0：非唯一，1：必须是唯一值') ),
		'settings'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('字段其它设置，如radio，select等的选项') ),
		'tips'		=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('字段提示信息') ),
		'show'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否在后台列表显示，0：不显示，1：显示') ),
		'post'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'1', 'unsigned'=>true, 'comment' => t('是否允许前台填写提交，0：不允许，1：允许') ),
		'search'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>null, 'unsigned'=>true, 'comment' => t('是否允许搜索，0：禁止，1：允许') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序字段') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否禁用，0：未禁用，1：禁用') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('表单字段') 
));

?>