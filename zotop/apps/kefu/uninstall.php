<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_UNINSTALL') OR die('No direct access allowed.');

// 卸载前检查数据表是否有数据，有则不允许删除
if ( $this->db->from('kefu')->count() )
{
	return $this->error(t('无法卸载，[ %s ] 数据表尚有数据', 'kefu'));
}

$this->db->schema('kefu')->drop();
?>