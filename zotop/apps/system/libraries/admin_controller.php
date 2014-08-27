<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 系统控制器操作类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */
class admin_controller extends controller
{

    /**
     * 动作执行之前执行
     *
     */
    public function __init()
    {
		parent::__init();

		//定义为后台入口
		define('ZOTOP_ADMIN',true);

		//登录及权限检查
		$this->__check();

		//初始化用户
		$this->assign('_USER',zotop::user());

		//初始化导航及导航接口
		$this->assign('_GLOBALNAVBAR',zotop::filter('system.globalnavbar',array()));

		//初始化全局消息及消息接口
		$this->assign('_GLOBALMSG',zotop::filter('system.globalmsg',array()));

		// hook
		zotop::run('admin.init', $this);
    }

    /**
     * 登录及权限检查
     *
     */
	public function __check()
	{
		//检查用户是否登录
		$this->__checkUser();

		//检查权限是否足够
		$this->__checkPriv();
	}

    /**
     * 检查用户是否登录
     *
     * @return void
     */
    public function __checkUser()
    {
		if( zotop::user('id') <=0 or zotop::user('modelid') != 'admin' )
		{
			$this->redirect(u('system/login'));
			return false;
		}
		return true;
    }

    /**
     * 检查用户是否拥有使用权限
     *
     * @return void
     */
	public function __checkPriv()
	{
		if ( !priv::allow(ZOTOP_APP,ZOTOP_CONTROLLER,ZOTOP_ACTION) )
		{
			return $this->error(t('权限不足'));
		}

		return true;
	}

    /**
     * 覆盖默认的消息通知，增加操作日志
     *
     * @return void
     */
	public function message(array $msg)
	{
		if ( c('system.log') )
		{
			m('system.log')->write($msg['state'],$msg['content']);
		}

		parent::message($msg);
	}
}
?>