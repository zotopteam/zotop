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
 * 创建app数据表
 */
$this->db->table('app')->drop();
$this->db->table('app')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('应用ID') ),
		'name'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('应用名称') ),
		'description'=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('应用说明') ),
		'type'		=> array ( 'type'=>'char', 'length'=>10, 'notnull'=>true, 'comment' => t('应用类型，例如：module:模块,plugin:插件') ),
		'version'	=> array ( 'type'=>'char', 'length'=>20, 'notnull'=>true, 'comment' => t('应用版本号') ),
		'dir'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('应用相对于apps的目录') ),
		'tables'	=> array ( 'type'=>'varchar', 'length'=>200, 'default'=>null, 'comment' => t('数据表，多个以英文逗号隔开') ),
		'dependencies'	=> array ( 'type'=>'varchar', 'length'=>200, 'default'=>null, 'comment' => t('依赖的应用，多个以英文逗号隔开') ),
		'author'	=> array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('开发者') ),
		'email'		=> array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('开发者邮件') ),
		'homepage'	=> array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('开发者网站') ),
		'installtime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('安装时间') ),
		'updatetime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('升级时间') ),
		'listorder'	=> array ( 'type'=>'tinyint', 'length'=>3, 'default'=>'0', 'comment' => t('排序数字') ),
		'status'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('状态：0=禁用，1=启用') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('应用表')
));

/*
 * 创建config数据表
 */
$this->db->table('config')->drop();
$this->db->table('config')->create(array(
	'fields'=>array(
		'app'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('所属应用') ),
		'key'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('键名') ),
		'value'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('键值') ),
	),
	'index'=>array(
	),
	'unique'=>array(
		'app_key' => array ( 'app',  'key' ),
	),
	'primary'=>array (),
	'comment' => t('配置表')
));


/*
 * 创建alias数据表
 */
$this->db->table('alias')->drop();
$this->db->table('alias')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'app'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('隶属的应用') ),
		'source'	=> array ( 'type'=>'varchar', 'length'=>200, 'notnull'=>true, 'comment' => t('原始URI，如 content/detail/1') ),
		'alias'		=> array ( 'type'=>'varchar', 'length'=>200, 'notnull'=>true, 'comment' => t('别名，如：about') ),
	),
	'index'=>array(
	),
	'unique'=>array(
		'source'	 => array ( 'source' ),
		'alias'		 => array ( 'alias' ),
	),
	'primary'=>array ( 'id' ),
	'comment' => t('别名')
));

// [log] 创建
$this->db->table('log')->drop();
$this->db->table('log')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'state'		=> array ( 'type'=>'char', 'length'=>32, 'default'=>null, 'comment' => t('操作状态，success 或者 error') ),
		'app'	=> array ( 'type'=>'char', 'length'=>64, 'notnull'=>true, 'comment' => t('应用') ),
		'controller'=> array ( 'type'=>'char', 'length'=>64, 'notnull'=>true, 'comment' => t('控制器') ),
		'action'	=> array ( 'type'=>'char', 'length'=>64, 'notnull'=>true, 'comment' => t('动作') ),
		'url'		=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('页面链接') ),
		'data'		=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('数据') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'unsigned'=>true, 'comment' => t('用户编号') ),
		'username'	=> array ( 'type'=>'char', 'length'=>32, 'default'=>null, 'comment' => t('用户名') ),
		'createip'	=> array ( 'type'=>'char', 'length'=>15, 'notnull'=>true, 'comment' => t('创建IP') ),
		'createtime'=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('创建时间') ),
	),
	'index'=>array(
		'type'		 => array ( 'app' ),
		'userid'	 => array ( 'userid' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('用户日志')
));

/*
 * 创建user数据表
 */
