<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 会员 后台控制器
*
* @package		content
* @version		1.0
* @author		zotop.chenlei
* @copyright	zotop.chenlei
* @license		http://www.zotop.com
*/
class content_controller_field extends admin_controller
{
	protected $content;
	protected $field;

	/**
	 * 重载__init函数
	 */
	public function __init()
	{
		parent::__init();

		$this->model 	= m('content.model');
		$this->field 	= m('content.field');		
	}

	/**
	 * 字段管理首页
	 * 
	 * @param  string $modelid 模型编号
	 * @return mixed
	 */
	public function action_index($modelid)
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
		$data = $this->field->cache($modelid);

		$this->assign('title', t('字段管理'));		
		$this->assign('modelid',$modelid);
		$this->assign('controls',$this->field->controls);
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 表单预览
	 * 
	 * @param  string $modelid 模型编号
	 * @return mixed
	 */
	public function action_view($modelid)
    {
		$this->assign('title', t('表单预览'));		
		$this->assign('modelid',$modelid);
		$this->display();
	}

	/**
	 * 添加
	 *
	 */
	public function action_add($modelid)
    {
		if ( $post = $this->post() )
		{
			if ( $this->field->add($post) )
			{
				return $this->success(t('保存成功'),u('content/field/index/'.$post['modelid']));
			}

			return $this->error($this->field->error());
		}

		$data = array(
			'modelid'	=> $modelid,
			'control'	=> 'text',
			'type'		=> $this->field->controls['text']['type'],
			'length'	=> $this->field->controls['text']['length']
		);

		$this->assign('title',t('新建字段'));
		$this->assign('data',$data);
		$this->assign('modelid',$modelid);
		$this->assign('controls',$this->field->controls);
		$this->display('content/field_post.php');
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
				return $this->success(t('保存成功'),u('content/field/index/'.$post['modelid']));
			}

			return $this->error($this->field->error());
		}

		$data = $this->field->get($id);

		$this->assign('title',t('修改字段'));
		$this->assign('data',$data);
		$this->assign('modelid',$data['modelid']);
		$this->assign('controls',$this->field->controls);
		$this->display('content/field_post.php');
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
		return $this->error($this->content->error());
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

	/**
	 * ajax 获取控件的属性设置
	 * @return [type] [description]
	 */
	public function action_settings()
	{

		// 获取当前控件
		$control = $_POST['control'];

		// 获取全部控件
		$controls = $this->field->controls;

		// 获取模板
		$template = $controls[$control]['settings'] ?  $controls[$control]['settings'] : A('content.path').DS.'templates'.DS.'field_settings'.DS.$control.'.php';

		if ( !file::exists($template) )
		{
			//$template = A('content.path').DS.'templates'.DS.'field_settings'.DS.'common.php';
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