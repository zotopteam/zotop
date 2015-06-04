<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 微信 后台控制器
*
* @package		wechat
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class wechat_controller_admin extends wechat_admin_controller
{
	/**
	 * 默认动作
	 * 
	 * @return mixed
	 */
	public function action_index()
    {



		//$result = $this->wechat->getMenu();

		//debug::dump($result);

		$this->assign('title',t('统计信息'));
		$this->assign('data',$data);
		$this->display();
	}


	/**
	 * 创建自定义菜单
	 * 
	 * @return [type] [description]
	 */
	public function action_menu()
	{
		$newmenu =  array("button"=>array(
			array('type'=>'click','name'=>'菜单','key'=>'MENU_KEY_NEWS','sub_button'=>array(
				array('type'=>'view','name'=>'百度搜索','url'=>'http://www.baidu.com'),
				array('type'=>'view','name'=>'测试连接','url'=>$this->wechat->getOauthRedirect(U('wechat/index/test'),'auth')),
			)),
			array('type'=>'view','name'=>'Oauth3','url'=>$this->wechat->getOauthRedirect(U('wechat/index/index'),'auth')),
		));

		if ( $this->wechat->createMenu($newmenu) )
		{
			return $this->success(t('创建菜单成功'));
		}

		return $this->error($this->wechat->errCode.': '.$this->wechat->errMsg);


		$this->assign('title',t('微信'));
		$this->assign('data',$data);
		$this->display();	
	}
	
}
?>