<?php
/*
* 在线客服 全局文件
*
* @package		kefu
* @version		1.8
* @author		
* @copyright	
* @license		
*/

// 注册类库到系统中
zotop::register(array(
    'kefu_api' => A('kefu.path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'kefu_api::start');


zotop::add('site.footer', 'kefu_show');

/**
 * 显示，将客服代码插入到前台页面中
 *
 * @return string 控件代码
 */
function kefu_show()
{
	// 控件属性
	echo '<script type="text/javascript" src="'.u('kefu').'"></script>';
}
?>