$this->db->table('user')->drop();
$this->db->table('user')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'mediumint', 'length'=>8, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('用户编号') ),
		'username'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('用户名') ),
		'password'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('密码') ),
		'email'		=> array ( 'type'=>'char', 'length'=>50, 'notnull'=>true, 'comment' => t('电子邮箱') ),
		'emailstatus'=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('邮件验证，0=未验证，1=已经验证') ),
		'mobile'	=> array ( 'type'=>'char', 'length'=>13, 'default'=>null, 'comment' => t('手机号') ),
		'mobilestatus'=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('手机状态，0=未验证，1=已经验证') ),
		'nickname'	=> array ( 'type'=>'char', 'length'=>32, 'default'=>null, 'comment' => t('昵称') ),
		'avtar'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('头像状态，1=已上传头像，0=未上传头像') ),
		'modelid'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('用户模型，admin=管理员，member=会员') ),
		'groupid'	=> array ( 'type'=>'tinyint', 'length'=>3, 'notnull'=>true, 'default'=>'0', 'comment' => t('用户组编号') ),
		'point'		=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'unsigned'=>true, 'comment' => t('积分') ),
		'amount'	=> array ( 'type'=>'decimal', 'length'=>'8,2', 'notnull'=>true, 'default'=>'0.00', 'comment' => t('金钱') ),
		'loginip'	=> array ( 'type'=>'char', 'length'=>15, 'default'=>null, 'comment' => t('最后登入IP') ),
		'logintime'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('最后登入时间') ),
		'logintimes'=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'unsigned'=>true, 'comment' => t('登入次数') ),
		'regip'		=> array ( 'type'=>'char', 'length'=>15, 'default'=>null, 'comment' => t('注册IP') ),
		'regtime'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('注册时间') ),
		'updatetime'=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('更新时间') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('帐号状态，0=禁用，1=正常') ),
	),
	'index'=>array(
	),
	'unique'=>array(
		'username'	 => array ( 'username' ),
		'email'		 => array ( 'email' ),
		'mobile'	 => array ( 'mobile' ),
	),
	'primary'=>array ( 'id' ),
	'comment' => t('用户表')
));

// [user_model] 创建
$this->db->table('user_model')->drop();
$this->db->table('user_model')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型ID，如：admin,member') ),
		'name'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('名称') ),
		'description'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('说明') ),
		'app'		=> array ( 'type'=>'char', 'length'=>32, 'default'=>null, 'comment' => t('隶属应用ID') ),
		'tablename'	=> array ( 'type'=>'char', 'length'=>64, 'default'=>null, 'comment' => t('数据表名称（不含前缀）') ),
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
	'comment' => t('用户模型')
));

// [user_model] 插入数据
$this->db->insert('user_model',array (
  'id' => 'admin',
  'name' => t('系统用户'),
  'description' => t('系统管理及操作用户'),
  'app' => 'system',
  'tablename' => 'admin',
  'settings' => NULL,
  'posts' => '0',
  'listorder' => '0',
  'disabled' => '0',
));

// [user_field] 创建
$this->db->table('user_field')->drop();
$this->db->table('user_field')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'modelid'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('模型名称') ),
		'control'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('控件类型，如:text,editor等系统定义类型') ),
		'label'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('标签名称，显示给用户的名称') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('字段名称，数据库表中的字段名称') ),
		'type'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('字段数据类型，如：varchar，char ，int') ),
		'length'	=> array ( 'type'=>'tinyint', 'length'=>3, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('字段长度') ),
		'default'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('默认值') ),
		'notnull'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否必填，0，非必填，1：必填') ),
		'unique'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否只允许存在唯一值') ),
		'settings'	=> array ( 'type'=>'mediumtext', 'default'=>null, 'comment' => t('字段其它设置，如：radio，select的选项') ),
		'tips'		=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('字段提示信息') ),
		'listorder'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>'0', 'unsigned'=>true, 'comment' => t('字段排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('禁用，1：禁用，0：不禁用') ),
		'base'		=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('基本字段，是否在注册时显示') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('用户字段')
));

// [user_group] 创建
$this->db->table('user_group')->drop();
$this->db->table('user_group')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'mediumint', 'length'=>6, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('用户组编号') ),
		'modelid'	=> array ( 'type'=>'varchar', 'length'=>32, 'notnull'=>true, 'comment' => t('用户组模型，如：admin，member') ),
		'name'		=> array ( 'type'=>'char', 'length'=>30, 'notnull'=>true, 'comment' => t('名称') ),
		'description'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('用户组描述') ),
		'settings'	=> array ( 'type'=>'text', 'default'=>null, 'comment' => t('用户组设置') ),
		'listorder'	=> array ( 'type'=>'tinyint', 'length'=>3, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('0：启用，1：禁用') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('用户组')
));

