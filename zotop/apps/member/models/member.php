<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * member_model_member
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class member_model_member extends model
{
	protected $pk = 'id'; //主键名称
	protected $table = ''; //member模型挂载下面全部的模型，在添加、编辑、删除时候需赋值

	public $user;

	public function __construct()
	{
		parent::__construct();

		$this->user		= m('system.user');
	}

	/*
	 *  会员登录
	 */
	public function login(array $data)
	{
		if ( $user = $this->user->login($data) )
		{
			$this->user->point($user['id'], C('member.login_point'));

			zotop::run('member.login', $user);

			return $user;
		}

		return $this->error($this->user->error());
	}

	/**
	 * 清除登陆信息
	 *
	 */
	public function logout()
	{
		if ( $this->user->logout() )
		{
			zotop::run('member.logout');
			return true;
		}

		return false;
	}


	/*
	 *  获取信息
	 */
	public function get($id, $field='')
	{
		$data = array();

		if ( $data = $this->user->get($id)  )
		{
			// 获取模型数据
			$model = m('member.model')->get($data['modelid']);

			if ( $model['tablename'] )
			{
				// 获取当前模型的数据表和字段
				$this->table	= $model['tablename'];

				$data = array_merge($data, parent::get($id));
			}
		}

		return empty($field) ? $data : $data[$field];
	}

	/*
	 *  检查用户名是否包含禁止使用的内容，如禁止使用 admin* 等作为名称
	 */
	public function checkname($str)
	{
		$banned = trim(c('member.register_banned')); //多个之间使用换行隔开

		if ( $str and $banned )
		{
			return !@preg_match('/^('.str_replace(array('\\*','\\?', "\r\n", "\n", ' '), array('.*','.?', '|', '|', ''), preg_quote($banned, '/')).')$/i', $str);
		}

		return true;
	}

	/*
	 *  新增数据
	 */
	public function add($data)
	{
		if ( $this->checkname($data['username']) == false ) return $this->error(t('用户名包含禁止使用的字符'));
		if ( $this->checkname($data['nickname']) == false ) return $this->error(t('昵称包含禁止使用的字符'));
		if ( empty($data['modelid']) ) return $this->error(t('模型不能为空'));

		// 获取模型数据
		$model = m('member.model')->get($data['modelid']);

		// 获取当前模型的数据表和字段
		$this->table	= $model['tablename'];

		// 设置默认值
		$data['groupid'] = $data['groupid'] ? $data['groupid'] : $model['settings']['groupid'];
		$data['point'] = $data['point'] ? $data['point'] :  $model['settings']['point'];
		$data['amount'] = $data['amount'] ? $data['amount']  : $model['settings']['amount'];

		if ( $insertid = $this->user->add($data) )
		{
			$data['id'] = $insertid;

			if ( $this->table and $this->insert($data) )
			{
				member_api::validmail($data['email'], $data);

				return $data['id'];
			}

			// 注册失败
			$this->user->delete($insertid);
			return false;
		}

		return $this->error($this->user->error());
	}

	/*
	 *  编辑数据
	 */
	public function edit($data,$id)
	{
		if ( $this->checkname($data['username']) == false ) return $this->error(t('用户名包含禁止使用的字符'));
		if ( $this->checkname($data['nickname']) == false ) return $this->error(t('昵称包含禁止使用的字符'));

		if ( empty($data['modelid']) ) return $this->error(t('模型不能为空'));

		// 获取当前模型的数据表
		$this->table	= m('member.model')->get($data['modelid'], 'tablename');

		if ( $this->user->edit($data, $id) )
		{
			$data['id'] = $id;

			if ( $this->table and $this->update($data, $id) )
			{
				return true;
			}

			return true;
		}

		return $this->error($this->user->error());
	}

	/*
	 *  删除数据
	 */
	public function delete($id)
	{
		if ( is_array( $id ) )
		{
			return array_map( array($this,'delete'), $id );
		}

		if ( $user = $this->user->getbyid($id)  )
		{
			// 获取当前模型的数据表
			$this->table	= m('member.model')->get($user['modelid'], 'tablename');

			if ( $this->table and $this->user->delete($id) )
			{
				return parent::delete($id);
			}

			return $this->error($this->user->error());
		}

		return $this->error(t('数据[ %s ]不存在'));
	}

	/**
	 * 根据ID设置状态
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function disabled($id, $disabled=1)
	{
		if ( is_array( $id ) )
		{
			return array_map( array($this,'disabled'), $id, array_fill(0, count($id), $disabled) ); //第二个参数必须是和第一个同样的数组
		}

		return $this->user->disabled($id, $disabled);
	}


}
?>