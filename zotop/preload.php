<?php
/**
 * 系统核心类库文件映射，系统启动时自动打包到runtime的core.php文件里面，减少加载次数
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */

return array
(
    //core
	'application' 	=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'application.php',
	'router' 		=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'router.php',
    'controller' 	=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'controller.php',
	'model' 		=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'model.php',

	//ui
	'template' 		=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'template.php',
    'form' 			=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'form.php',

	 //util
	'request' 		=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'request.php',
	'format' 		=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'format.php',
	'file' 			=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'file.php',
	'folder' 		=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'folder.php',
    'str'			=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'str.php',


    //database
    'database' 			=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'database.php',
	'database_mysql' 	=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'database'.DS.'mysql.php',
	'database_sqlite' 	=> ZOTOP_PATH_LIBRARIES.DS.'classes'.DS.'database'.DS.'sqlite.php',

    //cache
    'cache' 				=> ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'cache.php',
    'cache_file' 			=> ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'cache' . DS . 'file.php',
    'cache_memcache' 		=> ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'cache' . DS . 'memcache.php',
    'cache_eaccelerator' 	=> ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'cache' . DS . 'eaccelerator.php',
	'cache_apc' 			=> ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'cache' . DS . 'apc.php',
);
?>