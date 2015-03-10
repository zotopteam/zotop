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

// [content] 创建	
$this->db->table('content')->drop();
$this->db->table('content')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'parentid'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('父编号') ),
		'categoryid'=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('分类') ),
		'modelid'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型ID') ),
		'title'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('标题') ),
		'style'		=> array ( 'type'=>'char', 'length'=>40, 'default'=>null, 'comment' => t('标题样式') ),
		'alias'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('别名') ),
		'url'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('链接') ),
		'image'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('缩略图') ),
		'keywords'	=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('关键词') ),
		'summary'	=> array ( 'type'=>'varchar', 'length'=>500, 'default'=>null, 'comment' => t('摘要') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>'0', 'comment' => t('会员编号') ),
		'template'	=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('模版') ),
		'hits'		=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('点击数') ),
		'comment'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('评论，1=允许，0=禁止') ),
		'comments'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'unsigned'=>true, 'comment' => t('评论数') ),
		'childs'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('子内容个数') ),
		'createtime'=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容时间') ),
		'updatetime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('更新时间') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'stick'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否固顶，0：不固顶，1：固顶') ),
		'status'	=> array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('状态') ),
	),
	'index'=>array(
		'parentid'	 => array ( 'parentid' ),
		'categoryid' => array ( 'categoryid' ),
		'listorder'	 => array ( 'listorder' ),
		'stick'		 => array ( 'stick' ),
		'status'	 => array ( 'status' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('内容主表') 
));



// [content_model] 创建	
$this->db->table('content_model')->drop();
$this->db->table('content_model')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型ID，如：news') ),
		'name'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('名称') ),
		'description'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('说明') ),
		'app'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('隶属应用ID') ),
		'model'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('对应app中的模型') ),
		'template'	=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('详细页面模版') ),
		'childs'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('下级模型编号') ),
		'posts'		=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>'0', 'unsigned'=>true, 'comment' => t('数据量') ),
		'listorder'	=> array ( 'type'=>'tinyint', 'length'=>3, 'default'=>null, 'comment' => t('排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('禁用') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('内容模型') 
));

$this->db->table('content_field')->drop();
$this->db->table('content_field')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'modelid'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型编号') ),
		'control'	=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('控件类型，如text，number等') ),
		'label'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('显示的标签名称') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('数据库中的字段名称') ),
		'type'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('字段类型，如char，varchar，int等') ),
		'length'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'unsigned'=>true, 'comment' => t('字段长度') ),
		'default'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('默认值') ),
		'notnull'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否允许空子，0：允许，1：不允许') ),
		'unique'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否唯一，0：非唯一，1：必须是唯一值') ),
		'settings'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('字段其它设置，如radio，select等的选项') ),
		'tips'		=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('字段提示信息') ),
		'base'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否在添加编辑的左侧显示，0：否，1：是') ),
		'post'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'1', 'unsigned'=>true, 'comment' => t('是否允许前台填写提交，0：不允许，1：允许') ),
		'search'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否允许搜索，0：禁止，1：允许') ),
		'system'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否为系统字段，0：自定义字段，1：系统字段') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序字段') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否禁用，0：未禁用，1：禁用') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('内容模型的字段表') 
));

/*
 * 创建content_category数据表
 */
$this->db->table('content_category')->drop();
$this->db->table('content_category')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('编号') ),
		'rootid'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('根编号') ),
		'parentid'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('父编号') ),
		'childid'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('子栏目编号') ),
		'parentids'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('全部父编号') ),
		'childids'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('全部子编号') ),
		//'type'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('类型，0=栏目，1=单页面，2=链接') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>50, 'notnull'=>true, 'comment' => t('名称') ),
		'alias'		=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('别名/英文名') ),
		'title'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('标题') ),
		'image'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('图片') ),
		'keywords'	=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('关键词') ),
		'description' => array ( 'type'=>'text', 'default'=>null, 'comment' => t('描述') ),
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
	'comment' => t('内容栏目表')
));

// [content_tag] 创建
$this->db->table('content_tag')->drop();
$this->db->table('content_tag')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('标签') ),
		'hits'		=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'comment' => t('点击次数') ),
		'quotes'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'comment' => t('引用次数') ),
	),
	'index'=>array(
		'hits'		 => array ( 'hits' ),
		'quotes'	 => array ( 'quotes' ),
	),
	'unique'=>array(
		'name'		 => array ( 'name' ),
	),
	'primary'=>array ( 'id' ),
	'comment' => t('内容标签')
));

// [content_tagdata] 创建
$this->db->table('content_tagdata')->drop();
$this->db->table('content_tagdata')->create(array(
	'fields'=>array(
		'tagid'		=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('标签编号') ),
		'contentid'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('内容编号') ),
	),
	'index'=>array(
		'tagid'		 => array ( 'tagid' ),
		'contentid'	 => array ( 'contentid' ),
	),
	'unique'=>array(
		'tagid_contentid' => array ( 'tagid',  'contentid' ),
	),
	'primary'=>array (),
	'comment' => t('tag和content对应关系')
));

?>