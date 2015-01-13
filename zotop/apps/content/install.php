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

/*
 * 创建content数据表
 */
$this->db->table('content')->drop();
$this->db->table('content')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'categoryid'=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('分类') ),
		'app'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('应用ID') ),
		'modelid'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型ID') ),
		'title'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('标题') ),
		'style'		=> array ( 'type'=>'char', 'length'=>40, 'default'=>null, 'comment' => t('标题样式') ),
		'alias'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('别名') ),
		'url'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('链接') ),
		'thumb'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('缩略图') ),
		'keywords'	=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('关键词') ),
		'summary'	=> array ( 'type'=>'varchar', 'length'=>500, 'default'=>null, 'comment' => t('摘要') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>'0', 'comment' => t('会员编号') ),
		'template'	=> array ( 'type'=>'varchar', 'length'=>50, 'default'=>null, 'comment' => t('模版') ),
		'hits'		=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('点击数') ),
		'comment'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('评论，1=允许，0=禁止') ),
		'comments'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'unsigned'=>true, 'comment' => t('评论数') ),
		'createtime'=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容时间') ),
		'updatetime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('更新时间') ),
		'weight'	=> array ( 'type'=>'tinyint', 'length'=>3, 'default'=>'0', 'unsigned'=>true, 'comment' => t('权重') ),
		'status'	=> array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('状态') ),
	),
	'index'=>array(
		'categoryid' => array ( 'categoryid',  'modelid',  'createtime',  'weight',  'status' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('内容表')
));

/*
 * 创建content_model_article表
 */
$this->db->table('content_model_article')->drop();
$this->db->table('content_model_article')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容编号') ),
		'author'	=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>false, 'comment' => t('作者') ),
		'source'	=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>false, 'comment' => t('来源') ),
		'content'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('内容') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('文章表')
));

/*
 * 创建content_model_page表
 */
$this->db->table('content_model_page')->drop();
$this->db->table('content_model_page')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容编号') ),
		'content'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('内容') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('页面表')
));

/*
 * 创建 content_model_gallery 表
 */
$this->db->table('content_model_gallery')->drop();
$this->db->table('content_model_gallery')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('内容编号') ),
		'total'		=> array ( 'type'=>'tinyint', 'length'=>3, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('图片个数') ),
		'gallery'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('图片集，array数据') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('图集表')
));


// [content_model_download] 创建
$this->db->table('content_model_download')->drop();
$this->db->table('content_model_download')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('内容编号') ),		
		'local'		=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('0：远程文件，1：本地文件') ),
		'filepath'	=> array ( 'type'=>'varchar', 'length'=>255, 'notnull'=>true, 'comment' => t('文件地址或者下载页面地址') ),
		'filename'	=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('文件名称') ),
		'filesize'	=> array ( 'type'=>'char', 'length'=>20, 'default'=>null, 'comment' => t('文件大小') ),
		'fileext'	=> array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('文件格式') ),
		'download'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('下载次数') ),
		'content'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('介绍') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('下载表') 
));


// [content_model] 创建	
$this->db->table('content_model')->drop();
$this->db->table('content_model')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型ID，如：news') ),
		'name'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('名称') ),
		'description'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('说明') ),
		'tablename'	=> array ( 'type'=>'char', 'length'=>64, 'default'=>null, 'comment' => t('数据表名称（不含前缀），一般为：conten_model_[id]，为空则为虚拟模型，如链接模型') ),
		'app'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('隶属应用ID') ),
		'model'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('对应app中的模型') ),
		'template'	=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('详细页面模版') ),
		'settings'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('模型设置') ),
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

// [content_model] 插入数据
$this->db->insert('content_model',array (
  'id' => 'page',
  'name' => '页面',
  'description' => '单页面或普通页面',
  'tablename' => 'content_model_page',
  'app' => 'content',
  'model' => 'page',
  'template' => 'content/detail_page.php',
  'settings' => NULL,
  'posts' => '0',
  'listorder' => '1',
  'disabled' => '0',
));

$this->db->insert('content_model',array (
  'id' => 'article',
  'name' => '文章',
  'description' => '新闻或文章',
  'tablename' => 'content_model_article',
  'app' => 'content',
  'model' => 'article',
  'template' => 'content/detail_article.php',
  'settings' => NULL,
  'posts' => '0',
  'listorder' => '2',
  'disabled' => '0',
));

$this->db->insert('content_model',array (
  'id' => 'gallery',
  'name' => '组图',
  'description' => '图集或画廊',
  'tablename' => 'content_model_gallery',
  'app' => 'content',
  'model' => 'gallery',
  'template' => 'content/detail_gallery.php',
  'settings' => NULL,
  'posts' => '0',
  'listorder' => '3',
  'disabled' => '0',
));

$this->db->insert('content_model',array (
  'id' => 'download',
  'name' => '下载',
  'description' => '文件下载',
  'tablename' => 'content_model_download',
  'app' => 'content',
  'model' => 'download',
  'template' => 'content/detail_download.php',
  'settings' => NULL,
  'posts' => '0',
  'listorder' => '4',
  'disabled' => '0',
));

$this->db->insert('content_model',array (
  'id' => 'link',
  'name' => '链接',
  'description' => '指向其它页面的链接',
  'tablename' => NULL,
  'app' => 'content',
  'model' => 'link',
  'template' => NULL,
  'settings' => NULL,
  'posts' => '0',
  'listorder' => '99',
  'disabled' => '0',
));


// [content_field] 创建	
$this->db->table('content_field')->drop();
$this->db->table('content_field')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'modelid'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型编号') ),
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
		'list'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否在列表显示，0：不显示，1：显示') ),
		'show'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'1', 'unsigned'=>true, 'comment' => t('是否在详细页面显示，0：不显示，1：显示') ),
		'post'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'1', 'unsigned'=>true, 'comment' => t('是否允许前台填写提交，0：不允许，1：允许') ),
		'search'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否允许搜索，0：禁止，1：允许') ),
		'order'		=> array ( 'type'=>'char', 'length'=>4, 'default'=>null, 'comment' => t('是否参与排序，空=不参与，ASC=顺序，DESC=倒序') ),
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
		'type'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('类型，0=栏目，1=单页面，2=链接') ),
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