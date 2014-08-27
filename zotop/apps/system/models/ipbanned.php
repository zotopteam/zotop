<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * ipbanned
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team 
 * @license		http://zotop.com/license.html
 */
class system_model_ipbanned extends model
{
	protected $pk = 'ip';
	protected $table = 'ipbanned';

    /**
     * 添加
     * 
     */	
	public function add($data)
	{	
		if ( empty($data['ip']) ) return $this->error(t('ip不能为空'));

		if ( empty($data['expires']) ) return $this->error(t('有效期不能为空'));		

		if ( !preg_match("/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/",$data['ip']))
		{
			return $this->error(t('IP格式错误'));
		}
		
		// 将字符串转化为时间戳
		$data['expires'] = strtotime($data['expires']);
		
		return $this->insert($data, true);
	}


    /**
     * 保存前处理数据
     * 
     */	
	public function edit($data, $ip)
	{	
		if ( empty($data['ip']) ) return $this->error(t('ip不能为空'));

		if ( empty($data['expires']) ) return $this->error(t('有效期不能为空'));		

		if( !preg_match("/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/",$data['ip']))
		{
			return $this->error(t('IP格式错误'));
		}
		
		// 将字符串转化为时间戳
		$data['expires'] = strtotime($data['expires']);
		
		return $this->update($data, $ip);
	}

	/*
	 * 是否禁止ip
	 *
	 * @params string $ip ip
	 * 
	 * @return bool  
	 */
	public function isbanned($ip)
	{	
		return $this->where('ip','=',$ip)->where('expires','>',ZOTOP_TIME)->exists();
	}

	/*
	 * 添加一个禁止IP，有效期为小时
	 *
	 * @params string $ip ip
	 * @params string $expires 禁止时间，单位小时
	 * @return bool  
	 */
	public function banned($ip, $expires=72)
	{
		$expires = ZOTOP_TIME + intval($expires)*60*60;

		if ( $this->where('ip','=',$ip)->exists() )
		{
			return $this->update(array('expires'=>$expires),$ip);
		}
		return $this->insert(array(
			'ip'=>$ip,
			'expires'=>$expires
		),true);
	}
}
?>