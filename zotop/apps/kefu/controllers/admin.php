<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 在线客服 后台控制器
*
* @package		kefu
* @version		1.8
* @author		
* @copyright	
* @license		
*/
class kefu_controller_admin extends admin_controller
{
	protected $kefu;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->kefu = m('kefu.kefu');
	}

 	/**
	 * id list
	 *
	 */
	public function action_index()
	{
		if ( $post = $this->post() )
		{
			if ( $this->kefu->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->kefu->error());
		}

		$data = $this->kefu->select();

		$this->assign('title',t('客服管理'));
		$this->assign('data',$data);
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
					$result = $this->kefu->where('id','in',$post['id'])->delete();
					break;
				default :
					break;
			}

			if ( $result )
			{
				return $this->success(t('%s成功',$post['operation']),u("kefu/admin"));
			}
			$this->error(t('%s失败',$post['operation']));
		}

		$this->error(t('禁止访问'));
    }

	/**
	 * 添加客服
	 *
	 */
	public function action_add()
	{
		if ( $post = $this->post() )
		{
			if ( $this->kefu->add($post) )
			{
				return $this->success(t('保存成功'),u('kefu/admin'));
			}
			return $this->error($this->kefu->error());
		}

		$data = array('type'=>'qq');

		$this->assign('title',t('添加'));
		$this->assign('data',$data);
		$this->display('kefu/admin_post.php');
	}

 	/**
	 * 编辑客服
	 *
	 */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->kefu->edit($post,$id) )
			{
				return $this->success(t('保存成功'),u('kefu/admin'));
			}
			return $this->error($this->kefu->error());
		}

		$data = $this->kefu->getbyid($id);

		$this->assign('title',t('编辑'));
		$this->assign('data',$data);
		$this->display('kefu/admin_post.php');
	}

	/**
	 * 客服选项
	 *
	 */
	public function action_options()
	{
		
		$template = 'kefu/admin_post_'.$_POST['type'].'.php';
		
		$this->assign('data',$_POST);
		$this->display($template);
	}

 	/**
	 * 删除客服
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->kefu->delete($id) )
		{
			return $this->success(t('删除成功'),u('kefu/admin'));
		}
		return $this->error($this->kefu->error());
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
			$count = $this->kefu->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->kefu->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}
}
?>