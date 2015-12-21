<?php
define('ZOTOP_DEBUG', true);
define('ZOTOP_TRACE', false);

require dirname(__file__) . DIRECTORY_SEPARATOR . 'zotop' . DIRECTORY_SEPARATOR . 'zotop.php';

zotop::boot('system/index');
?>