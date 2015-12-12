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
class ueditor_field
{
	/**
	 * Ueditor编辑器
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function editor($attrs)
	{
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
			$options = array('root'=>A('ueditor.url').'/editor/', 'toolbar'=>'standard', 'resize'=>0, 'tools'=>false, 'server'=>U('ueditor/server'), 'css'=> ZOTOP_URL_THEMES .'/'. C('site.theme') . '/css/editor.css');

			foreach( array('toolbar','resize','tools','css') as $attr )
			{
				if ( isset($attrs[$attr]) and !empty($attrs[$attr]) )
				{
					$options[$attr] = $attrs[$attr];unset($attrs[$attr]);
				}
			}

			//定义默认的行数高度
			$rows = zotop::filter('editor.rows',array(
				'full'     => 25,
				'standard' => 15,
				'simple'   => 8
			));

			$attrs['id'] 	= empty($attrs['id']) ? $attrs['name'] : $attrs['id'];
			$attrs['rows'] 	= empty($attrs['rows']) ? $rows[$options['theme']] : $attrs['rows'];

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
			$html[]	= html::import(A('ueditor.url').'/editor/ueditor.all.min.js');
			$html[]	= html::import(A('ueditor.url').'/global.js');
			$html[] = '<script type="text/javascript">';
			$html[] = '	$(function(){';
			$html[] = '		$(\'textarea[name='.$attrs['name'].']\').editor('.json_encode($options).');';
			$html[] = '	});';
			$html[] = '</script>';
			$html[] = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

			unset($attrs['upload'],$attrs['options']);

			return implode("\n",$html);
	}
}
?>