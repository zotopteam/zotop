<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 微信 后台基准控制器
*
* @package		wechat
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class wechat_admin_controller extends admin_controller
{
	public $wechat;
	public $account;

	public function __init()
	{
		parent::__init();

    	if ( $accountid = $_GET['accountid'] )
    	{
    		zotop::session('wechat_current_accountid', $accountid);
    	}
    	else
    	{
    		$accountid = zotop::session('wechat_current_accountid');
    	}

    	// 获取当前微信账号信息，如果没有$accountid，则获取第一个作为默认
		$this->account = $accountid ? m('wechat.account.get',$accountid) : reset(m('wechat.account.cache'));
		
		// 生成wechat对象
		$this->wechat  = new wechat(arr::get($this->account, 'appid,appsecret,token,encodingaeskey,debug'));

		// 页面中的account
    	$this->assign('account',$this->account);
	}


	
}
?>