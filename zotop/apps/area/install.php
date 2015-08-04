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

// [area] 创建
$this->db->schema('area')->drop();
$this->db->schema('area')->create(array(
	'fields'=>array(
		'id'		=> array ( 'type'=>'mediumint', 'length'=>10, 'notnull'=>true, 'unsigned'=>true, 'comment' => t('区域编号') ),
		'parentid'	=> array ( 'type'=>'mediumint', 'length'=>10, 'default'=>'0', 'unsigned'=>true, 'comment' => t('父编号') ),
		'parentids'	=> array ( 'type'=>'char', 'length'=>100, 'default'=>null, 'comment' => t('父编号串') ),
		'level'		=> array ( 'type'=>'tinyint', 'length'=>1, 'notnull'=>true, 'comment' => t('级别') ),
		'name'		=> array ( 'type'=>'char', 'length'=>20, 'notnull'=>true, 'comment' => t('区域名称') ),
		'letter'	=> array ( 'type'=>'char', 'length'=>1, 'default'=>null, 'comment' => t('首字母') ),
		'listorder'	=> array ( 'type'=>'smallint', 'length'=>5, 'notnull'=>true, 'default'=>'0', 'unsigned'=>true, 'comment' => t('排序') ),
	),
	'index'=>array(
		'parentid'	 => array ( 'parentid' ),
	),
	'unique'=>array(
	),
	'primary'=>array ( 'id' ),
	'comment' => t('地区表')
));

// 默认插入的数据
$default_area = include(ZOTOP_PATH_APPS.DS.'area'.DS.'data'.DS.'default.php');

// 插入数据
foreach( $default_area as $area )
{
	$this->db->insert('area',$area);
}
?>