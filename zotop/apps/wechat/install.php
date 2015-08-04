<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_INSTALL') OR die('No direct access allowed.');

// [wechat_account] 创建	
$this->db->schema('wechat_account')->drop();
$this->db->schema('wechat_account')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'int', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'autoinc'=>true, 'comment' => t('') ),
		'name'		=> array ( 'type'=>'char', 'length'=>50, 'notnull'=>true, 'comment' => t('公众号名称') ),
		'account'	=> array ( 'type'=>'char', 'length'=>30, 'notnull'=>true, 'comment' => t('微信号') ),
		'original'	=> array ( 'type'=>'char', 'length'=>50, 'default'=>null, 'comment' => t('原始ID') ),
		'type'		=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('公众号类型') ),
		'appid'		=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('AppID') ),
		'appsecret'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('AppSecret') ),
		'token'		=> array ( 'type'=>'varchar', 'length'=>100, 'default'=>null, 'comment' => t('Token令牌') ),
		'encodingaeskey'=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('消息加解密密钥') ),
		'headface'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('头像') ),
		'qrcode'	=> array ( 'type'=>'varchar', 'length'=>255, 'default'=>null, 'comment' => t('二维码地址') ),
		'connect'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否接入，0：未接入，1：接入') ),
		'listorder'	=> array ( 'type'=>'int', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('账号排序') ),
		'disabled'	=> array ( 'type'=>'tinyint', 'length'=>1, 'default'=>'0', 'unsigned'=>true, 'comment' => t('是否禁用，1：禁用') ),
	),
	'index'=>array(
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('微信公众账号及账号信息') 
));
?>