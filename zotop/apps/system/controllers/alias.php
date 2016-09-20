<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 别名管理
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_alias extends controller
{
	/*
	 * 获取别名，将字符串转化为别名
	 */
	public function action_get()
	{
		$alias = '';

		if ( $source = $_GET['source'] )
		{
			// 最大长度
			$maxlength = max(16,intval($_GET['maxlength']));
			
			// 别名转换
			$alias     = pinyin::get($source,'-');
			$alias     = trim($alias,'-');
			$alias     = substr(strtolower($alias), 0, $maxlength);
		}

		exit($alias);
	}


    /**
     * 检查别名是否被占用
     *
     * @return bool
     */
	public function action_check($key='alias')
	{
		$alias = m('system.alias');

		// 如果别名为应用ID，则已经存在
		if ( A($_GET[$key]) )
		{
			$count = 1;
		}
		elseif ( empty($_GET['ignore']) )
		{
			$count = $alias->where('alias',$_GET[$key])->count();
		}
		else
		{
			$count = $alias->where('alias',$_GET[$key])->where('alias','!=',$_GET['ignore'])->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}
}
?>