<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 留言本控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class guestbook_controller_index extends site_controller
{
	protected $guestbook;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->guestbook = m('guestbook.guestbook');
	}

	/**
	 * 留言显示
	 *
	 */
	public function action_index()
    {
		$dataset = array();

		// 如果显示列表，读取数据
		if ( c('guestbook.showlist') )
		{
			//只显示已经回复的留言
			if ( c('guestbook.showreplied') )
			{
				$this->guestbook->where('replytime', '>', 0);
			}

			//只显示已经审核
			if ( c('guestbook.showaudited') )
			{
				$this->guestbook->where('status', '=', 'publish');
			}

			$dataset = $this->guestbook->orderby('createtime','desc')->getPage();

			$dataset['data'] = zotop::filter('guestbook.show',$dataset['data']);
		}


		$this->assign('title',c('guestbook.title'));
		$this->assign('description',c('guestbook.description'));
		$this->assign($dataset);
		$this->display('guestbook/index.php');
    }


	/**
	 * 添加留言
	 *
	 */
	public function action_add()
    {
    	if ( $post = $this->post() )
    	{
    		if ( c('guestbook.captcha') and !captcha::check() )
    		{
    			return $this->error(t('验证码错误，请重试'));
    		}

 			if ( $this->guestbook->add($post) )
			{
				return $this->success(t('留言已经提交成功，请等待管理员回复中……'), u('guestbook/index'));
			}

			return $this->error($this->guestbook->error());   		
    		
    	}
    }


}
?>