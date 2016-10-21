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
 * 创建guestbook数据表
 */
$this->db->dropTable('guestbook');
$this->db->createTable('guestbook',array(
	'fields'=>array(
		'id'          => array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'categoryid'  => array ( 'type'=>'tinyint', 'length'=>3, 'default'=>'0', 'comment' => t('分类') ),
		'content'     => array ( 'type'=>'text', 'notnull'=>true, 'comment' => t('留言内容') ),
		'userid'      => array ( 'type'=>'mediumint', 'length'=>8, 'default'=>'0', 'comment' => t('会员编号') ),
		'name'        => array ( 'type'=>'char', 'length'=>20, 'default'=>null, 'comment' => t('姓名') ),
		'email'       => array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('电子邮件') ),
		'mobile'      => array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('手机号') ),
		'qq'          => array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('QQ') ),
		'homepage'    => array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('主页') ),
		'data'        => array ( 'type'=>'text', 'default'=>null, 'comment' => t('用户信息扩展') ),
		'createtime'  => array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'comment' => t('留言时间') ),
		'createip'    => array ( 'type'=>'char', 'length'=>15, 'notnull'=>true, 'comment' => t('留言ip') ),
		'reply'       => array ( 'type'=>'text', 'default'=>null, 'comment' => t('回复内容') ),
		'replyuserid' => array ( 'type'=>'mediumint', 'length'=>8, 'default'=>null, 'comment' => t('') ),
		'replytime'   => array ( 'type'=>'int', 'length'=>10, 'default'=>null, 'comment' => t('回复时间') ),
		'status'      => array ( 'type'=>'char', 'length'=>10, 'default'=>null, 'comment' => t('留言状态') ),
	),
	'index'=>array(
		'status'	 => array ( 'status' ),
		'createtime' => array ( 'createtime' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('留言表')
));

// 插入开始菜单
$system_navbar = array(
	'id'       => 'guestbook',
	'parentid' => 'system_navbar',
	'rootid'   => 'system_navbar',
	'app'      => 'guestbook',
	'listorder'=> 3,
	'data'     => array(
		'text'   => t('留言'),
		'href'   => 'guestbook/admin',
		'active' => 'guestbook/*'
	)
);

$this->db->table('menu')->data($system_navbar)->insert();
?>