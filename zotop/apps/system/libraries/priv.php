<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 权限类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */
class priv
{
    /**
     * 检查用户角色是否拥有使用权限
     *
     * @return void
     */
	public static function allow($app, $controller='', $action='')
	{
		static $groupid = null;
		static $privs = array();

		if ( empty($groupid) ) $groupid = zotop::user('groupid');

		if ( empty($groupid) ) return false;

		if ( $groupid == 1 ) return true;

		if ( empty($privs) )
		{
			$privs = m('system.rolepriv')->cache($groupid);
		}

		foreach ( $privs as $r )
		{
			// 拥有应用权限
			if ( $r[0] == $app and empty($r[1]) ) return true;

			// 拥有控制器权限
			if ( $r[0] == $app and  $r[1] == $controller and empty($r[2]) ) return true;

			// 拥有动作权限
			if ( $r[0] == $app and  $r[1] == $controller and ( $r[2] == $action or in_array($action, explode(",", $r[2])) ) ) return true;
		}

		return false;
	}
}
?>