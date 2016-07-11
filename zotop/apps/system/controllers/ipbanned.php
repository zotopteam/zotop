<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * ip禁止
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_ipbanned extends admin_controller
{
	protected $ipbanned;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->ipbanned = m('system.ipbanned');
	}

 	/**
	 * ip list
	 *
	 */
	public function action_index()
	{
		// 删除过期ip
		$this->ipbanned->where('expires','<',ZOTOP_TIME)->delete();

		// 获取全部数据
		$dataset = $this->ipbanned->orderby('expires','asc')->paginate();

		$this->assign('title',t('IP禁止'));
		$this->assign($dataset);
		$this->display();
	}

	/**
	 * 多选操作
	 *
	 * @param $operation 操作
	 * @return mixed
	 */
    public function action_operate($operation)
    {
		if ( $post = $this->post() )
		{
			switch($operation)
			{
				case 'delete' :
					$result = $this->ipbanned->where('ip','in',$post['ip'])->delete();
					break;
				default :
					break;
			}

			if ( $result )
			{
				return $this->success(t('%s成功',$post['operation']),u("system/ipbanned"));
			}
			$this->error(t('%s失败',$post['operation']));
		}

		$this->error(t('禁止访问'));
    }

	/**
	 * 添加IP
	 *
	 */
	public function action_add($ip=null, $expires=7)
	{
		if ( $post = $this->post() )
		{
			if ( $this->ipbanned->add($post) )
			{
				return $this->success(t('%s成功',t('保存')));
			}
			return $this->error($this->ipbanned->error());
		}

		$data = array();

		if ( $ip ) $data['ip'] = $ip;
		if ( $expires ) $data['expires'] = ZOTOP_TIME + $expires*24*3600;

		$this->assign('title',t('添加%s',t('IP')));
		$this->assign('data',$data);
		$this->assign('expires',$expires);
		$this->display('system/ipbanned_post.php');
	}

 	/**
	 * 编辑IP
	 *
	 */
	public function action_edit($ip)
	{
		if ( $post = $this->post() )
		{
			if ( $this->ipbanned->edit($post,$ip) )
			{
				return $this->success(t('%s成功',t('保存')));
			}
			return $this->error($this->ipbanned->error());
		}

		$data = $this->ipbanned->getbyip($ip);

		$this->assign('title',t('编辑%s',t('IP')));
		$this->assign('data',$data);
		$this->display('system/ipbanned_post.php');
	}

 	/**
	 * 删除IP
	 *
	 */
	public function action_delete($ip)
	{
		if ( $this->ipbanned->delete($ip) )
		{
			return $this->success(t('删除成功'),u('system/ipbanned'));
		}
		return $this->error($this->ipbanned->error());
	}

    /**
     * 检查是否被占用
     *
     * @return bool
     */
	public function action_check($key, $ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->ipbanned->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->ipbanned->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}
}
?>