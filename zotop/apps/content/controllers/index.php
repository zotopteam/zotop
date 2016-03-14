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

        // 栏目如果没有子栏目，则自动显示列表页        
        $template = ( $category['childid'] and $category['settings']['template_index'] ) ? $category['settings']['template_index'] : $category['settings']['template_list'];

        // 如果没有列表模板，则跳转到栏目的第一条内容
        if ( empty($template) )
        {
            $rows = m('content.content.tag_content', array('cid'=>$category['id'],'size'=>1));

            if ( $row = reset($rows) )
            {
                return $this->redirect($row['url']);
            }

            return $this->_404(t('编号为 %s 的栏目尚无数据', $id));            
        }        

		$this->assign('title', $category['name']);
		$this->assign('keywords', $category['keywords']);
		$this->assign('description', $category['description']);
		$this->assign('category', $category);
		$this->display($template);
    }



}
?>