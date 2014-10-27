<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * form_model
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class form_model_data extends model
{
	protected $pk 		= 'id';
	protected $table 	= '';
	protected $formid	= '';


	/**
	 * 初始化对应的data类
	 * 
	 * @param  string $table 数据表名称，不含前缀
	 * @return $object 返回类对象
	 */
	public function table($table)
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * 初始化对应的data类
	 * 
	 * @param  int $id 表单编号
	 * @return $object 返回类对象
	 */
	public function init($formid)
	{
		$this->formid 	= $formid;
		$this->table 	= m('form.form.get',$formid,'table');

		return $this;
	}

	/**
	 * 获取数据
	 * 
	 * @param  int $id    数据编号
	 * @param  string $field 字段名称
	 * @return mixed
	 */
	public function get($id, $field='')
	{
		static $data = array();

		if ( !isset($data[$id]) )
		{
			$row = $this->where('id', '=', $id)->getRow();

			if ( $row )
			{
				foreach ($row as $key => &$val)
				{
					if ( str::is_serialized($val) ) $val = unserialize($val);
				}

				$row['dataid'] = "form-{$this->formid}-{$id}";

				$data[$id] = $row;				
			}
		}

		return empty($field) ? $data[$id] : $data[$id][$field];		
	}

	/**
	 * 添加数据
	 * 
	 * @param array $data 数据数组
	 * @return mixed 操作结果
	 */
	public function add($data)
	{
		$data = $this->_dealdata($data);

		if ( $data and is_array($data)  )
		{
			if ( $id = $this->insert($data) )
			{
				// 保存关联附件
            	m('system.attachment')->setRelated("form-{$this->formid}-{$id}");

            	return $id;
			}
		}

		return false;
	}

	/**
	 * 编辑数据
	 * 
	 * @param array $data 数据数组
 	 * @param  int $id 数据编号
	 * @return mixed 操作结果
	 */
	public function edit($data,$id)
	{
		$data = $this->_dealdata($data);

		if ( $data and is_array($data)  )
		{
			if ( $this->update($data, $id) )
			{
				// 保存关联附件
            	m('system.attachment')->setRelated("form-{$this->formid}-{$id}");

            	return $id;
			}
		}

		return false;
	}


	/**
	 * 根据编号删除文件
	 * @param  mixed $id 数据编号或者数据编号数组
	 * @return bool    操作结果
	 */
	public function delete($id)
	{
		if ( is_array( $id ) )
		{
			return array_map( array($this,'delete'), $id );
		}

		if ( $id and parent::delete($id) )
		{
			// 删除关联附件
			m('system.attachment')->delRelated("form-{$this->formid}-{$id}");

			return true;
		}

		return false;
	}


	/**
	 * 添加删除时候预处理数据
	 * 
	 * @param  array $data 待处理的数据
	 * @return mixed
	 */
	private function _dealdata($data)
	{
		$fields = m('form.field.cache',$this->formid);

		foreach ($data as $key => &$value)
		{
			if ( false === $value = $this->_formatdata($value, $fields[$key]) )
			{
				return false;
			}
		}

		return $data;
	}

	private function _formatdata($value, $field)
	{
		if ( $field['notnull'] and is_null($value) ) return $this->error(t('{1}不能为空', $field['label']));

		if ( $field['settings']['maxlength']  and strlen($value) > $field['settings']['maxlength'] ) return $this->error(t('{1}最大长度为{2}', $field['label'],$field['settings']['maxlength']));
		if ( $field['settings']['minlength']  and strlen($value) < $field['settings']['minlength'] ) return $this->error(t('{1}最小长度为{2}', $field['label'],$field['settings']['minlength']));

		if ( $field['settings']['max']  and intval($value) > $field['settings']['max'] ) return $this->error(t('{1}最大值为{2}', $field['label'],$field['settings']['max']));
		if ( $field['settings']['min']  and intval($value) < $field['settings']['min'] ) return $this->error(t('{1}最小值为{2}', $field['label'],$field['settings']['min']));




		
		switch ($field['control'])
		{
			case 'date':
			case 'datetime':
				
				$value = empty($value) ? ZOTOP_TIME : strtotime($value);
				break;
			
			default:
				# code...
				break;
		}

		return $value;
	}
}
?>