<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 翻译
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class translator_controller_alias extends controller
{
	/*
	 * 获取别名，将字符串转化为别名，一般用于ajax调用
	 */
	public function action_get()
	{
		$alias = '';

		$s = '-'; //分隔符

		if ( $source = $_GET['source'] )
		{
			$alias = $this->language($source);
			$alias = str_replace(' ', $s, $alias);
			$alias = preg_replace("/[[:punct:]]/",$s,$alias);
			$alias = preg_replace('#['.$s.$s.']+#', $s, $alias);
			$alias = strtolower($alias);
		}					

		if( $maxlength = $_GET['maxlength'] )
		{
			$alias = substr($alias, 0, $maxlength);
		}

		exit(trim($alias,$s));
	}

	/*
	 * 别名翻译 ,将中文翻译成英文
	 *
	 */
	private function language($str, $from = 'zh', $to = 'en')
	{
		// 首先对要翻译的文字进行 urlencode 处理
		$str 		= urlencode($str);

		// 百度翻译的appid
		$clientid 	= c('translator.baidu_clientid');

		// 生成翻译地址
		$url 		= "http://openapi.baidu.com/public/2.0/bmt/translate?client_id=" . $clientid ."&q=" .$str. "&from=".$from."&to=".$to;

		// 获取翻译结果
		$text 		= json_decode($this->language_text($url), true);

		// 返回结果
		return $text['trans_result'][0]['dst'];
	}

	/*
	 * 获取翻译结果
	 */
	private function language_text($url)
	{
		if( function_exists('file_get_contents') )
		{
			$file_contents = file_get_contents($url);
		}
		else
		{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}

		return $file_contents;
	}
}
?>