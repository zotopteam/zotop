<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 别名
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team 
 * @license		http://zotop.com/license.html
 */
class content_model_extend_category extends content_model_extend
{
    /**
     * 删除
     * 
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        if ( $this->content->where('parentid',$id)->count() > 0 )
        {
            return $this->error(t('请先删除该分类下面的数据'));
        }

        return parent::delete($id);
    }

    /**
     * 扩展菜单
     * 
     * @param  array $menu    菜单数组
     * @param  array $content 内容基本数据
     * @return array
     */
    public function manage_menu($menu, $content)
    {           
        $menu = arr::after($menu, 'view', 'child', array(
            'text' => t('打开'),
            'icon' => 'fa fa-folder-open',
            'href' => U('content/content/index?parentid='.$content['id'].'&status='.$content['status'])
        ));

        return $menu;
    }    
}
?>