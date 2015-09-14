<?php defined('ZOTOP') or die('No direct script access.');
/**
 * zotop core
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class form
{
    /**
     * 创建标签
     *
     * @param array|string $attrs 标签名称
	 * @param string $value 标签值
     * @return void
     */
	public static function attributes($key, $value = null)
	{
		$str = '';

		if ( empty($key) ) return '';

		if ( is_string($key) )
		{
			if ( in_array($key, array('checked','selected','readonly','disabled','required')) )
			{
				return $value ? ' '.$key : '';
			}

			if( $value === null )
			{
				return '';
			}
			elseif ( is_string($value) )
			{
				return ' '.$key.'="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'"';
			}
			elseif( is_numeric($value) )
			{
				return ' '.$key.'="'.$value.'"';
			}
			elseif( is_bool($value)  )
			{
				return ' '.$key.'="'.($value ? 'true' : 'false').'"';
			}
		}

		if ( is_array($key) )
		{
			foreach ( $key as $k=>$v )
			{
				$str .= form::attributes($k, $v);
			}
		}

		return $str;
	}

	/**
	 * 将字符串转化成标准的选项数组
	 *
	 * @param  string $options 选项字符串
	 * @param  string $s1  第一分割符号
	 * @param  string $s2  第二分割符号,分隔符后面的是数组键值
	 * @return array
	 */
	public static function options($options, $s1 = "\n", $s2 = '|')
	{
		if( is_array($options) ) return $options;

		$os = array();

		$options = explode($s1, $options);

		foreach($options as $option)
		{
			if(strpos($option, $s2))
			{
				list($name, $value) = explode($s2, trim($option));
			}
			else
			{
				$name = $value = trim($option);
			}

			$os[$value] = $name;
		}

		return $os;
	}

    /**
     * 输出表单头部
     *
     * @param array|string $form 表单参数
     * @return void
     */
    public static function header($form=array())
    {
		if ( is_string($form) )
		{
			$form = array('action'=>$form);
		}
		$form = $form + array('class'=>'form','method'=>'post','action'=>request::url());

		return '<form'.form::attributes($form).' novalidate>'."\n";
    }

    /**
     * 输出表单尾部
     *
     * @return void
     */
    public static function footer()
    {
		return '</form>'."\n";
    }

    /**
     * 输出字段标签
     *
     * @return void
     */
    public static function label($text, $for, $required=false)
    {
		return '<label class="form-label'.($required ? ' required' : '').'" for="'.$for.'">'.$text.($required ? '<b class="required">*</b>' : '').'</label>';
    }

    /**
     * 输出字段提示信息
     *
     */
	public static function tips($tips)
	{
		return empty($tips) ? '' : '<span class="help-block">'.$tips.'</span>';
	}

    /**
     * 设置或者生成一个字段
	 *
	 * @code
	 * 1, 设置一个新的表单控件，覆盖系统默认的表单控件
     *
     * function mytextarea($attrs)
     * {
     *   return field::textarea($attrs).'<div>new textarea</div>';
     * }
     * field::set('textarea','mytextarea');
     *
	 * 2,生成一个新的框架
	 *
	 * echo form::field(array('type'=>'textarea'));
	 *
	 * @endcode
     *
     * @param $name  string  控件名称
     * @param $callback function 控件函数
     * @return mixed
     */
    public static function field($field, $callback='')
    {
		static $fields = array();

		//设置控件
		if ( is_string($field) and !empty($callback) )
		{
			$field = strtolower($field);

			if ( $callback == '?' )
			{
				return ( !empty($fields[$field]) or method_exists('form',"field_{$field}") );
			}

			return ( $fields[$field] = $callback );
		}
		elseif ( is_array($field) )
		{
			// 默认设置控件编号为控件名称
			$field['id'] 	= empty($field['id']) ? $field['name'] : $field['id'];
			$field['id'] 	= str_replace(array('.',']',' ','/','\\'), '', str_replace('[', '-', $field['id']));

			// 如果未设置控件类型，则默认为text
			$field['type'] 	= empty($field['type']) ? 'text' : strtolower($field['type']);

			// 设置默认的class
			$field['class'] = empty($field['class']) ? str_replace(',',' ',$field['type']) : str_replace(',',' ',$field['type']).' '.$field['class'];

			// 处理required标签( validation 在ie下无法正常解析 required = "true" 或者 required = "required")
			if ( $field['required'] )
			{
				$field['class'] = $field['class'].' required';
			}

			//debug::dump($field);

			// 字段类型依次查找，用英文逗号分隔，如summary,textarea，如果找不到summary控件，则输出textarea控件
			$types = explode(',',$field['type']); unset($field['type']);

			foreach($types as $type)
			{
				// 调用设置的控件
				if ( $callback = $fields[$type] )
				{
					return call_user_func_array($callback, array($field));
				}

				// 调用系统默认的控件
				if ( $type = "field_{$type}" and method_exists('form',$type) )
				{
					return form::$type($field);
				}
			}

			return form::field_text($field);;
		}

		return false;
    }

	/**
     * text 文本控件
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
    public static function field_text($attrs)
	{
		$attrs['type']  = empty($attrs['type']) ? 'text' : $attrs['type'];
		$attrs['class'] = empty($attrs['class']) ? 'form-control' : 'form-control '.$attrs['class'];

		return '<input'.form::attributes($attrs).'/>';
	}


	/**
     * hidden 控件
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_hidden($attrs)
	{
		$attrs['type'] = 'hidden';

		return form::field_text($attrs);
	}

	/**
     * password 数字控件
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_password($attrs)
	{
		$attrs['type'] = 'password';

		return form::field_text($attrs);
	}


	/**
     * number 数字控件
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_number($attrs)
	{
		//$attrs['type'] = 'number';
		
		return form::field_text($attrs);
	}

	/**
     * upload file
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_upload($attrs)
	{
		$attrs['type'] = 'file';
		$attrs['name'] = empty($attrs['name']) ? 'filedata' : $attrs['name'];
		return form::field_text($attrs);
	}

    /**
     * 按钮控件
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_textarea($attrs)
	{
		if ( $value = $attrs['value'] )
		{
			unset($attrs['value']);
		}		
		
		$attrs['rows']  = is_int($attrs['rows']) ? $attrs['rows'] : 3;
		$attrs['class'] = empty($attrs['class']) ? 'form-control' : 'form-control '.$attrs['class'];

		return '<textarea'.form::attributes($attrs).'>'.htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8').'</textarea>';
	}

	/**
     * editor
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_editor($attrs)
	{
		return form::field_textarea($attrs);
	}

	/**
     * bool 布尔控件，TRUE或者FALSE
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_bool($attrs)
	{
		$attrs['type'] = 'radio';
		
		$attrs['options'] = array(
			1	=> $attrs['yes'] ? $attrs['yes'] : t('是'),
			0	=> $attrs['no'] ? $attrs['no'] : t('否')
		);

		unset($attrs['yes'], $attrs['no']);
		
		return form::field($attrs);
	}

    /**
     * 单选
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_radio($attrs)
	{
		@extract($attrs);

		// options必须是数组，将字符串options转化为数组
		$options = form::options($options);

		// 如果没有设置值，默认选择第一个作为值
		$value = isset($value) ? $value : reset(array_keys($options));

	    $html = array('');

		if ( is_array($options) )
	    {
			$n = count($options);

			$html[] = '<div class="form-radio'. ( $column > 0 ? ' form-radio-column' : '' ).'">';
			
			$html[] = '<div class="form-radio-row">';

			$i = 1;

			foreach($options as $val=>$text)
			{
				$checked = ( $val == $value ) ? ' checked="checked"' : '';

				$html[] = '<label>';
				$html[] = '	<input type="radio" name="'.$name.'" id="'.str_replace(']', '', str_replace('[', '-', $name)).'-'.$val.'" value="'.$val.'"'.$checked.'/>';
				$html[] = '	'.$text;
				$html[] = '</label>';

				if ( $column > 0 and $i%$column == 0 )
				{
					$html[] = '</div>';
					$html[] = '<div class="form-radio-row">';
				}

				$i++;
			}

			$html[] = '</div>';
			$html[] = '</div>';
			$html[] = '<label for="'.$name.'" class="error" generated="generated"></label>';// eroor container for validate
	    }

	    return implode("\n",$html);
	}

    /**
     * 多选
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_checkbox($attrs)
	{
		@extract($attrs);

		// options必须是数组，将字符串options转化为数组
		$options = form::options($options);

	    $html = array('');

		if(is_array($options))
	    {
			$n = count($options);

			$html[] = '<div class="form-checkbox'. ( $column > 0 ? ' form-checkbox-column' : '' ).'">';
			$html[] = '<div class="form-checkbox-row">';

			$i = 1;
			foreach($options as $val=>$text)
			{
				$checked = in_array($val,(array)$value) ? ' checked="checked"' : '';

				$html[] = '<label>';
				$html[] = '	<input type="checkbox" name="'.$name.'[]" id="'.$name.'-item'.$i.'" value="'.$val.'"'.$checked.'/>';
				$html[] = '	'.$text;
				$html[] = '</label>';

				if ( $column > 0 AND $i%$column == 0 )
				{
					$html[] = '</div>';
					$html[] = '<div class="form-checkbox-row">';
				}

				$i++;
			}

			$html[] = '</div>';
			$html[] = '</div>';
			$html[] = '<label for="'.$name.'" class="error" generated="generated"></label>';// eroor container for validate
	    }

	    return implode("\n",$html);
	}


	/**
	 * 生成一个标准的select控件
	 *
	 * @param $attrs
	 * @return string
	 */
	public static function field_select($attrs)
	{
		// options必须是数组，将字符串options转化为数组
		$options = form::options($attrs['options']);

	    // 提取value
		$value = $attrs['value'];

		//当value为数组时，则为多选
		if ( is_array($value) )
		{
			$attrs['multiple'] = 'multiple';
			$attrs['class'] = $attrs['class'].' multiple';
		}
		else
		{
			$value = array($value);
		}

		// 删除多余的标签
		unset($attrs['options'],$attrs['value']);

	    $html[] = $attrs['multiple'] ? '<div class="form-select form-select-multiple">' : '<div class="form-select form-select-single">';
	    $html[] = '<select'.form::attributes($attrs).'>';

	    if( is_array($options) )
	    {
	        foreach($options as $val=>$option)
	        {
				if ( is_array($option) )
				{
					$html[] = '<optgroup label="'.$val.'">';

					foreach( $option as $val=>$opt )
					{
						$selected = in_array($val,$value) ? ' selected="selected"' : '';
						$html[] = '	<option value="'.$val.'"'.$selected.'>'.$opt.'</option>';
					}

					$html[] = '</optgroup>';
				}
				else
				{
					$selected = in_array($val,$value) ? ' selected="selected"' : '';
					$html[] = '	<option value="'.$val.'"'.$selected.'>'.$option.'</option>';
				}
	        }
	    }
	    $html[] = '</select>';
	    $html[] = '</div>';
	    return implode("\n",$html);
	}


    /**
     * 按钮控件
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_button($attrs)
	{
		$attrs['type']         = 'button';
		$attrs['class']        = empty($attrs['class']) ? 'btn btn-default' : 'btn btn-default '.$attrs['class'];
		$attrs['autocomplete'] = 'off';		
		$value                 = $attrs['value'] ? $attrs['value'] : t('button');unset($attrs['value']);

		return '<button'.form::attributes($attrs).'>'.htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8').'</button>';
	}

    /**
     * 表单提交按钮
     *
     * @param $attrs array 控件参数
     * @return string 控件代码
     */
	public static function field_submit($attrs)
	{

		$attrs['type']              = 'submit';
		$attrs['class']             = empty($attrs['class']) ? 'btn btn-primary' : 'btn btn-primary '.$attrs['class'];
		$attrs['autocomplete']      = 'off';
		$attrs['data-loading-text'] = $attrs['data-loading-text'] ? $attrs['data-loading-text'] : t('提交中……');

		$attrs += array
		(
			'id'    => 'submitform',
			'value' => t('提交')
		);

		$value = $attrs['value'];unset($attrs['value']);

		return '<button'.form::attributes($attrs).'>'.htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8').'</button>';
	}

	/**
	 * 取消按钮，点击后返回前页
	 * 
	 * @param  [type] $attrs [description]
	 * @return [type]        [description]
	 */
	public static function field_cancel($attrs)
	{
		$attrs['onclick'] =  $attrs['onclick'] ? $attrs['onclick'] :'javascript:history.go(-1);';

		$attrs += array
		(
			'id'    => 'cancelform',
			'value' => t('取消')
		);		

		return form::field_button($attrs);
	}

	/**
	 * 时区选择器
	 *
	 * @param  string $attrs 选项字符串
	 * @return array
	 */
	public static function field_timezone($attrs)
	{
		$attrs['type']    = 'select';
		$attrs['options'] = include(ZOTOP_PATH_LIBRARIES.DS.'resources'.DS.'timezone.php');

		return form::field($attrs);
	}

	/**
	 * 语言选择
	 *
	 * @param  string $attrs 选项字符串
	 * @return array
	 */
	public static function field_language($attrs)
	{
		$attrs['type']    = 'select';
		$attrs['options'] = array('zh-cn'=>t('简体中文'));

		return form::field($attrs);
	}
}
?>