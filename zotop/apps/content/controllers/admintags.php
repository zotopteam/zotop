<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 标签控制器
 *
 * @package		content
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_controller_admintags extends admin_controller
{
	protected $content;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->tag = m('content.tag');
	}

	public function action_index()
    {
		if ( $keywords = $_REQUEST['keywords'] )
		{
			 $this->tag->where('name','like',$keywords);
		}

		$dataset = $this->tag->orderby('id','desc')->paginate();

		$this->assign('title',t('Tag管理'));
		$this->assign('keywords',$keywords);
		$this->assign($dataset);
		$this->display();
    }
	/**
	 * 多选操作
	 *
	 * @param $operation 操作
	 * @return mixed
	 */
    public function action_operate($operation)
    {
		if ( $post = $this->post() )
		{
			if ( empty($post['id']) or !is_array($post['id']) )
			{
				return $this->error(t('请选择要操作的项'));
			}

			switch($operation)
			{
				case 'delete' :
					$result = $this->tag->deltags($post['id']);
					break;
				default :
					break;
			}

			return $result ? $this->success(t('操作成功'),request::referer()) : $this->error(t('操作失败'));
		}

		return $this->error(t('禁止访问'));
    }

}
?>