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
		
		$attrs['style']  = $attrs['style']."visibility:hidden;";
		
		// 获取上传参数
		$upload = arr::pull($attrs,
			array('app','field','select','maxfile','dataid','image_resize','image_width', 'image_height','image_quality','watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity'),
			array(
				'app'    => ZOTOP_APP,
				'field'  => $attrs['name'],
				'select' => intval($attrs['select'])
			)
		);
		
		// 获取textarea参数
		$textarea = arr::pull($attrs,array('name','value','id','style','rows','class','required','maxlength','minlength','partten','remote'));

		// 额外工具
		$tools = arr::pull($attrs,'tools',array());

		if ( $tools )
		{
			$attrs['plugins'][0] = $attrs['plugins'][0].' zotop_tools';

			foreach ($tools as $name=>$tool)
			{
				$tool['name'] = 'tool-'.$name;
				$tool['url']  = U($tool['url'], $upload);

				$attrs['tools'][] = $tool;

				$attrs['toolbar'] = $attrs['toolbar'].' '.$tool['name'];
			}		
		}



		// 为tinymce内部上传附加参数
		$attrs['images_upload_url'] = url::set_query_arg($upload, $attrs['images_upload_url']);		

		$html   = array();
		$html[] = '<div class="field-editor">';

		$html[] = form::field_textarea($textarea);
		$html[] = '</div>';

		$html[]	= html::import(A('tinymce.url').'/editor/tinymce.min.js');
		$html[]	= html::import(A('tinymce.url').'/editor/jquery.tinymce.min.js');			
		$html[]	= html::import(A('tinymce.url').'/global.js');
		$html[] = '<script type="text/javascript">';
		$html[] = '	$(function(){';
		$html[] = '		$("#'.$textarea['id'].'").editor('.json_encode($attrs).');';
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
			'image'	=> array('type'=>'image','text'=>t('图片'),'title'=>t('选择或上传图片'),'icon'=>'image','url'=>'system/upload/image'),
			'file'	=> array('type'=>'file','text'=>t('文件'),'title'=>t('选择或上传文件'),'icon'=>'fa fa-file','url'=>'system/upload/file'),
			'video'	=> array('type'=>'video','text'=>t('视频'),'title'=>t('选择或上传视频'),'icon'=>'fa fa-video','url'=>'system/upload/video'),
			'audio'	=> array('type'=>'audio','text'=>t('音频'),'title'=>t('选择或上传音频'),'icon'=>'fa fa-audio','url'=>'system/upload/audio'),
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
			'tools'             => array('image','file'),
			'toolbar'           => 'standard',
			'resize'            => true,
			'image_caption'     => true,
			'media_live_embeds' => true,
			'imagetools_proxy'  => U('tinymce/server/proxy'),
			'images_upload_url' => U('tinymce/server/uploadimage'),
			'content_css'       => ZOTOP_URL_THEMES .'/'. C('site.theme') . '/css/editor.css',
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
			$attrs['plugins'] = array('advlist autolink lists link image zotop_media table paste textcolor colorpicker textpattern onekeyclear localautosave tabfocus');
			$attrs['toolbar'] = 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
			$attrs['rows']    = $attrs['rows'] ? $attrs['rows'] : 8;
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
			$attrs['plugins'] = array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen zotop_media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample');
			$attrs['toolbar'] = 'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image';
			$attrs['rows']    = $attrs['rows'] ? $attrs['rows'] : 12;
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
			$attrs['plugins'] = array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen zotop_media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample zotop_pagebreak wordcount');
			$attrs['toolbar'] = 'undo redo copy paste pastetext searchreplace removeformat onekeyclear | forecolor backcolor | bold italic underline strikethrough | subscript superscript | formatselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link unlink | image media | insertdatetime table anchor charmap emoticons blockquote hr | visualchars nonbreaking codesample template pagebreak | localautosave preview code fullscreen';
			$attrs['rows']    = $attrs['rows'] ? $attrs['rows'] : 18;
		}

		return $attrs;
	}	
}
?>