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
class content_controller_list extends site_controller
{
	/**
	 * 网站
	 *
	 */
	public function action_index($id)
    {
		$category = m('content.category.get', $id);

        if ( empty($category) or $category['disabled'] )
        {
            return $this->_404(t('编号为 %s 的栏目不存在', $id));
        }

        // 如果没有设置栏目模板，则跳转到栏目的第一条内容
        if ( empty($category['settings']['template_list']) )
        {
            $first = m('content.content.tag_content', array('cid'=>$category['id'],'size'=>1));

            if ( empty($first) )
            {
                return $this->_404(t('编号为 %s 的栏目尚无数据', $id));
            }

            return $this->redirect($first['url']);
        }

		$this->assign('title', $category['name']);
		$this->assign('keywords', $category['keywords']);
		$this->assign('description', $category['description']);
		$this->assign('category', $category);
		$this->display($category['settings']['template_list']);
    }



}
?>