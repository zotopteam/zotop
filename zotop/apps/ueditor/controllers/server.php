<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 编辑器服务类
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class ueditor_controller_server extends admin_controller
{

	public function action_index()
    {

        $result = array(
        	'state'=> t('暂时不支持编辑器直接上传文件')
        );

        exit(json_encode($result));
	}
}
?>