<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 内容控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_controller_index extends site_controller
{
	/**
	 * 网站
	 *
	 */
	public function action_index($id)
    {
		// 栏目数据
		$category = m('content.category.get', $id);

        if ( empty($category) or $category['disabled'] )
        {
            return $this->_404(t('编号为 %s 的栏目不存在', $id));
        }		

		// 栏目模板
		if ( $category['childid'] )
		{
			$template = $category['settings']['template_index'] ? $category['settings']['template_index'] : 'content/index.php';
		}
		else
		{
			$template = $category['settings']['template_list'] ? $category['settings']['template_list'] : 'content/list.php';
		}

		$this->assign('title', $category['name']);
		$this->assign('keywords', $category['keywords']);
		$this->assign('description', $category['description']);
		$this->assign('category', $category);
		$this->display($template);
    }



}
?>