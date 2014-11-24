<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_UNINSTALL') OR die('No direct access allowed.');

/**
 * 卸载程序
 *
 * @package		conten-case
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */

// 卸载前检查数据表是否有数据，有则不允许删除
if ( $this->db->from('content_model_case')->count() )
{
	return $this->error(t('无法卸载，%s 数据表尚有数据，请先删除全部 %s', t('工程案例'), t('工程案例')));
}

/*
 * 删除数据
 */
if ( m('content.model')->delete('case') )
{
	$this->db->table('content_model_case')->drop();
}
?>