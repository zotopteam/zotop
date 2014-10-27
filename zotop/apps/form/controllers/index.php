<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 自定义表单 首页控制器
*
* @package		form
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class form_controller_index extends site_controller
{
	/**
	 * index 动作
	 *
	 */
	public function action_index()
    {

		$this->assign('title',t('自定义表单'));
		$this->assign('data',$data);
		$this->display('form/index.php');
	}

	/**
	 * index 动作
	 *
	 */
	public function action_list($formid)
    {
 		// 获取表单数据
		$form = m('form.form.get',$formid);

		if ( empty($form) or $form['settings']['list'] == 0 )
		{
			return $this->_404(t('表单不存在', $formid));
		}

		$this->assign('title',$form['name']);
		$this->assign('formid',$formid);
		$this->assign('form',$form);
		$this->display($form['settings']['listtemplate']);
	}

	public function action_detail($formid, $id)
	{
		// 获取表单数据
		$form = m('form.form.get',$formid);

		if ( empty($form) or $form['settings']['detail'] == 0 )
		{
			return $this->_404(t('表单不存在', $formid));
		}

		// 获取当前表单字段
		$fields = m('form.field.getall',$formid);

		// 获取当前条目详细数据
		$data 	= m('form.data.init',$formid)->get($id);

		if ( empty($data) )
		{
			return $this->_404(t('数据不存在', $id));
		}

		$this->assign('title',$form['name']);
		$this->assign('id',$id);
		$this->assign('formid',$formid);
		$this->assign('form',$form);
		$this->assign('fields',$fields);
		$this->assign('data',$data);
		$this->display($form['settings']['detailtemplate']);		
	}
}
?>