<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 用户模型
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_user extends model
{
	protected $pk = 'id';
	protected $table = 'user';

	/**
	 * 检查用户名是否符合规定
	 *
	 * @param STRING $username 要检查的用户名
	 * @return 	TRUE or FALSE
	 */
	public function checkusername($username)
	{
		// 检查长度
		if ( strlen($username) > 32 or strlen($username) < 2 )
		{
			return false;
		}

		// 检测输入中是否含有非法字符
		foreach(array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#") as $value)
		{
			if( strpos($username, $value) ) return false;
		}

		if( !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username))
		{
			return false;
		}

		return true;
	}

    /**
     * 插入前处理数据
     *
     */
	public function add($data)
	{
		// 如果有用户名，检查用户名
		if ( $data['username'] )
		{
			if ( $this->checkusername($data['username']) == false )  return $this->error(t('用户名不符合要求'));
			if ( $this->where('username',$data['username'])->exists() ) return $this->error(t('用户名已经存在'));
		}

		// 检查邮箱是否已经存在
		if ( $data['email'] and $this->where('email',$data['email'])->exists() ) return $this->error(t('邮箱已经存在'));
		
		// 检查手机号是否存在
		if ( $data['mobile'] and $this->where('mobile',$data['mobile'])->exists() ) return $this->error(t('手机号已经存在'));

		// 加入salt
		$data['salt']     = substr(uniqid(rand()), -6);
		$data['password'] = $this->password($data['password'], $data['salt']);
		$data['regtime']  = ZOTOP_TIME;
		$data['regip']    = request::ip();

		if ( $data['id'] = $this->insert($data,true) )
		{
			zotop::run('user.add', $data);
			return $data['id'];
		}

		return false;
	}

    /**
     * 更新前处理数据
     *
     */
	public function edit($data, $id)
	{
		if ( empty($id) or intval($id) == 0 ) return $this->error(t('用户编号错误'));

		if ( $data['username'] and $this->checkusername($data['username']) == false )
		{
			return $this->error(t('用户名不符合要求'));
		}

		// 保存前加密密码
		if ( !empty($data['password']) )
		{
			$data['salt']     = $data['salt'] ? $data['salt'] : $this->field('salt')->where('id',$id)->getField();
			$data['password'] = $this->password($data['password'], $data['salt']);
		}
		else
		{
			unset($data['password']);
		}

		// 保存前自动设置更新时间
		$data['updatetime'] = ZOTOP_TIME;

		if ( $this->where('id',$id)->data($data)->update() )
		{
			$data['id'] = $id;
			zotop::run('user.edit', $data);
			return $data['id'];
		}

		return false;
	}

    /**
     * 删除用户
     *
     */
	public function delete($id='')
	{
		if ( parent::delete($id) )
		{
			zotop::run('user.delete', $id);
			return true;
		}

		return false;
	}

	/**
	 * 根据uid获取头像url
	 *
	 * @param int $uid 用户id
	 * @param string $size 返回的尺寸，可以是'big','middle','small'三个之一
	 * @return array 四个尺寸用户头像数组
	 */
	public function avatar($uid=0, $size='middle', $path=false)
	{

		// hook
		$params = func_get_args();
		$avatar = zotop::filter('user.avatar', $avatar, $params);

		if ( $avatar ) return $avatar;

		// 调用用户上传
		$size = in_array(strtolower($size), array('big', 'middle', 'small','tiny')) ? $size : 'middle';

		if ( intval($uid) )
		{
			$uid = abs(intval($uid));
			$dir1 = ceil($uid / 10000);
			$dir2 = ceil($uid % 10000 / 1000);

			$avatar = '/avatar/'.$dir1.'/'.$dir2.'/'.$uid.'/'.$size.'.jpg';

			if ( $path ) return ZOTOP_PATH_UPLOADS.$avatar;

			if ( file::exists(ZOTOP_PATH_UPLOADS.$avatar) ) return ZOTOP_URL_UPLOADS.$avatar;
		}


		return ZOTOP_URL_UPLOADS."/avatar/{$size}.gif";

	}

	/**
	 * 对密码进行加密，返回加密后的密码
	 *
	 * @param string $password 原密码
	 * @param string $salt 密码盐
	 * @return string 加密后的密码
	 */
	public function password($password, $salt)
	{
	    return md5(md5($password).$salt);
	}

	/**
	 * 写入登陆信息
	 *
	 */
	public function login(array $data)
	{
		@extract($data);

		//读取用户,允许使用户名、邮箱或者手机号登录
		$user = $this->where(array('username','=',$username),'or',array('email','=',$username),'or',array('mobile','=',$username))->getRow();

		//用户不存在
		if( empty($user) )
		{
			return $this->error(t('账户 <b>%s</b> 不存在，请检查是否输入有误',$username));
		}

		//验证密码
		if ( $user['password'] != $this->password($password, $user['salt']) )
		{
			return $this->error(t('密码 <b>%s</b> 错误，请检查是否输入有误',$password));
		}

		//验证状态
		if ( $user['disabled'] )
		{
			return $this->error(t('账户 <b>%s</b> 已经被禁用', $username));
		}

		zotop::run('user.beforelogin',$user);

		//记录用户数据，TODO 使用session存储重要的登录信息
		zotop::cookie('user.id',$user['id'], (int)$cookietime);
		zotop::cookie('user.username',$user['username'], (int)$cookietime);
		zotop::cookie('user.nickname',$user['nickname'], (int)$cookietime);
		zotop::cookie('auth',zotop::encode($user['id']."\t".$user['username']."\t".$user['password']."\t".$user['groupid']."\t".$user['modelid']), (int)$cookietime);

		//刷新信息
		$this->refresh($user['id']);

		//hook
		zotop::run('user.login',$user);

		//返回用户的详细信息
		return $user;
	}

	/**
	 * 清除登陆信息
	 *
	 */
	public function logout()
	{
        //记录用户数据
        zotop::cookie('user.id', null);
		zotop::cookie('user.username', null);
		zotop::cookie('user.name', null);
		zotop::cookie('auth', null);

		//hook
		zotop::run('user.logout');

		return true;
	}

	/**
	 * 刷新用户状态，包括登录时间以及登录次数等
	 *
	 * @param int $id 用户编号
	 * @return bool
	 */
	public function refresh($id)
	{
	    if( empty($id) ) return false;

	    return $this->where('id',intval($id))->data(array(
			'logintime'  => ZOTOP_TIME,
			'logintimes' => array('logintimes','+',1),
			'loginip'    => request::ip()
	    ))->update();
	}

	/**
	 * 积分
	 *
	 * @param int $id 用户编号
	 * @return bool
	 */
	public function point($id, $point)
	{
	    if( empty($id) or intval($point) == 0 ) return false;

	    return $this->db()->where('id', intval($id))->data('point',array('point','+',intval($point)))->update();
	}

	/**
	 * 根据应用的ID设置应用状态
	 *
	 * @param string $id 应用ID
	 * @return bool
	 */
	public function disabled($id, $disabled)
	{
		if ( $id == 1 and intval($disabled) == 1 )
		{
			return $this->error(t('系统管理员不能被锁定'));
		}

		return $this->update(array('disabled'=>intval($disabled)), $id);
	}
}
?>