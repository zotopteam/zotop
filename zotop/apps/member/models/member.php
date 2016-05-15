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
	protected $pk    = 'id'; //主键名称
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
		extract($data);

		if ( $user = $this->user->checklogin($username,$password,$expire) )
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
			$model = m('member.model')->getbyid($data['modelid']);

			// 当前模型数据表
			if ( $this->db->existsTable($model['tablename']) )
			{

				$this->table = $model['tablename'];

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

	/**
	 * 检查并完善添加编辑时传入的数据
	 *
	 * @param  array $data 数据
	 * @return array
	 */
	private function _data($data)
	{
		if ( empty($data['modelid']) ) return $this->error(t('模型不能为空'));

		if ( $data['modelid'] != 'admin' )
		{
			if ( isset($data['username']) and $this->checkname($data['username']) == false ) return $this->error(t('用户名包含禁止使用的字符'));
			if ( isset($data['nickname']) and $this->checkname($data['nickname']) == false ) return $this->error(t('昵称包含禁止使用的字符'));
		}

        // 预处理数据
		$fields = m('member.field.cacheall', $data['modelid']);
		$fields = zotop::filter('member.field.data',$fields, $data, $this);

        foreach ($fields as $name => $field)
        {
        	// 不检查禁用字段
        	if ( $field['disabled'] ) continue;

        	// 资料修改时候密码可以为空 TODO 写进HOOK里面
        	if ( $name=='password' and $data['id'] and empty($data['password']) )
        	{
				$field['notnull'] = false;
				$field['settings']['minlength'] = null;
        	}

        	// 检查是否允许为空
        	if ( isset($data[$name]) and $field['notnull'] and empty($data[$name]) ) return $this->error(t('$1不能为空', $field['label']));

        	// 检查是否唯一值
        	if ( $field['unique'] and $data[$name] )
        	{
	        	$mtable = $field['system'] ? 'user' : m('member.model.get', $data['modelid'],'tablename');
				$mquery = $data['id'] ? $this->db->table($mtable)->where('id','!=', $data['id']) : $this->db->table($mtable);

				if ( $mquery->where($name,$data[$name])->count() )
				{
					return $this->error(t('$1的值 $2 已经存在', $field['label'], $data[$name]));
				}
        	}

        	// 有值的时候才检查和处理
        	if ( $data[$name] )
        	{
            	if ( $field['settings']['maxlength'] and str::len($data[$name]) > $field['settings']['maxlength'] ) return $this->error(t('$1最大长度为$2', $field['label'],$field['settings']['maxlength']));
            	if ( $field['settings']['minlength'] and str::len($data[$name]) < $field['settings']['minlength'] ) return $this->error(t('$1最小长度为$2', $field['label'],$field['settings']['minlength']));
            	if ( $field['settings']['max'] and intval($data[$name]) > $field['settings']['max'] ) return $this->error(t('$1最大值为$2', $field['label'],$field['settings']['max']));
            	if ( $field['settings']['min'] and intval($data[$name]) < $field['settings']['min'] ) return $this->error(t('$1最小值为$2', $field['label'],$field['settings']['min']));            	
            	if ( $field['control'] == 'keywords' and is_string($data[$name]) ) $data[$name] = str_replace('，', ',', $data[$name]);
            	if ( $field['control'] == 'files' and is_array($data[$name]) ) $data[$name] = array_values($data[$name]);            	
        	}

            if ( $field['control'] == 'date' or $field['control'] == 'datetime' ) $data[$name] = empty($data[$name]) ? ZOTOP_TIME : strtotime($data[$name]);

        }

		return zotop::filter('member.data',$data, $this);
	}

	/**
	 * 添加用户
	 * 
	 * @param array $data 用户数据
	 * @return bool
	 */
	public function add($data)
	{
		if ( $data = $this->_data($data) )
		{
			// 获取模型数据
			$model = m('member.model')->getbyid($data['modelid']);

			// 设置默认值
			$data['groupid'] = $data['groupid'] ? $data['groupid'] : $model['settings']['groupid'];
			$data['point']   = $data['point'] ? $data['point'] :  $model['settings']['point'];
			$data['amount']  = $data['amount'] ? $data['amount']  : $model['settings']['amount'];

			if ( $insertid = $this->user->add($data) )
			{
				$data['id'] = $insertid;

				// 当前模型数据表
				if ( $this->db->existsTable($model['tablename']) )
				{
					$this->table = $model['tablename'];

					if ( !$this->insert($data) )
					{
						// 附表插入失败时候，删除主表数据
						$this->user->delete($insertid);
						return false;
					}
				}				

				zotop::filter('member.add',$data);
				return $data['id'];

			}

			return $this->error($this->user->error());
		}

		return false;
	}

	/*
	 *  编辑数据
	 */
	public function edit($data,$id)
	{
		$data['id'] = $id;

		if ( $data = $this->_data($data) )
		{
			if ( $this->user->edit($data, $id) )
			{
				$model = m('member.model')->getbyid($data['modelid']);

				if ( $this->db->existsTable($model['tablename']) )
				{
					$this->table= $model['tablename'];					
					$this->data($data)->insert(true);
				}

				zotop::filter('member.edit',$data);
				return true;
			}

			return $this->error($this->user->error());
		}

		return false;
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
			$model = m('member.model')->getbyid($data['modelid']);

			if ( $this->db->existsTable($model['tablename']) )
			{
				$this->table = $model['tablename'];
			}

			if ( $this->user->delete($id) )
			{
				if ( $this->table ) parent::delete($id);
				return true;
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