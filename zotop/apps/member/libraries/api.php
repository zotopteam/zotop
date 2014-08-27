<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员 api
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class member_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['member'] = array(
			'text' => A('member.name'),
			'href' => U('member/admin'),
			'icon' => A('member.url') . '/app.png',
			'description' => A('member.description'));

		return $start;
	}

	/**
	 * 注册全局导航
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function globalnavbar($nav)
	{
		$nav['member'] = array(
			'text' => A('member.name'),
			'href' => u('member/admin'),
			'icon' => A('member.url').'/app.png',
			'description' => A('member.description'),
			'allow' => priv::allow('member'),
			'current' => (ZOTOP_APP == 'member')
		);

		return $nav;
	}


	/**
	 * 测试控件，请修改或者删除此处代码，详细修改方式请参见文档
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function test($attrs)
	{
		// 控件属性
		$html['field'] = form::field_text($attrs);

		return implode("\n",$html);
	}

	/**
	 * 发送验证邮件，激活码格式：rawurlencode(zotop::encode(用户编号|时间戳)
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function validmail($to, $data=array())
	{
		if ( $to and $data['id'] and intval(C('member.register_validmail')) and intval(C('system.mail')) )
		{
			// 激活地址
			$data['url'] = U('member/register/validmail', array('code'=>rawurldecode(zotop::encode($data['id'].'|'.ZOTOP_TIME))), true);

			//解析邮件内容
			$title = self::parseMail(c('member.register_validmail_title'),$data);
			$content = self::parseMail(c('member.register_validmail_content'),$data);

			$mail = new mail();
			$mail->sender = c('site.name');
			$mail->send($to, $title, $content);

			return true;
		}

		return false;
	}

    /**
     * 解析邮件内容,支持{site} {url} {click} 等参数以及自定义的用户参数
     *
     */
	public function parseMail($str, $data)
	{
		$str = zotop::filter('member.parsemail', $str, $data);

		$str = str_replace('{site}',' '.c('site.name').' ',$str);
		$str = str_replace('{click}','<div><a href="'.$data['url'].'" target="_blank">'.$data['url'].'</a></div>',$str);

		foreach ( $data as $key=>$val )
		{
			$str = str_replace('{'.$key.'}', $val ,$str);
		}

		return $str;
	}
}
?>