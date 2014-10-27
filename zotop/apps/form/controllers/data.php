<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 后台控制器
*
* @package		form
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class form_controller_data extends admin_controller
{
	protected $data;

	/**
	 * 重载权限检测，当动作为check时不检查权限
	 * 
	 * @return mixed
	 */
	public function __check()
	{
		ZOTOP_ACTION != 'check' && parent::__check();		
	}	

	/**
	 * index
	 *
	 */
	public function action_index($formid)
    {	
    	// 获取表单设置
    	$form 	= m('form.form.get', $formid);

		// 获取显示字段
		$fields = m('form.field.cache', $formid);

		if( $fields )
		{
		
			foreach($fields as $i=>$r)
			{
				if ( $r['list'] ) $list[$r['name']] = $r;

				if ( $r['order'] ) $orderby[$r['name']] = $r['order'];

				if ( $r['search'] ) $search[$r['name']] = $r['label'];
			}

			$this->data = m('form.data.init',$formid);

			if ( $orderby and is_array($orderby) )
			{
				$this->data->orderby($orderby);
			}
			else
			{
				$this->data->orderby('id','desc');
			}

			if ( $search and $keywords = $_REQUEST['keywords'] )	
			{
				$_search = array();

				foreach ($search as $key => $r)
				{
					$_search[] = 'or';
					$_search[] = array($key,'like', $keywords);
				}

				array_shift($_search);

				$this->data->where($_search);
			}	

			$dataset = $this->data->getPage();

		}

		$this->assign('title',$form['name']);		
		$this->assign('formid',$formid);
		$this->assign('form',$form);
		$this->assign('fields',$fields);
		$this->assign('keywords',$keywords);
		$this->assign('search',$search);
		$this->assign('list',$list);
		$this->assign($dataset);
		$this->display();
	}

	/**
	 * 添加
	 *
	 */
	public function action_add($formid)
    {
		$this->data = m('form.data.init',$formid);

		if ( $post = $this->post() )
		{
			if ( $this->data->add($post) )
			{
				return $this->success(t('保存成功'), u('form/data/index/'.$formid));
			}

			return $this->error($this->data->error());
		}

		$fields = m('form.field')->getFields($formid);

		$this->assign('title',t('添加'));
		$this->assign('formid',$formid);
		$this->assign('fields',$fields);
		$this->assign('data',$data);
		$this->display('form/data_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($formid, $id)
    {
		$this->data = m('form.data.init',$formid);

		if ( $post = $this->post() )
		{
			if ( $this->data->edit($post, $id) )
			{
				return $this->success(t('保存成功'), u('form/data/index/'.$formid));
			}

			return $this->error($this->data->error());
		}

		$data = $this->data->get($id);

		$fields = m('form.field')->getFields($formid, $data);

		//debug::dump($fields,true);

		$this->assign('title',t('编辑'));
		$this->assign('formid',$formid);
		$this->assign('fields',$fields);
		$this->assign('data',$data);
		$this->display('form/data_post.php');
	}


    /**
     * 删除
     *
	 * @param  int $formid 表单编号
 	 * @param  int $id 数据编号
 	 * @return json 操作结果
     */
	public function action_delete($formid, $id)
	{
		if( $this->post() )
		{
			$this->data = m('form.data.init',$formid);

			if ( $this->data->delete($id) )
			{
				return $this->success(t('删除成功'),request::referer());
			}

			return $this->error($this->data->error());
		}
	}

    /**
     * 检测字段值是否已经存在
     * 
     * @param  int $formid 表单编号
     * @param  string $key  字段名称
     * @param  string $ignore 避忽略的数据
     * @return json
     */
	public function action_check($formid, $key, $ignore='')
	{
		$this->data = m('form.data.init',$formid);

		if ( empty($ignore) )
		{
			$count = $this->data->where($key,$_GET[$key])->count();
		}
		else
		{
			$count = $this->data->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}

}
?>