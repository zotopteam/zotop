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
class content_controller_search extends site_controller
{
	/**
	 * 搜索
	 *
	 */
	public function action_index()
    {
    	// 导入变量
		extract( $_GET );

		if ( $keywords )
		{
	   		// 初始化读取数据，只读取已经发布的数据
			$db = m('content.content')->select('*')->where('status','=','publish');

			// 单独模型数据
			if ( $modelid ) $db->where('modelid','=',$modelid);

			// 根据关键词筛选
			if ( !empty($keywords) )
			{
				$keywords_array = explode(',', $keywords);
				$keywords_where = array();
				foreach($keywords_array as $k)
				{
					$keywords_where[] = 'or';
					$keywords_where[] = array('title','like', $k);
					$keywords_where[] = 'or';
					$keywords_where[] = array('keywords','like', $k);				
				}
				array_shift($keywords_where);
				$db->where($keywords_where);
			}

			$dataset = $db->orderby('updatetime desc')->getPage(null, 20);

			$dataset['data'] = m('content.content.process', $dataset['data']);
		}

		$this->assign('title',$keywords);
		$this->assign('description',$keywords);
		$this->assign('keywords', $keywords);
		$this->assign($dataset);
		$this->display('content/search.php');
    }



}
?>