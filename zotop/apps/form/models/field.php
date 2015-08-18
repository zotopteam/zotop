<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * form_model_field
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class form_model_field extends model
{
	protected $pk 		= 'id';
	protected $table 	= 'form_field';

	public $controls;
	public $system_fields = array('id','dataid','status','formid','table','select','orderby','page','size','cache','return','search','keywords');

	public function __construct()
	{
        parent::__construct();

        // 控件类型
		$this->controls = zotop::filter('form.field.controls',array(
            'text'		=> array('name'=>t('单行文本') ,'type'=>'varchar', 'length'=>'255'),
			'textarea'	=> array('name'=>t('多行文本') ,'type'=>'text'),
			'number'	=> array('name'=>t('数字'),'type'=>'int', 'length'=>'10'),
			'radio'		=> array('name'=>t('单选'),'type'=>'varchar', 'length'=>'50'),
			'checkbox'	=> array('name'=>t('多选'),'type'=>'varchar', 'length'=>'255'),
			'select'	=> array('name'=>t('下拉选择'),'type'=>'varchar', 'length'=>'50'),
			'editor'	=> array('name'=>t('编辑器'),'type'=>'text'),
			'email'		=> array('name'=>t('电子邮件'),'type'=>'varchar', 'length'=>'100'),
			'url'		=> array('name'=>t('网址'),'type'=>'varchar', 'length'=>'100'),
			'image'		=> array('name'=>t('图片'),'type'=>'varchar', 'length'=>'100'),
			'file'		=> array('name'=>t('文件'),'type'=>'varchar', 'length'=>'100'),	
			'date'		=> array('name'=>t('日期'),'type'=>'int', 'length'=>'10'),
			'datetime'	=> array('name'=>t('日期 + 时间'),'type'=>'int', 'length'=>'10'),
        ));
	}

	/*
	 *  获取控件类型选项
	 */
	public function control_options()
	{
		$options = array();

		foreach( $this->controls as $key=>$val )
		{
			$options[$key] = $val['name'];
		}

		return $options;
	}

	

	/**
	 * 获取数据集，支持链式查询
	 *
	 * @return array
	 */
	public function select($formid)
	{
		$data = array();

		$rows = $this->db()->where('formid',$formid)->orderby('listorder','asc')->select();

		foreach( $rows as &$r )
		{
			$r['settings'] = $r['settings'] ? unserialize($r['settings']) : array();

			$data[$r['name']] = $r;
		}

		return $data;
	}

	/**
	 * 获取添加编辑时的表单字段并格式化
	 * 
	 * @param  int $formid 表单编号
	 * @param  array  $data  表单数据
	 * @return array 格式化的表单
	 */
	public function getfields($formid, $data=array())
	{
		
		$fields = array();

		if ( $_fields = $this->cache($formid) )
		{
			foreach( $_fields as $i=>$r )
			{
				if ( $r['disabled'] ) continue;

				$fields[$i]['label']	= $r['label'];
				$fields[$i]['for']		= $r['name'];
				$fields[$i]['required']	= $r['notnull'];
				$fields[$i]['tips']		= $r['tips'];
				$fields[$i]['post']		= $r['post'];

				$fields[$i]['field']['id']			= $r['name'];
				$fields[$i]['field']['name']		= $r['name'];
				$fields[$i]['field']['value']		= isset($data[$r['name']]) ? $data[$r['name']]  : $r['default'];
				$fields[$i]['field']['type']		= $r['control'];
				$fields[$i]['field']['required']	= $r['notnull'];

				if ( $r['unique'] )
				{
					$fields[$i]['field']['remote']	= U('form/data/check/'.$formid.'/'.$fields[$i]['field']['name'].'/'.$fields[$i]['field']['value']);
				}

				// 将settings中的属性合并到字段
				foreach( $r['settings'] as $key=>$val )
				{
					if ( $key[0] == '_' or $val == '' ) continue;

					$fields[$i]['field'][$key] = $val;					
				}				

				// 增加上传数据编号
				if ( in_array($r['control'], array('editor','image','file')) and $data['id'] )
				{
					$fields[$i]['field']['dataid']	= "form-{$formid}-{$data['id']}";
				}

			}
		}

		return $fields;
	}

	/**
	 * 字段显示
	 * 
	 * @param  mixed $val  字段值
	 * @param  array $field 字段配置
	 * @return string
	 */
	public function show($val, $field)
	{
		// 存储原始数据，可以用于hook
		$field['value'] = $val;

		switch ( $field['control'] )
		{
			case 'radio':
			case 'select':

				$field['settings']['options'] = form::options($field['settings']['options']);

				if ( is_array($field['settings']['options']) )
				{
					$val = $field['settings']['options'][$val];
				}

				break;			
			case 'checkbox':

				$vals = ( $val and str::is_serialized($val) ) ? unserialize($val) : array();

				$field['settings']['options'] = form::options($field['settings']['options']);

				if ( is_array($vals) and is_array($field['settings']['options']) )
				{
					
					$val = array();

					foreach ($vals as $v)
					{
						$val[] = $field['settings']['options'][$v];
					}

					$val = implode(',', $val);

				}

				break;
			case 'date' :
			case 'datetime' :

				$val = format::date($val, $field['settings']['format']);

				break;
			case 'image' :

				$val = $val ? '<img src="'.$val.'"/>' : '';

				break;
			case 'file' :

				$val = $val ? '<a href="'.$val.'" target="_blank" class="btn btn-icon-text btn-highlight btn-filedownload"/><i class="icon icon-download"></i><b>'.t('下载').'</b></a>' : '';
				
				break;
			case 'url' :
				$val = $val ? '<a href="'.$val.'" target="_blank"><i class="icon icon-url"></i> '.$val.'</a>' : '';
				break;

			case 'email' :
				$val = $val ? '<a href="mailto:'.$val.'" target="_blank"><i class="icon icon-mail"></i> '.$val.'</a>' : '';
				break;

			case 'textarea' :

				$val = format::textarea($val);
				
				break;
			case 'editor' :

				$val = '<div class="html">'.$val.'</div>';
				
				break;						
		}

		return zotop::filter('form.field.show', $val, $field);
	}	

	/**
	 * 获取字段数组
	 *
	 * @param  int $id 字段编号
	 * @return mixed
	 */
	public function get($id, $field='')
	{
		$data = $this->getbyid($id);

		if ( is_array($data)  )
		{
			$data['settings'] = $data['settings'] ? unserialize($data['settings']) : array();

			if ( $field )
			{
				return isset($data[$field]) ? $data[$field] : '';
			}

			return $data;
		}

		return array();
	}	


	/**
	 * 检查并完善添加编辑时传入的数据
	 * 
	 * @param  array $data 数据
	 * @return array
	 */
	private function checkdata($data)
	{
		if ( empty($data['name']) ) return $this->error(t('字段名不能为空'));
		if ( empty($data['label']) ) return $this->error(t('标签名不能为空'));
		if ( empty($data['formid']) ) return $this->error(t('表单编号不能为空'));
		if ( empty($data['type']) ) return $this->error(t('数据类型不能为空'));

		if ( in_array($data['name'], $this->system_fields) )
		{
			return $this->error(t('不能使用字段名 {1}，请重新输入', $data['name']));
		}

		if ( empty($data['length']) and in_array($data['type'], array('char','varchar','tinyint','smallint','mediumint','int')) )
		{
			return $this->error(t('数据字段长度不能为空'));
		}

		// 大数据字段不能在后台显示
		$data['list'] 	= in_array($data['type'], array('text','mediumtext')) ? 0 : $data['list'];

		// 大数据字段不能设为值唯一
		$data['unique'] = in_array($data['type'], array('text','mediumtext')) ? 0 : $data['unique'];
		
		// 大数据字段不能参与排序
		$data['order'] 	= in_array($data['type'], array('text','mediumtext')) ? '' : $data['order'];

		// 自动获取对应的数据表名称
		$data['table'] = empty($data['table']) ? m('form.form.get', $data['formid'], 'table') : empty($data['table']);

		return $data;
	}

	/**
	 * 从数据组中组合出数据表字段数组
	 *
	 * @param  array $data 原始数据
	 * @return array
	 */
	private function fielddata($data)
	{
		$field = array(
			'name'		=> $data['name'],
			'type'		=> $data['type'],
			'length'	=> $data['length'],
			'default'	=> $data['default'],
			'notnull'	=> $data['notnull'],
			'unsigned'	=> $data['unsigned'],
			'comment'	=> $data['label']
		);

		return $field;
	}	

	/**
	 * 添加
	 * 
	 * @param  array $data 数据
	 * @return mixed  操作结果或者字段编号
	 */
	public function add($data)
	{
		$data['listorder'] = $this->max('id') + 1;

		if ( $data = $this->checkdata($data) )
		{
			$table = $this->db->schema($data['table']);

			// 检查字段名称是否已经存在
			if ( $table->existsField($data['name']) )
			{
				return $this->error(t('字段名 %s 已经存在', $data['name']));
			}

			if ( $table->addField($data['name'], $this->fielddata($data)) and ( $id = $this->insert($data) ) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['table']}.fields", null);

				
				// 更新字段缓存
				$this->cache($data['formid'], true);

				return $id;
			}
		}

		return false;
	}

	/**
	 * 编辑
	 * 
	 * @param  array $data 数据
	 * @param  int $id   字段编号
	 * @return mixed  操作结果或者字段编号
	 */
	public function edit($data,$id)
	{

		if ( $data = $this->checkdata($data) )
		{
			$table = $this->db->schema($data['table']);

			$name = $data['_name'] ? $data['_name'] : $data['name']; //改变字段名称

			// 更名的时候检查字段名称是否已经存在
			if ( $name != $data['name'] and $table->existsField($data['name']) )
			{
				return $this->error(t('字段名 %s 已经存在', $data['name']));
			}

			// 更该字段
			if ( $table->changeField($name, $this->fielddata($data)) and $this->update($data,$id) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['table']}.fields",null);

				// 更新字段缓存
				$this->cache($data['formid'], true);				

				return $id;
			}
		}

		return false;
	}			

	/**
	 * 删除
	 * 
	 * @param  int $id   字段编号
	 * @return mixed  操作结果
	 */
	public function delete($id)
	{
		if ( $data = $this->get($id) )
		{
			if ( empty($data['table']) )
			{
				$data['table'] = m('form.form.get', $data['formid'], 'table');
			}

			if ( $this->db->schema($data['table'])->dropField($data['name']) and parent::delete($id) )
			{
				// 更新数据表字段缓存
				zotop::cache("{$data['table']}.fields",null);

				// 更新字段缓存
				$this->cache($data['formid'], true);				

				return true;
			}
		}
		return false;
	}

	/**
	 * 排序
	 *
	 */
	public function order($ids)
	{
		foreach( (array)$ids as $i=>$id )
		{
			$this->update(array('listorder'=>$i+1), $id);
		}

		// 获取formid
		$formid = $this->get($id, 'formid');

		// 更新字段缓存
		$this->cache($formid, true);		

		return true;
	}

	/**
	 * 根据ID设置状态
	 *
	 * @param string $id ID
	 * @return bool
	 */
	public function status($id)
	{
		
		$formid 	= $this->get($id, 'formid');
		$disabled 	= $this->get($id, 'disabled') ? 0 : 1;

		if ( $this->update(array('disabled'=>$disabled), $id) )
		{
			// 更新字段缓存
			$this->cache($formid, true);

			return true;
		}

		return false;
	}

	/**
	 * 缓存数据
	 *
	 * @param bool $refresh 是否强制刷新缓存
	 * @return bool
	 */
	public function cache($formid, $refresh=false)
	{
		$cache = zotop::cache("form.form.{$formid}");

		if ( $refresh or empty($cache) or !is_array($cache) )
		{
			$cache = $this->select($formid);

			zotop::cache("form.form.{$formid}", $cache, false);
		}

		return $cache;
	}		
}
?>