<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 商城 首页控制器
*
* @package		shop
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class shop_controller_index extends site_controller
{
	/**
	 * 网站
	 *
	 */
	public function action_index($id=0)
    {
    	if ( $id  )
    	{
			// 栏目数据
			$category = m('shop.category.get', $id);

			if ( empty($category) )
			{
				 return $this->_404(t('编号为 %s 的内容不存在', $id));
			}

			$teamplate = $category['childid'] ? $category['settings']['template_index'] : $category['settings']['template_list'];
		}


		$this->assign('title', $category['name']);
		$this->assign('keywords', $category['keywords']);
		$this->assign('description', $category['description']);
		$this->assign('category', $category);
		$this->display($teamplate);
    }
}
?>