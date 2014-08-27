<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 敏感词管理
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_badword extends admin_controller
{
	protected $badword;

	/*
	 * 初始化
	 */
	public function __init()
	{
		parent::__init();

		$this->badword = m('system.badword');
	}

 	/**
	 * id list
	 *
	 */
	public function action_index()
	{
		$dataset = $this->badword->orderby('listorder','asc')->getPage();

		$this->assign('title',t('敏感词管理'));
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
					$result = $this->badword->where('id','in',$post['id'])->delete();
					break;
				default :
					break;
			}

			if ( $result )
			{
				return $this->success(t('%s成功',$post['operation']),u("system/badword"));
			}
			$this->error(t('%s失败',$post['operation']));
		}

		$this->error(t('禁止访问'));
    }

	/**
	 * 添加敏感词
	 *
	 */
	public function action_add()
	{
		if ( $post = $this->post() )
		{
			if ( $this->badword->add($post) )
			{
				return $this->success(t('%s成功',t('保存')),u('system/badword'));
			}
			return $this->error($this->badword->error());
		}

		$data = array();

		$this->assign('title',t('添加%s',t('敏感词')));
		$this->assign('data',$data);
		$this->display('system/badword_post.php');
	}

 	/**
	 * 编辑敏感词
	 *
	 */
	public function action_edit($id)
	{
		if ( $post = $this->post() )
		{
			if ( $this->badword->edit($post,$id) )
			{
				return $this->success(t('%s成功',t('保存')),u('system/badword'));
			}
			return $this->error($this->badword->error());
		}

		$data = $this->badword->getbyid($id);

		$data['expires'] = format::date($data['expires']);

		$this->assign('title',t('编辑%s',t('敏感词')));
		$this->assign('data',$data);
		$this->display('system/badword_post.php');
	}

 	/**
	 * 删除敏感词
	 *
	 */
	public function action_delete($id)
	{
		if ( $this->badword->delete($id) )
		{
			return $this->success(t('删除成功'),u('system/badword'));
		}
		return $this->error($this->badword->error());
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
			$count = $this->badword->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->badword->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}
}
?>