<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 内容详细页面控制器，前台显示详细内容
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class content_controller_detail extends site_controller
{
    protected $content;
    protected $category;

    /*
    * 初始化
    */
    public function __init()
    {
        parent::__init();

        $this->content  = m('content.content');
        $this->category = m('content.category');
    }

    /**
     * 详细页面
     *
     * @param int $id 内容编号
     * @return void
     */
    public function action_index($id)
    {
        // 读取内容数据
        $content = $this->content->get(intval($id));

        if ( empty($content) )
        {
            return $this->_404(t('编号为 %s 的内容不存在', $id));
        }

        // 钩子
        $content  = zotop::filter('content.show', $content, $this);
        
        // 栏目数据
        $category = $this->category->get($content['categoryid']);
        
        //模板
        $template = empty($content['template']) ? $category['settings']['models'][$content['modelid']]['template'] : $content['template'];

        $this->assign('title', $content['title'] . ' ' . $category['title']);
        $this->assign('keywords', $content['keywords'] . ' ' . $category['keywords']);
        $this->assign('description', $content['summary'] . ' ' . $category['description']);
        $this->assign('content', $content);
        $this->assign('category', $category);
        $this->display($template);
    }

    public function action_fullcontent($id)
    {
        $content = $this->content->get(intval($id));
                
        // 钩子
        $content  = zotop::filter('content.fullcontent', $content, $this);        

        exit($content['content']);
    }

    /**
     * 文件下载
     * 
     * @param  int $id 内容编号
     * @return file
     */
    public function action_download($id)
    {
        // 读取内容数据
        $content = $this->content->get(intval($id));

        if ( empty($content) or $content['modelid'] != 'download' )
        {
            return $this->_404(t('编号为 %s 的内容不存在', $id));
        }

        // 本服务器上的文件
        if ( $content['local'] )
        {
             m('content.download.downfile', $id);

             return file::download($content['filepath'], $content['filename']);
        }

        // 直接指向远程文件
        return $this->redirect($content['filepath']);       
    }
}
?>