<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员 api控制器
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class member_controller_api extends site_controller
{
    
    /**
     * 检查用户名、邮箱等是否被占用，用于jQuery validation的remote验证
     *
     * @return bool
     */
	public function action_check_exists($field,$ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = m('system.user')->where($field,$_GET[$field])->count();
		}
		else
		{
			$count = m('system.user')->where($field,$_GET[$field])->where($field,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}
}
?>