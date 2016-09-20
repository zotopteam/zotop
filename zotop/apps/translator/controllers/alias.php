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
		
		$s     = '-'; //分隔符

		if ( $source = $_GET['source'] )
		{
			$alias = $this->translate($source);
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
	private function translate($str, $from = 'zh', $to = 'en')
	{
		$api = c('translator.api');

		switch ($api)
		{
			case 'baidu':		
				$appid  = C('translator.baidu_appid');
				$seckey = C('translator.baidu_seckey');
				$salt   = rand(10000,99999);
				$sign   = md5($appid . $str . $salt . $seckey);
				$str    = rawurlencode($str);
				$url    = "http://api.fanyi.baidu.com/api/trans/vip/translate?q={$str}&from={$from}&to={$to}&appid={$appid}&salt={$salt}&sign={$sign}";
				$result = json_decode($this->getresult($url), true);
				$result = is_array($result) ? $result['trans_result'][0]['dst'] : '111';
				break;			
			case 'youdao':
				$str = urlencode($str);
				$url    = 'http://fanyi.youdao.com/openapi.do?keyfrom='.c('translator.youdao_keyfrom').'&key='.c('translator.youdao_key').'&type=data&doctype=json&version=1.1&q='.$str;			
				$result = json_decode($this->getresult($url), true);
				$result = is_array($result) ? $result['translation'][0] : '';
				break;
		}

		return $result;
	}

	/*
	 * 获取翻译结果
	 */
	private function getresult($url)
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