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
$groupid = $this->db->table('user_group')->max('id') + 1;

$this->db->table('user_group')->data(array(
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
))->insert();

// 插入会员模型
$this->db->table('user_model')->data(array(
	'id' => 'member',
	'name' => t('会员'),
	'description' => '',
	'app' => 'member',
	'tablename' => 'member',
	'settings' => array(
		'register'	=> 1,
		'point'		=> 0,
		'amount'	=> 0,
		'groupid'	=>$groupid
	),
	'posts' => '0',
	'listorder' => '1',
	'disabled' => '0',
))->insert(true);

// 插入会员系统字段

$member_system_fields = array(
	'username' => array('control'=>'username','label'=>t('用户名'),'name'=>'username','type'=>'varchar','length'=>'32','notnull'=>'1','settings'=>array('minlength'=>'2','maxlength'=>'32'),'base'=>'1','tips'=>t('4-20位字符，允许中文、英文、数字和下划线，不能含有特殊字符')),
	'password' => array('control'=>'password','label'=>t('密码'),'name'=>'password','type'=>'varchar','length'=>'32','notnull'=>'1','settings'=>array('minlength'=>'6','maxlength'=>'32'),'base'=>'1','tips'=>t('6-20位字符，可使用英文、数字或者符号组合，不建议使用纯数字、纯字母或者纯符号')),
	'email'    => array('control'=>'email','label'=>t('邮箱'),'name'=>'email','type'=>'varchar','length'=>'50','notnull'=>'1','settings'=>array('maxlength'=>'32'),'base'=>'1'),
	'mobile'   => array('control'=>'mobile','label'=>t('手机'),'name'=>'mobile','type'=>'varchar','length'=>'13','notnull'=>'1','settings'=>array('maxlength'=>'13'),'base'=>'1'),
	'nickname' => array('control'=>'nickname','label'=>t('昵称'),'name'=>'nickname','type'=>'varchar','length'=>'32','notnull'=>'0','settings'=>array('maxlength'=>'32'),'base'=>'0'),
);

$listorder = 1;

foreach( $member_system_fields as $field )
{
	$field = $field + array(
		'system'    => 1,
		'modelid'   => 'member',
		'listorder' => $listorder,
	);
	$this->db->table('user_field')->data($field)->insert(true);
	$listorder++;
}


?>