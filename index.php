<?php
// 调试模式
define('ZOTOP_DEBUG', true);

// 跟踪模式
define('ZOTOP_TRACE', true);

require dirname(__file__) . DIRECTORY_SEPARATOR . 'zotop' . DIRECTORY_SEPARATOR . 'zotop.php';

zotop::boot('site/index');
?>