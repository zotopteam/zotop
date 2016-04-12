<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员注册
*
* @package		member
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class member_controller_register extends site_controller
{
	private $member;
	private $model;
	private $field;
	private $group;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->member = m('member.member');
		$this->model  = m('member.model');
		$this->field  = m('member.field');
		$this->group  = m('member.group');
	}

	/**
	 * 会员注册
	 *
	 */
	public function action_index($modelid='')
    {
		if ( $post = $this->post() )
		{
			if ( c('member.register_captcha') and !captcha::check() )
			{
				return $this->error(t('验证码错误，请重试'));
			}

			if ( $this->member->add($post) )
			{
				//注册成功后自动登录
				$this->member->login($post);

				return $this->success(t('注册成功'),u('member/index'));
			}

			return $this->error($this->member->error());
		}

		//可用模型
		$models = $this->model->cache();

		foreach( $models as $i=>$m )
		{
			if ( !$m['settings']['register'] or $m['disabled'] ) unset($models[$i]);
		}

		$modelid = $modelid ? $modelid : reset(array_keys($models));

		if ( empty($modelid) or !isset($models[$modelid]) )
		{
			return $this->error(t('禁止注册'));
		}

		// 字段信息
		$fields = m('member.field')->getFields($modelid);

		foreach( $fields as $i=>$f )
		{
			if( $f['base'] == 0 and $f['required'] == 0 ) unset($fields[$i]);
		}

		// 当前模型
		$model = $this->model->get($modelid);

		// 模型模板
		$template = $model['settings']['register_template'] ? $model['settings']['register_template'] : 'member/register.php';

		$this->assign('title',t('会员注册'));
		$this->assign('model',$model);
		$this->assign('modelid',$modelid);
		$this->assign('models',$models);
		$this->assign('fields',$fields);
		$this->display($template);
	}

    /**
     * 验证用户邮箱
     *
     * @return bool
     */
	public function action_validmail()
	{
		// 获取验证码
		//$code = zotop::decode(rawurldecode($_GET['code']));
		//list($id, $time) = explode('|',$code);

		extract($_GET);

		// 验证链接有效期
		if ( $time and (ZOTOP_TIME - $time) > C('member.register_validmail_expire') * 3600 )
		{
			return $this->error(t('链接已失效，请重新验证'));
		}		

		// 验证safekey
		if ( empty($safekey) or $safekey != md5(C('system.safekey').$id.$time) )
		{
			return $this->error(t('链接已失效，请重新验证'));
		}

		// 验证用户编号
		if ( $id and $user = $this->member->user->getbyid($id) )
		{
			$this->member->user->where('id',$id)->data('emailstatus',1)->update();
			$this->member->user->login($user);
			
			return $this->success(t('邮箱地址验证成功'),U('member/index'));
		}

		return $this->error(t('禁止访问'));
	}
}
?>