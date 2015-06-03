<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 微信 接口控制器
*
* @package		wechat
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class wechat_controller_api extends site_controller
{
	/**
	 * 微信服务器接口
	 * 
	 * @return mixed
	 */
	public function action_index($id)
    {
		$account = m('wechat.account.get', $id);
		
		$options = arr::get($account, 'appid,appsecret,token,encodingaeskey,debug');
		
		$wechat  = new wechat($options);

		$wechat->debug = true;

		$wechat->valid();

		if ( !$account['connect'] )
		{
			m('wechat.account')->where('id',$id)->set('connect',1)->update();
		}

		$type = $wechat->getRev()->getRevType();

		switch($type)
		{
			case Wechat::MSGTYPE_TEXT:
				$data = $wechat->getRevData();

				$wechat->log($account);

				$wechat->text("hello, I'm zotop!")->reply();
				exit;
			break;
			case Wechat::MSGTYPE_EVENT:
				$data = $wechat->getRevData();

				$events = $wechat->getRevEvent();

				$wechat->text(print_r($data,true))->reply();

			break;
			default:
				$wechat->text("help info")->reply();
		}
	}


	public function action_menu()
	{

	}
}
?>