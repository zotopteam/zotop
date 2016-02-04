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
class tinymce_field
{
	/**
	 * Ueditor编辑器
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function editor($attrs)
	{
		// 参数HOOK
		$attrs           = zotop::filter('tinymce.attrs',$attrs);
		
		// 参数完善
		$attrs['app']    = empty($attrs['app']) ? ZOTOP_APP : $attrs['app'];
		$attrs['field']  = empty($attrs['field']) ? $attrs['name'] : $attrs['field'];
		$attrs['select'] = intval($attrs['select']);
		$attrs['style']  = $attrs['style']."visibility:hidden;";
		
		// 获取上传参数
		$upload          = arr::take($attrs,'app','field','select','maxfile','dataid','image_resize','image_width', 'image_height','image_quality','watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity');
		
		// 获取textarea参数
		$textarea        = arr::take($attrs,'name','value','id','style','rows','class','required','maxlength','minlength','partten','remote');
		
		// tinymce 参数
		// $tinymce         = arr::take($attrs,'tools','js');

		$html   = array();
		$html[] = '<div class="field-editor">';

		// 开启额外工具条
		if ( isset($attrs['tools']) and is_array($attrs['tools']) )
		{
			$html[] = '<div class="btn-toolbar btn-toolbar-top btn-toolbar-editor">';
			foreach( $attrs['tools'] as $k=>$t )
			{
				$html[] = '<a href="'.U($t['url'], $upload).'" tabindex="-1" class="btn btn-default editor-insert" data-field="'.$textarea['name'].'" data-type="'.$t['type'].'" title="'.t('插入%s',$t['text']).'"><i class="'.$t['icon'].' fa-fw"></i><b>'.$t['text'].'</b></a>';
			}
			$html[] = '</div>';

			unset($attrs['tools']);
		}


		$html[] = form::field_textarea($textarea);
		$html[] = '</div>';

		$html[]	= html::import(A('tinymce.url').'/editor/tinymce.jquery.min.js');
		$html[]	= html::import(A('tinymce.url').'/editor/jquery.tinymce.min.js');			
		$html[]	= html::import(A('tinymce.url').'/global.js');
		$html[] = '<script type="text/javascript">';
		$html[] = '	$(function(){';
		$html[] = '		$(\'textarea[name='.$textarea['name'].']\').editor('.json_encode($attrs).');';
		$html[] = '	});';
		$html[] = '</script>';
		$html[] = '<label for="'.$textarea['id'].'" generated="true" class="error"></label>';


		return implode("\n",$html);
	}


	/**
	 * 编辑器默认类型
	 * 
	 * @return array
	 */
	public static function mode($mode='')
	{
		$modes = zotop::filter('editor.mode',array(
			'full'     => t('全能'),
			'standard' => t('标准'),
			'basic'    => t('基本')
		));

		if ( empty($mode) )
		{
			return $modes;
		}

		return isset($modes[$mode]) ? $modes[$mode] : '';
	}

	/**
	 * 编辑器额外工具
	 * 
	 * @return array
	 */
	public static function tools($attrs)
	{
		// 额外工具条通过 editor.tools 扩展，可以在其它编辑器共用
		$tools = zotop::filter('editor.tools',array(
			'image'	=> array('type'=>'image','text'=>t('图片'),'icon'=>'fa fa-image','url'=>'system/upload/image'),
			'file'	=> array('type'=>'file','text'=>t('文件'),'icon'=>'fa fa-file','url'=>'system/upload/file'),
			'video'	=> array('type'=>'video','text'=>t('视频'),'icon'=>'fa fa-video','url'=>'system/upload/video'),
			'audio'	=> array('type'=>'audio','text'=>t('音频'),'icon'=>'fa fa-audio','url'=>'system/upload/audio'),
		));

		// 只显示传入的部分
		if ( $attrs['tools'] )
		{
			$attrs['tools'] = is_string($attrs['tools']) ? explode(',', $attrs['tools']) : $attrs['tools'];
			
			$active_tools   = array();

			foreach( $attrs['tools'] as $k )
			{
				if (isset($tools[$k])) $active_tools[$k] = $tools[$k];
			}

			$attrs['tools'] = $active_tools;
		}

		return $attrs;		
	}

	/**
	 * 编辑器默认选项
	 * 
	 * @return array
	 */
	public static function init($attrs)
	{
		$default = zotop::filter('tinymce.default', array(
			'tools'             => false,
			'toolbar'           => 'standard',
			'resize'            => true,				
			'imagetools_proxy'  => U('tinymce/server/proxy'),
			'images_upload_url' => U('tinymce/server/uploadimage',array('HTTP_X_REQUESTED_WITH'=>true)),
			'content_css'       => ZOTOP_URL_THEMES .'/'. C('site.theme') . '/css/editor.css',
			'rows'				=> 20,
		));

		return array_merge($default, $attrs);
	}

	/**
	 * 编辑器参数
	 * 
	 * @param  array $options 编辑器参数
	 * @return array
	 */
	public static function basic($attrs)
	{
		if ( $attrs['toolbar'] == 'basic' )
		{
			$attrs['plugins'] = array('advlist autolink lists link image media table paste textcolor colorpicker textpattern onekeyclear localautosave tabfocus');
			$attrs['toolbar'] = 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
			$attrs['rows']    = 8;
		}

		return $attrs;
	}

	/**
	 * 编辑器参数
	 * 
	 * @param  array $options 编辑器参数
	 * @return array
	 */
	public static function standard($attrs)
	{		
		if ( $attrs['toolbar'] == 'standard' )
		{
			$attrs['plugins'] = array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave wordcount tabfocus codesample powerpaste');
			$attrs['toolbar'] = 'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image codesample';
			$attrs['rows']    = 12;
		}

		return $attrs;
	}

	/**
	 * 编辑器参数
	 * 
	 * @param  array $options 编辑器参数
	 * @return array
	 */
	public static function full($attrs)
	{
		if ( $attrs['toolbar'] == 'full' )
		{
			$attrs['plugins'] = array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample powerpaste zotop_pagebreak');
			$attrs['toolbar'] = 'undo redo copy paste pastetext searchreplace removeformat onekeyclear | forecolor backcolor | bold italic underline strikethrough | subscript superscript | formatselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link unlink | image media | insertdatetime table anchor charmap emoticons blockquote hr | visualchars nonbreaking codesample template pagebreak | localautosave preview code fullscreen';
			$attrs['rows']    = 20;
		}

		return $attrs;
	}	
}
?>