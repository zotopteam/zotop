<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 商城 详细页面
*
* @package		shop
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class shop_controller_detail extends site_controller
{
	/**
	 * 网站
	 *
	 */
	public function action_index($id)
    {

		$goods = m('shop.goods.get', $id);

		if ( empty($goods) )
		{
			 return $this->_404(t('编号为 %s 的内容不存在', $id));
		}

		$category = m('shop.category.get', $goods['categoryid']);

		if ( $category )
		{
			$template = $category['settings']['template_detail'];
		} 
		
		$template = $goods['template'] ? $goods['template'] : $template;
		$template = empty($template) ? 'shop/detail.php' : $template;

		// 生成属性
		foreach( m('shop.type.attrs', $goods['typeid']) as $i=>$a )
		{
			$attrs[$i]['name'] 	= $a['name'];
			$attrs[$i]['value'] = $goods['attrs'][$a['id']];
		}

		$goods['attrs'] = is_array($attrs) ? $attrs : array();			

		$this->assign('title', $goods['name']);
		$this->assign('keywords', $goods['keywords']);
		$this->assign('description', $goods['description']);
		$this->assign('goods', $goods);
		$this->assign('category', $category);
		$this->display($template);
    }
}
?>