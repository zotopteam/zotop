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
	public $wechat;

	/**
	 * 微信服务器接口
	 * 
	 * @return mixed
	 */
	public function action_index($id)
    {
    	$account = m('wechat.account.get',$id);

    	$this->wechat = new wechat(C('wechat'));

    	$this->wechat->valid();

		$type = $this->wechat->getRev()->getRevType();

		switch($type)
		{
			case Wechat::MSGTYPE_TEXT:
				$this->wechat->text("hello, I'm zotop!")->reply();
				exit;
			break;
			case Wechat::MSGTYPE_EVENT:
				$data = $this->wechat->getRevData();

				$events = $this->wechat->getRevEvent();

				$this->wechat->text(print_r($data,true))->reply();

			break;
			default:
				$this->wechat->text("help info")->reply();
		}
	}


	public function action_menu()
	{

	}
}
?>