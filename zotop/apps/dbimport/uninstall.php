<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_UNINSTALL') OR die('No direct access allowed.');

// [dbimport] 创建
$this->db->schema('dbimport')->drop();
?>