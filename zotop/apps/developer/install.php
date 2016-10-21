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

// 插入开始菜单
$system_navbar = array(
	'id'       => 'developer',
	'parentid' => 'system_navbar',
	'rootid'   => 'system_navbar',
	'app'      => 'developer',
	'listorder'=> 100,
	'data'     => array(
		'text'   => t('开发助手'),
		'href'   => 'developer',
		'active' => 'developer/*'
	)
);

$this->db->table('menu')->data($system_navbar)->insert();
?>