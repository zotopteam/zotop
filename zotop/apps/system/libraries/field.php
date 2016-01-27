<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * field
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */
class system_field
{
	/**
	 * 别名输入框
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function alias($attrs)
	{
		
		$attrs['maxlength']        = intval($attrs['maxlength']);
		$attrs['maxlength']        = ( $attrs['maxlength'] < 1 or $attrs['maxlength'] > 128 ) ? 128 : $attrs['maxlength'];
		$attrs['pattern']          = '^[a-z0-9-_]+$';
		$attrs['data-msg-pattern'] = t('只能包含小写字母、数字、下划线和中划线');
		$attrs['remote']           = u('system/alias/check','ignore='.$attrs['value']);
		
		$html['group-start']       = '<div class="input-group">';
		$html['field']             = form::field_text($attrs);
		
		if( $attrs['data-source'] )
		{
			$html['js']        = html::import(A('system.url').'/assets/js/field.alias.js');			
			$html['btn-start'] = '<span class="input-group-btn">';			
			$html['getalias']  = '<a href="'.u('system/alias/get').'" tabindex="-1" class="btn btn-default btn-icon-text getalias" data-source="'.$attrs['data-source'].'" data-to="'.$attrs['name'].'" title="'.t('获取别名').'"><i class="fa fa-refresh"></i> <b>'.t('获取').'</b></a>';
			$html['btn-end']   = '</span>';
		}
		
		$html['group-end'] = '</div>';		
		$html['error']     = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';
		$html['tips']      = form::tips(t('别名是在URL中使用的别称，它可以令URL更美观，只能包含小写字母、数字、下划线和中划线'));

		// hook
		$html = zotop::filter('system.field.alias', $html, $attrs, $options);

		return implode("\n",$html);
	}

	/**
	 * 关键词输入框
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function keywords($attrs)
	{
		
		$html['group-start'] = '<div class="input-group">';
		$html['field']       = form::field_text($attrs);

		if( $attrs['data-source'] )
		{
			list($source_title, $source_content) = explode(',', $attrs['data-source']);

			$html['btn-start'] = '<span class="input-group-btn">';
			$html['js']        = html::import(A('system.url').'/assets/js/field.keywords.js');
			$html['get']       = '<a href="'.u('system/keywords/get').'" tabindex="-1" class="btn btn-default btn-icon-text getkeywords" data-source-title="'.$source_title.'" data-source-content="'.$source_content.'"  data-to="'.$attrs['name'].'"><i class="fa fa-refresh"></i> <b>'.t('提取').'</b></a>';
			$html['btn-end']   = '</span>';
		}

		$html['group-end'] = '</div>';		
		$html['error']     = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';
		$html['tips']      = form::tips(t('合理填写有助于搜索引擎收录，多个关键词之间用“，”隔开'));

		// hook
		$html = zotop::filter('system.field.keywords', $html, $attrs, $options);

		return implode("\n",$html);
	}

	/**
	 * 单个图片上传控件输入框
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function image($attrs)
	{
		$attrs['placeholder'] = empty($options['placeholder']) ? t('请输入图片地址或者上传图片') : $options['placeholder'];
		$attrs['extension']   = 'jpg|jpeg|gif|png|bmp';

		//上传参数
		$upload = array('app'=>ZOTOP_APP,'field'=>$attrs['name'],'select'=>1);

		foreach( array('dataid','field','app','maxfile','image_resize','image_width', 'image_height','image_quality','watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity') as $attr )
		{
			if ( isset($attrs[$attr]) )
			{
				$upload[$attr] = $attrs[$attr];unset($attrs[$attr]);
			}
		}

		$html['js']       = html::import(A('system.url').'/assets/js/field.image.js');
		$html[]           = '<div class="input-group">';
		$html[]           = form::field_text($attrs);
		$html[]           = '<span class="input-group-btn">';
		$html['uploader'] = '<a href="'.u('system/upload/image', $upload).'" tabindex="-1" class="btn btn-default"><i class="fa fa-image"></i> <b>'.t('上传').'</b></a>';
		$html[]			  = '<button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>';
		$html[]			  = '<ul class="dropdown-menu dropdown-menu-right">';
		$html['selector'] = '	<li><a href="'.u('system/upload/library/image', $upload).'" tabindex="-1"><i class="fa fa-server fa-fw"></i>'.t('从库中选择').'</a></li>';
		$html['dirview']  = '	<li><a href="'.u('system/upload/dirview/image', $upload).'" tabindex="-1"><i class="fa fa-folder fa-fw"></i>'.t('从目录中选择').'</a></li>';
		$html[]           = '</ul>';
		$html[]           = '</span>';
		$html[]           = '</div>';
		$html['error']    = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

		// hook
		$html = zotop::filter('system.field.image', $html, $attrs, $upload);

		return implode("\n",$html);
	}

	/**
	 * 多图控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function images($attrs)
	{
		$attrs['id'] = $attrs['id'] ? $attrs['id'] : $attrs['name'];

		//上传参数
		$upload = array('app'=>ZOTOP_APP,'field'=>$attrs['name'],'select'=>0);

		foreach( array('dataid','field','app','maxfile','image_resize','image_width', 'image_height','image_quality','watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity') as $attr )
		{
			if ( isset($attrs[$attr]) )
			{
				$upload[$attr] = $attrs[$attr];unset($attrs[$attr]);
			}
		}

		$html[]	= html::import(A('system.url').'/assets/plupload/plupload.full.js');
		$html[]	= html::import(A('system.url').'/assets/plupload/i18n/zh_cn.js');
		$html[]	= html::import(A('system.url').'/assets/plupload/jquery.upload.js');

		$html[]	= html::import(A('system.url').'/assets/js/field.images.js');
		$html[]	= html::import(A('system.url').'/assets/css/field.images.css');

		$html[] = '	<div class="field-images" id="field-'.$attrs['id'].'">';

		$html[] = '<div class="row">';
		$html[] = '	<div class="col-xs-8">';
		$html[] = '		<div class="btn-toolbar">';
		$html[] = '			<div class="btn-group">';
		$html[] = '				<a class="btn btn-default btn-icon-text upload" id="'.$attrs['id'].'-upload" href="'.U('system/upload/uploadprocess', $upload).'"><i class="fa fa-upload"></i><b>'.t('批量上传').'</b></a>';
		$html[] = '			</div>';
		$html[] = '			<div class="btn-group">';
		$html[] = '				<a class="btn btn-default btn-icon-text select" href="'.U('system/upload/image', $upload).'"><i class="fa fa-image"></i><b>'.t('已上传').'</b></a>';
		$html[] = '				<a class="btn btn-default btn-icon-text select" href="'.U('system/upload/library/image', $upload).'"><i class="fa fa-server"></i><b>'.t('图像库').'</b></a>';
		$html[] = '				<a class="btn btn-default btn-icon-text select" href="'.U('system/upload/dirview/image', $upload).'"><i class="fa fa-folder"></i><b>'.t('目录选择').'</b></a>';
		$html[] = '			</div>';
		$html[] = '			<div class="btn-group">';
		$html[] = '				<a class="btn btn-default btn-icon-text description" href="javasacript:;"><i class="fa fa-info"></i><b>'.t('批量描述').'</b></a>';
		$html[] = '			</div>';
		$html[] = '		</div>';
		$html[] = '	</div>';
		$html[] = '	<div class="col-xs-4">';
		$html[] = '		<div id="'.$attrs['id'].'-upload-progress" class="field-images-progress progress"><div class="progress-bar"></div></div>';
		$html[] = '	</div>';
		$html[] = '</div>';

		$html[] = '	<div class="field-images-list" id="'.$attrs['id'].'-upload-dragdrop">';
		$html[] = '	</div>';		
		$html[] = '</div>';

		$html[] = '<script type="text/javascript">';
		$html[] = '	$(function(){';
		$html[] = '		$(\'#field-'.$attrs['id'].'\').imagesfield("'.$attrs['name'].'",'.json_encode($attrs['value']).','.json_encode($upload).');';
		$html[] = '	});';
		$html[] = '</script>';
		$html[] = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

		return implode("\n",$html);
	}	


	/**
	 * 单个文件上传控件输入框
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function file($attrs)
	{
		$attrs['placeholder'] = empty($options['placeholder']) ? t('请输入文件地址或者上传文件') : $options['placeholder'];

		//上传参数
		$upload = array('app'=>ZOTOP_APP,'field'=>$attrs['name'],'select'=>1);

		foreach( array('dataid','field','app','maxfile') as $attr )
		{
			if ( isset($attrs[$attr]) )
			{
				$upload[$attr] = $attrs[$attr];unset($attrs[$attr]);
			}
		}

		$html['js']			= html::import(A('system.url').'/assets/js/field.file.js');
		$html[]           = '<div class="input-group">';
		$html['field']		= form::field_text($attrs);
		$html[]           = '<span class="input-group-btn">';
		$html['uploader'] = '<a href="'.u('system/upload/file', $upload).'" tabindex="-1" class="btn btn-default"><i class="fa fa-upload"></i> <b>'.t('上传').'</b></a>';
		$html[]			  = '<button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>';
		$html[]			  = '<ul class="dropdown-menu dropdown-menu-right">';
		$html['selector'] = '	<li><a href="'.u('system/upload/library/file', $upload).'" tabindex="-1"><i class="fa fa-server fa-fw"></i>'.t('从库中选择').'</a></li>';
		$html['dirview']  = '	<li><a href="'.u('system/upload/dirview/file', $upload).'" tabindex="-1"><i class="fa fa-folder fa-fw"></i>'.t('从目录中选择').'</a></li>';
		$html[]           = '</ul>';
		$html[]           = '</span>';
		$html[]           = '</div>';


		$html['error']		= '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

		// hook
		$html = zotop::filter('system.field.file', $html, $attrs, $upload);

		return implode("\n",$html);
	}

	/**
	 * 日期控件，不含时间，如：2013-04-19
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function date($attrs)
	{
		// 属性设置
		$attrs['format']     = empty($attrs['format']) ? 'Y-m-d' : $attrs['format'];
		$attrs['timepicker'] = isset($attrs['timepicker']) ? $attrs['timepicker'] : false;
		$attrs['lang']       = empty($attrs['lang']) ? 'ch' : $attrs['lang'];
		$attrs['value']      = empty($attrs['value']) ? ZOTOP_TIME : $attrs['value'];
		$attrs['value']      = format::date($attrs['value'], $attrs['format']);

		// 参数设置
		$options = array();

		foreach( array('min','max','start','format','double','inline','datepicker','timepicker','lang') as $attr )
		{
			if ( isset($attrs[$attr]) )
			{
				$options[$attr] = ( in_array($attr, array('min','max','start')) and is_int($attrs[$attr]) ) ? format::date($attrs[$attr], $attrs['format']) : $attrs[$attr];
				unset($attrs[$attr]);
			}
		}


		if ( !empty($options['min']) and strtotime($options['min']) )  $settings['minDate'] = $options['min'];
		if ( !empty($options['max']) and strtotime($options['max']) )  $settings['maxDate'] = $options['max'];
		if ( !empty($options['start']) and strtotime($options['start']) )  $settings['startDate'] = $options['start'];

		if ( $options['inline'] )
		{
			$html[] = '<div class="form-control-static">';
			$html[]	= form::field_text($attrs);
			$html[] = '</div>';
		}
		else
		{
			$html[]	= '<div class="input-group">';
			$html[]	= form::field_text($attrs);
			$html[]	= '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>';
			$html[]	= '</div>';
		}		

		$html[]	= html::import(A('system.url').'/assets/datepicker/jquery.datetimepicker.js');
		$html[]	= html::import(A('system.url').'/assets/datepicker/jquery.datetimepicker.css');
		$html[]	= '<script>$(function(){$("#'.$attrs['id'].'").datetimepicker('.json_encode($options).');})</script>';
		$html[]	= '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

		return implode("\n",$html);
	}

	/**
	 * 日期控件，带时间，如2013-04-19 13:52
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function datetime($attrs)
	{
		$attrs['format']     = 'Y-m-d H:i';
		$attrs['timepicker'] = true;
		return self::date($attrs);
	}

	/**
	 * 验证码控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function captcha($attrs)
	{
		$attrs['remote'] = $attrs['remote'] ? $attrs['remote'] : u('system/captcha/check',array('name'=>$attrs['name']));
		$attrs['alt']    = isset($attrs['alt']) ? $attrs['alt'] : t('看不清？换一个');

		// 验证码参数
		$options = array('length'=>4,'rand'=>ZOTOP_TIME);

		foreach( array('length','width','height','font_size','font_color','background') as $attr )
		{
			if ( isset($attrs[$attr]) and !empty($attrs[$attr]) )
			{
				$options[$attr] = $attrs[$attr];unset($attrs[$attr]);
			}
		}

		$attrs['maxlength'] = $options['length'];

		$html[] = '<span class="form-captcha">';
		$html[] = form::field_text($attrs);
		$html[] = '<a href="javascript:void(0);" tabindex="-1" class="captcha" onclick="this.children[0].src=\''.u('system/captcha',$options).'&time=\'+Math.random()" title="'.t('点击更换').'" data-placement="right">';
		$html[] = '	<img src="'.u('system/captcha',$options).'"/>';
		$html[] = '	<span class="hidden-sm">'.$attrs['alt'].'</span>';
		$html[] = '</a>';
		$html[] = '</span>';
		$html[] = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

		return implode("\n",$html);
	}

	/**
	 * 模版选择器
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function template($attrs)
	{
		$id = $attrs['id'] ? $attrs['id'] : $attrs['name'];
		$id = str_replace(array(']',' ','/','\\'), '', $id);
		$id = str_replace(array('.','['), '-', $id);

		$attrs['id']          = $id;
		$attrs['placeholder'] = empty($options['placeholder']) ? t('请输入模版地址或者选择模版') : $options['placeholder'];
		$attrs['extension']   = 'php|tpl|htm|html';
		
		//参数设置
		$options          = is_array($attrs['options']) ? $attrs['options'] : array();
		$options['field'] = $attrs['name'];

		unset($attrs['options']);

		$html['js']       = html::import(A('system.url').'/assets/js/field.template.js');		
		$html[]           = '<div class="input-group">';
		$html['field']    = form::field($attrs);
		$html[]           = '<span class="input-group-btn">';		
		$html['selector'] = '<a href="'.u('system/theme/template/select').'" id="'.$id.'-selector" tabindex="-1"  class="btn btn-default btn-icon-text btn-select" title="'.t('选择模版').'"><i class="fa fa-file-text"></i> <b>'.t('选择').'</b></a>';
		$html['editor']   = '<a href="'.u('system/theme/template_edit').'" id="'.$id.'-editor" tabindex="-1" class="btn btn-default btn-icon btn-edit" title="'.t('编辑模版').'"><i class="fa fa-edit"></i></a>';
		$html[]           = '</span>';
		$html[]           = '</div>';		
		$html['error']    = '<label for="'.$id.'" generated="true" class="error"></label>';

		// hook
		$html = zotop::filter('system.field.template', $html, $attrs, $options);

		return implode("\n",$html);
	}

	/**
	 * 模版编辑器
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function template_editor($attrs)
	{
		$id = $attrs['name'] == $attrs['id'] ? $attrs['name'] : $attrs['name'].'_'.$attrs['id'];
		$id = str_replace(array(']',' ','/','\\'), '', $id);
		$id = str_replace(array('.','['), '_', $id);

		$attrs['id'] = $id;

		if ( !defined('FIELD_TEMPLATE_EDITOR_INIT') )
		{
			define('FIELD_TEMPLATE_EDITOR_INIT', true);

			$html['codemirror-css'] = '<link rel="stylesheet" href="'.A('system.url').'/assets/codemirror/codemirror.css">';
			$html['codemirror-zotop'] = '<link rel="stylesheet" href="'.A('system.url').'/assets/codemirror/zotop.css">';
			$html['codemirror-js'] = '<script type="text/javascript" src="'.A('system.url').'/assets/codemirror/codemirror-compressed.js"></script>';
			$html['codemirror-editor'] = '<script type="text/javascript" src="'.A('system.url').'/assets/codemirror/field.template_editor.js"></script>';
		}

		$html['start']		= '<div class="template-editor">';
		$html['head']		= '<div class="template-editor-head"></div>';
		$html['body']		= '<div class="template-editor-body">'.form::field_textarea($attrs).'</div>';
		$html['end']		= '</div>';
		$html['error']		= '<label for="'.$id.'" generated="true" class="error"></label>';

		// hook
		$html = zotop::filter('system.field.template_editor', $html, $attrs, $options);

		return implode("\n",$html);
	}

	/**
	 * 图标选择器
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function icon($attrs)
	{
		$id = $attrs['name'] == $attrs['id'] ? $attrs['name'] : $attrs['name'].'_'.$attrs['id'];
		$id = str_replace(array(']',' ','/','\\'), '', $id);
		$id = str_replace(array('.','['), '_', $id);

		$attrs['id']                         = $id;
		$attrs['data-iconset']               = 'fontawesome';
		$attrs['data-arrow-prev-icon-class'] = 'fa fa-arrow-left';
		$attrs['data-arrow-next-icon-class'] = 'fa fa-arrow-right';
		$attrs['data-icon']                  = $attrs['value'] ? $attrs['value'] : 'fa-file';
		$attrs['data-rows']                  = $attrs['rows'] ? $attrs['rows'] : 4;
		$attrs['data-cols']                  = $attrs['cols'] ? $attrs['cols'] : 12;
		$attrs['role']                       = 'iconpicker';
		
		$html['field'] = form::field_button($attrs);
		$html['js']    = html::import(A('system.url').'/assets/js/bootstrap-iconpicker.min.js');		
		$html['css']   = html::import(A('system.url').'/assets/css/bootstrap-iconpicker.min.css');	
		

		return implode("\n",$html);
	}	
}
?>