// [user_group] 插入数据
$this->db->insert('user_group',array (
  'id' => '1',
  'modelid' => 'admin',
  'name' => t('超级管理员'),
  'description' => t('超级管理员拥有不受限制的完全权限'),
  'settings' => '',
  'listorder' => '1',
  'disabled' => '0',
));

// [user_group] 插入数据
$this->db->insert('user_group',array (
  'id' => '2',
  'modelid' => 'admin',
  'name' => t('管理员'),
  'description' => t('拥有部分管理权限'),
  'settings' => '',
  'listorder' => '2',
  'disabled' => '0',
));

// [user_group] 插入数据
$this->db->insert('user_group',array (
  'id' => '3',
  'modelid' => 'admin',
  'name' => t('编辑'),
  'description' => t('站点内容管理'),
  'settings' => '',
  'listorder' => '3',
  'disabled' => '0',
));


/*
 * 创建admin数据表
 */
$this->db->table('admin')->drop();
$this->db->table('admin')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'mediumint', 'length'=>8, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('用户编号') ),
		'realname'	=> array ( 'type'=>'char', 'length'=>20, 'notnull'=>true, 'comment' => t('真实姓名') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('管理员表')
));

/*
 * 创建admin_priv数据表
 */
$this->db->table('admin_priv')->drop();
$this->db->table('admin_priv')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'varchar', 'length'=>128, 'notnull'=>true, 'comment' => t('编号') ),
		'parentid'	=> array ( 'type'=>'varchar', 'length'=>128, 'default'=>null, 'comment' => t('父编号') ),
		'name'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('名称') ),
		'app'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('应用') ),
		'controller'=> array ( 'type'=>'char', 'length'=>32, 'default'=>null, 'comment' => t('控制器') ),
		'action'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('动作集合，多个用逗号隔开') ),
	),
	'index'=>array(
		'parentid'	 => array ( 'parentid' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('权限表')
));

/*
 * 插入权限数据
 */
$privs = array(
	array('id' => 'system_config','parentid' => 'system','name' => t('系统设置'),'app' => 'system','controller' => 'config','action'	=> ''),
	array('id' => 'system_admin','parentid' => 'system','name' => t('管理员管理'),'app' => 'system','controller' => 'admin','action'	=> ''),
	array('id' => 'system_app','parentid' => 'system','name' => t('应用管理'),'app' => 'system','controller' => 'app','action'	=> ''),
	array('id' => 'system_mine','parentid' => 'system','name' => t('个人管理'),'app' => 'system','controller' => 'mine','action'	=> ''),
	array('id' => 'system_log','parentid' => 'system','name' => t('系统操作日志'),'app' => 'system','controller' => 'log','action'	=> ''),
	array('id' => 'system_system','parentid' => 'system','name' => t('系统管理'),'app' => 'system','controller' => 'system','action'	=> ''),
);

foreach( $privs as $p )
{
	$this->db->insert('admin_priv', $p);
}


