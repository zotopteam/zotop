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
	 * 侧边菜单导航
	 * 
	 * @return [type] [description]
	 */
	public function admin_sidebar()
	{
		return zotop::filter('member.admin.sidebar',array(
			'member_admin' => array(
				'text'   => t('会员列表'),
				'href'   => U('member/admin'),
				'icon'   => 'fa fa-users',
				'active' => request::is('member/admin')
			),
            'member_model' => array(
                'text'   => t('会员模型'),
                'href'   => U('member/model'),
                'icon'   => 'fa fa-search',
                'active' => request::is('member/model')
            ),            
			'member_group' => array(
				'text'   => t('会员组'),
				'href'   => U('member/group'),
				'icon'   => 'fa fa-phone',
				'active' => request::is('member/group')
			),
			'member_field' => array(
				'text'   => t('字段管理'),
				'href'   => U('member/field'),
				'icon'   => 'fa fa-power-off',
				'active' => request::is('member/field')
			),
			'member_config' => array(
				'text'   => t('设置'),
				'href'   => U('member/config'),
				'icon'   => 'fa fa-cog',
				'active' => request::is('member/config')
			),									
		));
	}

	/**
	 * 会员组设置，允许通过 HOOk [member.group.settings] 进行扩展
	 *
	 * 
	 * @param  array $data 当前会员组的默认数据
	 * @return array
	 */
	public static function group_settings($data)
	{
		$fields = zotop::filter('member.group.settings', array(
			'username_color'    => array('label'=>t('用户名颜色'),'type'=>'color','colclass'=>'col-sm-3'),
			'icon'              => array('label'=>t('用户组图标'),'type'=>'image','placeholder'=>t('一般使用16px或者32px的PNG图片')),
			'point'             => array('label'=>t('积分最小值'),'type'=>'number','value'=>'0','required'=>true,'min'=>0,'colclass'=>'col-sm-3','tips'=>t('达到该值后用户方能升级到该用户组')),
			'contribute'        => array('label'=>t('投稿设置'),'type'=>'radio','options'=>array(''=>t('禁止'),'pending'=>t('允许-需要审核'),'publish'=>t('允许-直接发布')),'value'=>''),
			'contribute_number' => array('label'=>t('日投稿数'),'type'=>'number','value'=>'5','min'=>0,'required'=>true,'colclass'=>'col-sm-3'),
		), $data);

		foreach ($fields as $key => &$val)
		{
			$val['name']  = $val['name'] ? $val['name'] : 'settings['.$key.']';
			$val['value'] = isset($data['settings'][$key]) ? $data['settings'][$key] : $val['value'];

			$fields[$key] = arr::take($val,'label','tips','colclass') + array('field'=>$val);
		}

		return $fields;
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