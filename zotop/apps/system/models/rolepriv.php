<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 角色/权限对应表
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_rolepriv extends model
{
	protected $pk = 'groupid';
	protected $table = 'admin_priv_group';

	/**
	 * 设置权限
	 *
	 * @param array $privids 权限编号组
	 * @param string $groupid 角色编号
	 * @return boo
	 */
	public function set($groupid, $privids)
	{
		if ( $groupid == 1 ) return true;

		// 删除当前角色的全部权限
		$this->where('groupid',$groupid)->delete();

		// 插入全部权限
		if ( is_array($privids) )
		{
			foreach( $privids as $privid )
			{
				$this->insert(array('groupid'=>$groupid,'privid'=>$privid),true);
			}
		}

		// 刷新缓存
		return $this->cache($groupid,true);
	}

	/**
	 * 写入日志
	 *
	 * @param array $privids 权限编号组
	 * @param string $groupid 角色编号
	 * @return boo
	 */
	public function cache($groupid, $refresh=false)
	{
		$cacheid = "priv.{$groupid}";

		$cache = zotop::cache($cacheid);

		if ( empty($cache) or $refresh )
		{
			$cache = array();

			$data = $this->alias('rp')->join('admin_priv as p','rp.privid','p.id','left')->where('rp.groupid',$groupid)->getAll();

			foreach ( $data as $r )
			{
				$cache[] = array($r['app'], $r['controller'], $r['action']);
			}

			if ( empty($cache) )
			{
				zotop::cache($cacheid, null); //删除缓存
			}
			else
			{
				zotop::cache($cacheid, $cache, false); //设置缓存
			}
		}

		return $cache;
	}
}
?>