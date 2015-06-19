<?php
define('ZOTOP_DEBUG', false);
define('ZOTOP_TRACE', true);

require dirname(__file__) . DIRECTORY_SEPARATOR . 'zotop' . DIRECTORY_SEPARATOR . 'zotop.php';
zotop::boot('system/index');
?>