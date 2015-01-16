<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 关键词
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_keywords extends admin_controller
{

	/**
	 * 获取关键词
	 *
	 * @return string  关键词
	 */
	public function action_get()
	{
		$title 		= strip_tags($_POST['title']);
		$content 	= strip_tags(preg_replace("/\[.+?\]/U", '', $_POST['content']));

		$title 		= rawurlencode(str::cut($title,500));
		$content 	= rawurlencode(str::cut($content,500));

		$data = @implode('', file("http://keyword.discuz.com/related_kw.html?ics=utf-8&ocs=utf-8&title={$title}&content={$content}"));

		if ( $data )
		{
			preg_match_all("/<kw>(.*)A\[(.*)\]\](.*)><\/kw>/",$data, $matches);

			$keywords = $matches[2];

			foreach( $keywords as $i=>$k )
			{
				if ( strlen($k) < 4 ) unset($keywords[$i]);
			}

			$keywords = @implode(',', $keywords);
		}

		exit($keywords);
	}
}
?>