// [admin_priv_group] 创建
$this->db->table('admin_priv_group')->drop();
$this->db->table('admin_priv_group')->create(array(
	'fields'=>array(
		'privid'	=> array ( 'type'=>'varchar', 'length'=>128, 'notnull'=>true, 'comment' => t('权限编号') ),
		'groupid'	=> array ( 'type'=>'tinyint', 'length'=>3, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('角色编号') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'groupid',  'privid' ),
	'comment' => t('管理员角色权限关系表')
));


/*
 * 创建ipbanned数据表
 */
$this->db->table('ipbanned')->drop();
$this->db->table('ipbanned')->create(array(
	'fields'=>array(
		'ip'		=> array ( 'type'=>'char', 'length'=>15, 'notnull'=>true, 'comment' => t('ip地址') ),
		'expires'	=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('有效期') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'ip' ),
	'comment' => t('ip禁止')
));


/*
 * 创建badword数据表
 */
$this->db->table('badword')->drop();
$this->db->table('badword')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('编号') ),
		'word'		=> array ( 'type'=>'char', 'length'=>30, 'notnull'=>true, 'comment' => t('敏感词') ),
		'replace'	=> array ( 'type'=>'char', 'length'=>30, 'default'=>null, 'comment' => t('替换词语') ),
		'level'		=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('级别，0：敏感词语 1：危险词语 直接去除') ),
		'lasttime'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'unsigned'=>true, 'comment' => t('最后使用时间') ),
		'listorder'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'comment' => t('排序') ),
	),
	'index'=>array(
		'word_replace_listorder' => array ( 'word',  'replace',  'listorder' ),
	),
	'unique'=>array(
		'word'		 => array ( 'word' ),
	),
	'primary'=>array ( 'id' ),
	'comment' => t('敏感词管理')
));

/*
 * 创建attachment数据表
 */
$this->db->table('attachment')->drop();
$this->db->table('attachment')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('编号') ),
		'folderid'	=> array ( 'type'=>'smallint', 'length'=>5, 'default'=>'0', 'unsigned'=>true, 'comment' => t('文件夹编号') ),
		'app'	=> array ( 'type'=>'char', 'length'=>32, 'notnull'=>true, 'comment' => t('隶属的应用编号') ),
		'dataid'	=> array ( 'type'=>'char', 'length'=>64, 'default'=>null, 'comment' => t('隶属数据编号') ),
		'field'		=> array ( 'type'=>'char', 'length'=>32, 'default'=>null, 'comment' => t('字段名称') ),
		'guid'		=> array ( 'type'=>'char', 'length'=>32, 'default'=>null, 'comment' => t('文件唯一编号') ),
		'name'		=> array ( 'type'=>'char', 'length'=>100, 'notnull'=>true, 'comment' => t('文件真实名称') ),
		'type'		=> array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('文件类型，如：image，file，audio等') ),
		'ext'		=> array ( 'type'=>'char', 'length'=>5, 'notnull'=>true, 'comment' => t('文件格式，如：jpg') ),
		'size'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('文件大小') ),
		'path'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('文件路径') ),
		'url'		=> array ( 'type'=>'varchar', 'length'=>100, 'notnull'=>true, 'comment' => t('文件URL') ),
		'description'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('描述') ),
		'width'		=> array ( 'type'=>'smallint', 'length'=>6, 'default'=>'0', 'comment' => t('宽度') ),
		'height'	=> array ( 'type'=>'smallint', 'length'=>6, 'default'=>'0', 'comment' => t('高度') ),
		'status'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'comment' => t('状态，0=未使用 1=使用，2=常用') ),
		'userid'	=> array ( 'type'=>'mediumint', 'length'=>8, 'default'=>'0', 'unsigned'=>true, 'comment' => t('用户编号') ),
		'uploadip'	=> array ( 'type'=>'char', 'length'=>15, 'default'=>null, 'comment' => t('上传IP') ),
		'uploadtime'=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('上传时间') ),
	),
	'index'=>array(
		'folderid'	 => array ( 'folderid' ),
		'dataid'	 => array ( 'dataid' ),
		'type'		 => array ( 'type' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('附件表')
));

/*
 * 创建attachment_folder数据表
 */
$this->db->table('attachment_folder')->drop();
$this->db->table('attachment_folder')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('分类编号') ),
		'parentid'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('父分类编号') ),
		'name'		=> array ( 'type'=>'varchar', 'length'=>50, 'notnull'=>true, 'comment' => t('分类名称') ),
		'listorder'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('分类排序') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('附件分类')
));

$this->db->insert('attachment_folder',array(
	'id'		=> 1,
	'parentid'	=> 0,
	'name'		=> t('常用图片'),
	'listorder'	=> 1,
));

$this->db->insert('attachment_folder',array(
	'id'		=> 2,
	'parentid'	=> 0,
	'name'		=> t('常用文件'),
	'listorder'	=> 2,
));
?>