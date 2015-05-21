<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 微信 首页控制器
*
* @package		wechat
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class wechat_controller_index extends site_controller
{
	public $wechat;

	public function __init()
	{
    	if ( $_GET['code'] and $_GET['state'] == 'auth' )
    	{
	     	$this->wechat = new wechat(C('wechat'));

	    	if ( $token = $this->wechat->getOauthAccessToken() )
	    	{
	    		$this->userinfo = $this->wechat->getOauthUserinfo($token['access_token'], $token['openid']);
	    	}   		
    	}

    	parent::__init();	

	}

	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {
		exit('index'.$this->userinfo['nickname']);

		// $this->assign('title',t('微信'));
		// $this->assign('data',$data);
		// $this->display('wechat/index.php');
	}

	/**
	 * test
	 * 
	 * @return mixed
	 */
	public function action_test()
    {

		exit('test'.$this->userinfo['openid']);

		// $this->assign('title',t('微信'));
		// $this->assign('data',$data);
		// $this->display('wechat/index.php');
	}	
}
?>