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

// [user_group] 插入数据
$groupid = $this->db->from('user_group')->max('id') + 1;

$this->db->insert('user_group',array(
	'id' => $groupid,
	'modelid' => 'member',
	'name' => t('注册用户'),
	'description' => '',
	'settings' => array(
		'point'		=> 0,
		'amount'	=> 0,
	),
	'listorder' => '0',
	'disabled' => '0',
));

// 插入会员模型
$this->db->insert('user_model',array (
	'id' => 'member',
	'name' => t('会员'),
	'description' => '',
	'app' => 'member',
	'tablename' => 'member',
	'settings' => array(
		'register'	=> 1,
		'register_template'	=> 'member/register.php',
		'point'		=> 0,
		'amount'	=> 0,
		'groupid'	=>$groupid
	),
	'posts' => '0',
	'listorder' => '1',
	'disabled' => '0',
),true);


// [member] 创建
$this->db->schema('member')->drop();
$this->db->schema('member')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'mediumint', 'length'=>8, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('用户编号') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('会员扩展信息')
));
?>