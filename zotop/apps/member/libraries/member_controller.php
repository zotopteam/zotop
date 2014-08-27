<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 会员控制器
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */
class member_controller extends site_controller
{
    protected $userid;

	/**
     * 动作执行之前执行
     *
     */
    public function __init()
    {
		parent::__init();

		//定义为后台入口
		define('ZOTOP_MEMBER',true);

		//登录及权限检查
		$this->__check();

		// 初始化userid
		$this->userid = zotop::user('id');

		//初始化用户
		$this->assign('_USER',zotop::user());

		// hook
		zotop::run('member.init', $this);
    }

    /**
     * 登录及权限检查
     *
     */
	public function __check()
	{
		if( zotop::user('id') <=0 )
		{
			$this->redirect(U('member/login'));
			return false;
		}

		return true;
	}


}
?>