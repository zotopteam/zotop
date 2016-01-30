<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员 后台控制器
*
* @package		form
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class form_controller_field extends admin_controller
{
	protected $form;
	protected $field;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->form 	= m('form.form');
		$this->field 	= m('form.field');		
	}

	/**
	 * index
	 *
	 */
	public function action_index($formid)
    {
		if ( $post = $this->post() )
		{
			if ( $this->field->order($post['id']) )
			{
				return $this->success(t('操作成功'));
			}

			return $this->error($this->field->error());
		}

		// 获取模型字段
		$data = $this->field->cache($formid);

		$this->assign('title',t('字段管理'));
		$this->assign('data',$data);
		$this->assign('formid',$formid);
		$this->assign('controls',$this->field->controls);
		$this->display();
	}

	/**
	 * 添加
	 *
	 */
	public function action_add($formid)
    {
		if ( $post = $this->post() )
		{
			if ( $this->field->add($post) )
			{
				return $this->success(t('保存成功'),u('form/field/index/'.$post['formid']));
			}

			return $this->error($this->field->error());
		}

		$data = array(
			'formid'	=> $formid,
			'control'	=> 'text',
			'type'		=> $this->field->controls['text']['type'],
			'length'	=> $this->field->controls['text']['length']
		);

		$this->assign('title',t('添加字段'));
		$this->assign('data',$data);
		$this->assign('formid',$formid);
		$this->assign('controls',$this->field->controls);
		$this->display('form/field_post.php');
	}

	/**
	 * 编辑
	 *
	 */
	public function action_edit($id)
    {
		if ( $post = $this->post() )
		{
			if ( $this->field->edit($post, $id) )
			{
				return $this->success(t('保存成功'),u('form/field/index/'.$post['formid']));
			}

			return $this->error($this->field->error());
		}

		$data = $this->field->get($id);

		$this->assign('title',t('编辑字段'));
		$this->assign('data',$data);
		$this->assign('formid',$data['formid']);
		$this->assign('controls',$this->field->controls);
		$this->display('form/field_post.php');
	}

    /**
     * 设置状态，禁用或者启用
     *
 	 * @param  int $id 编号
 	 * @return json 操作结果
     */
	public function action_status($id)
	{
		if ( $this->field->status($id) )
		{
			return $this->success(t('操作成功'),request::referer());
		}
		return $this->error($this->form->error());
	}	

    /**
     * 删除
     *
 	 * @param  int $id 编号
 	 * @return json 操作结果
     */
	public function action_delete($id)
	{
		if( $this->post() )
		{
			if ( $this->field->delete($id) )
			{
				return $this->success(t('删除成功'),request::referer());
			}

			return $this->error($this->field->error());
		}
	}

    /**
     * 检查字段名称是否被占用
     *
     * @return bool
     */
	public function action_check($key, $ignore='')
	{
		$ignore = empty($ignore) ? $_GET['ignore'] : $ignore;

		if ( empty($ignore) )
		{
			$count = $this->field->where($key, $_GET[$key])->count();
		}
		else
		{
			$count = $this->field->where($key,$_GET[$key])->where($key,'!=',$ignore)->count();
		}

		exit($count ? '"'.t('已经存在，请重新输入').'"' : 'true');
	}



	// 获取控件的设置
	public function action_settings()
	{

		// 获取当前控件
		$control = $_POST['control'];

		// 获取全部控件
		$controls = $this->field->controls;

		// 获取模板
		$template = $controls[$control]['settings'] ?  $controls[$control]['settings'] : A('form.path').DS.'templates'.DS.'field_settings'.DS.$control.'.php';

		if ( !file::exists($template) )
		{
			exit('');
		}

		$this->assign('data',$_POST);
		$this->display($template);
	}

	/**
	 * 设置数组
	 *
	 */
	private function settings_fields($data)
	{
		$settings_fields = array();

		foreach ( $this->settings_fields as $name => $setting )
		{
			$settings_fields[$name]['label'] = $setting['label'];
			$settings_fields[$name]['tips'] = $setting['tip'];
			$settings_fields[$name]['for'] = $name;
			$settings_fields[$name]['required'] = $setting['required'];

			$settings_fields[$name]['field']['id'] = "settings_fields_{$name}";
			$settings_fields[$name]['field']['name'] = "settings_fields[{$name}]";
			$settings_fields[$name]['field']['value'] = isset($data['settings_fields'][$name]) ? $data['settings_fields'][$name]  : $setting['value'];

			// 其他属性全部都设为字段属性
			foreach ( $setting as $k=>$s )
			{
				if ( in_array($k, array('label','tip','name','value','id')) ) continue;

				$settings_fields[$name]['field'][$k] = $s;
			}
		}

		return $settings_fields;
	}
}
?>