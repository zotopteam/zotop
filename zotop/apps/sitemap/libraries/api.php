<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 网站地图 api
*
* @package		sitemap
* @version		1.0
* @author		zotop team
* @copyright	zotop team
* @license		http://www.zotop.com
*/
class sitemap_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['sitemap'] = array(
			'text' => A('sitemap.name'),
			'href' => U('sitemap/admin'),
			'icon' => A('sitemap.url') . '/app.png',
			'description' => A('sitemap.description'));

		return $start;
	}

	/**
	 * 内容站点地图
	 *
	 */
	public static function content($sitemap)
	{
		if ( A('content') )
		{
			// 获取栏目
			foreach ( m('content.category.active') as $category)
			{
				$sitemap->add($category['url'], gmdate('Y-m-d'), 'daily', '0.9');
			}
			
			// 内容数据
			$data = m('content.content')->where('status','publish')->orderby('createtime desc')->limit(1000)->getall();

			foreach ($data as $r)
			{
				$r['updatetime'] = $r['updatetime'] ? $r['updatetime'] : $r['createtime'];

				$sitemap->add($r['url'], gmdate("Y-m-d",$r['updatetime']), 'weekly', '0.7');
			}

			unset($data);
		}
	}	
	
}
?>