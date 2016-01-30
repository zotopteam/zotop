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
		$attrs = zotop::filter('editor.attrs',$attrs);	

			$attrs['id']    = empty($attrs['id']) ? $attrs['name'] : $attrs['id'];
			$attrs['rows']  = empty($attrs['rows']) ? self::mode($attrs['toolbar'],'rows') : $attrs['rows'];
			$attrs['rows']  = intval($attrs['rows']) ? intval($attrs['rows']) : 20;
			$attrs['style'] = $attrs['style']."visibility:hidden;";

			// 上传参数
			$upload = array('app'=>ZOTOP_APP,'field'=>$attrs['name'],'select'=>0);

			foreach( array('field','app','maxfile','dataid','image_resize','image_width', 'image_height','image_quality','watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity') as $attr )
			{
				if ( isset($attrs[$attr]) )
				{
					$upload[$attr] = $attrs[$attr];unset($attrs[$attr]);
				}
			}

			// 编辑器参数
			$options = self::options_default();

			foreach( array_keys($options) as $attr)
			{
				if ( isset($attrs[$attr]) and !empty($attrs[$attr]) )
				{
					$options[$attr] = $attrs[$attr];unset($attrs[$attr]);
				}
			}

			$options  = zotop::filter('editor.options', $options);

			// 开启额外工具条
			if ( $options['tools'] )
			{
				$tools = zotop::filter('editor.tools',array(
					'image'	=> array('type'=>'image','text'=>t('图片'),'icon'=>'fa fa-image','url'=>'system/upload/image'),
					'file'	=> array('type'=>'file','text'=>t('文件'),'icon'=>'fa fa-file','url'=>'system/upload/file'),
					'video'	=> array('type'=>'video','text'=>t('视频'),'icon'=>'fa fa-video','url'=>'system/upload/video'),
					'audio'	=> array('type'=>'audio','text'=>t('音频'),'icon'=>'fa fa-audio','url'=>'system/upload/audio'),
				));

				// 如果传入的是数组，则只显示传入部分
				if ( is_array($tools) and is_array($options['tools']) )
				{
					foreach( $tools as $k=>$v )
					{
						if ( !in_array($k, $options['tools']) ) unset($tools[$k]);
					}
				}

				// 生成工具条
				if ( is_array($tools) && !empty($tools) )
				{
					$html[] = '<div class="btn-toolbar btn-toolbar-top btn-toolbar-editor">';
					foreach( $tools as $k=>$t)
					{
						$html[] = '<a href="'.u($t['url'],$upload).'" tabindex="-1" class="btn btn-default editor-insert" data-field="'.$attrs['name'].'" data-type="'.$t['type'].'" title="'.t('插入%s',$t['text']).'"><i class="'.$t['icon'].' fa-fw"></i><b>'.$t['text'].'</b></a>';
					}
					$html[] = '</div>';
				}
			}

			$html[] = form::field_textarea($attrs);
			$html[]	= html::import(A('tinymce.url').'/editor/tinymce.jquery.min.js');
			$html[]	= html::import(A('tinymce.url').'/editor/jquery.tinymce.min.js');			
			$html[]	= html::import(A('tinymce.url').'/global.js');
			$html[] = '<script type="text/javascript">';
			$html[] = '	$(function(){';
			$html[] = '		$(\'textarea[name='.$attrs['name'].']\').editor('.json_encode($options).');';
			$html[] = '	});';
			$html[] = '</script>';
			$html[] = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

			unset($attrs['upload'],$attrs['options']);

			return implode("\n",$html);
	}


	/**
	 * 编辑器默认类型
	 * 
	 * @return [type] [description]
	 */
	public static function mode($mode='', $key='')
	{
		$modes = zotop::filter('editor.mode',array(
			'full'     => array(
				'name'    => t('全能'),
				'rows'    => 20,
				'options' => array(
					'plugins' => array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample powerpaste zotop_pagebreak'),
					'toolbar' => 'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image codesample',
				)
			),
			'standard' => array(
				'name'    => t('标准'),
				'rows'    => 12,
				'options' => array(
					'plugins' => array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample powerpaste zotop_pagebreak'),
					'toolbar' => 'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image codesample',
				)
			),
			'basic'    => array(
				'name'    => t('基本'),
				'rows'    => 8,
				'options' => array(
					'plugins' => array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample powerpaste zotop_pagebreak'),
					'toolbar' => 'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image codesample',
				)
			)
		));

		if ( empty($mode) )
		{
			return $modes;
		}

		if ( empty($key) )
		{
			isset($modes[$mode]) ? $modes[$mode] : array();
		}

		return isset($modes[$mode]) ? $modes[$mode][$key] : '';
	}

	/**
	 * 编辑器默认选项
	 * 
	 * @return [type] [description]
	 */
	public static function options_default()
	{
		return zotop::filter('editor.options.default', array(
			'tools'             => false,
			'toolbar'           => 'standard',
			'resize'            => true,				
			'imagetools_proxy'  => U('tinymce/server/proxy'),
			'images_upload_url' => U('tinymce/server/uploadimage',array('HTTP_X_REQUESTED_WITH'=>true)),
			'content_css'       => ZOTOP_URL_THEMES .'/'. C('site.theme') . '/css/editor.css'
		));
	}

	/**
	 * 编辑器参数
	 * 
	 * @param  array $options 编辑器参数
	 * @return array
	 */
	public static function basic($options)
	{
		if ( $options['toolbar'] == 'basic' )
		{
			$options['plugins'] = "['advlist autolink lists link image media table paste textcolor colorpicker textpattern onekeyclear localautosave tabfocus']";
			$options['toolbar'] = 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
		}

		return $options;
	}

	/**
	 * 编辑器参数
	 * 
	 * @param  array $options 编辑器参数
	 * @return array
	 */
	public static function standard($options)
	{		
		if ( $options['toolbar'] == 'standard' )
		{
			$options['plugins'] = array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave wordcount tabfocus codesample powerpaste');
			$options['toolbar'] = 'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image codesample';
		}

		return $options;
	}

	/**
	 * 编辑器参数
	 * 
	 * @param  array $options 编辑器参数
	 * @return array
	 */
	public static function full($options)
	{
		if ( $options['toolbar'] == 'full' )
		{
			$options['plugins'] = array('advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample powerpaste zotop_pagebreak');
			$options['toolbar'] = 'undo redo copy paste pastetext searchreplace removeformat onekeyclear | forecolor backcolor | bold italic underline strikethrough | subscript superscript | formatselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link unlink | image media | insertdatetime table anchor charmap emoticons blockquote hr | visualchars nonbreaking codesample template pagebreak | localautosave preview code fullscreen';
		}

		return $options;
	}	
}
?>