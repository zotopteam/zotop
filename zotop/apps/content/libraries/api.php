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
class content_api
{
	/**
	 * 注册开始页面图标
	 *
	 * @param $start array 已有数据
	 * @return array
	 */
	public static function start($start)
	{
		$start = array_merge($start,array(

			'content'=>array(
				'text' => A('content.name'),
				'href' => u('content/content'),
				'icon' => A('content.url').'/app.png',
				'description' => A('content.description'),
				'allow' => priv::allow('content')
			),

			'content_category'=>array(
				'text'=>t('栏目管理'),
				'href'=>u('content/category'),
				'icon'=>zotop::app('content.url').'/icons/category.png',
				'description'=>t('栏目设置、添加、删除、排序'),
				'allow' => priv::allow('content','category')
			),
		));

		// 设置提示信息
		if ( $pending = m('content.content')->getPendingcount() )
		{
			$start['content']['msg'] = t('%s 条待审',$pending);
		}

		return $start;
	}

	/**
	 * 全局导航
	 *
	 * @param $nav array 已有数据
	 * @return array
	 */
	public static function globalnavbar($nav)
	{

		$nav['content'] = array(
			'text' => t('内容'),
			'href' => u('content/content'),
			'icon' => A('content.url').'/app.png',
			'description' => A('content.description'),
			'allow' => priv::allow('content'),
			'current' => (ZOTOP_APP == 'content')
		);

		return $nav;
	}

	/**
	 * 注册全局消息
	 *
	 * @param $msg array 已有数据
	 * @return array
	 */
	public static function globalmsg($msg)
	{
		// 设置提示信息
		if ( $pending = m('content.content')->getPendingCount() )
		{
			$msg[] = array(
				'text' => t('您有 %s 等待审核内容', $pending),
				'href' => u('content/content/index/0/pending'),
				'type' => 'pending',
			);
		}

		return $msg;
	}

	/**
	 * 注册会员投稿
	 *
	 * @param $nav array 已有数据
	 * @return array
	 */
	public static function membernavlist($nav)
	{
		$c = m('content.category')->contribute();
		
		$g = m('member.group')->get(zotop::user('groupid'));

		if ( !empty($c) and ( $g['settings']['contribute'] or zotop::user('modelid') == 'admin' ) )
		{
			$nav['contribute']['text'] = t('投稿');
			$nav['contribute']['menu']['list'] = array('icon'=>'icon-list',	'text'=>t('投稿管理'), 'href'=>U('content/contribute/index'));
		}

		return $nav;
	}


	/**
	 * 标题控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function field_title($attrs)
	{
		//获取临时id
		$id = str_replace(array('.','[',']',' ','/','\\'), '', $attrs['name'].'_'.microtime(true));

		$attrs['id'] = $id;
		$attrs['class'] = isset($attrs['class']) ? 'title ruler '.$attrs['class'] : 'title ruler';
		$attrs['stylefield'] = isset($attrs['stylefield']) ? $attrs['stylefield'] : 'style';

		$html[] = form::field_text($attrs);
		$html[] = form::field_hidden(array('name'=>$attrs['stylefield'],'value'=>$attrs['style'],'tabindex'=>-1));
		$html[] = '<a href="javascript:void(0);" tabindex="-1" class="btn btn-icon" rel="bold" title="'.t('加粗').'"><i class="icon icon-bold"></i></a>';
		$html[] = '<a href="javascript:void(0);" tabindex="-1" class="btn btn-icon" rel="color" title="'.t('颜色').'"><i class="icon icon-color"></i></a>';
		$html[] = '<label for="'.$id.'" generated="true" class="error"></label>';
		$html[]	= html::import(A('content.url').'/common/field.title.js');

		return implode("\n",$html);
	}

	/**
	 * 多图控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function field_gallery($attrs)
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

		$html[]	= html::import(A('system.url').'/common/plupload/plupload.full.js');
		$html[]	= html::import(A('system.url').'/common/plupload/i18n/zh_cn.js');
		$html[]	= html::import(A('system.url').'/common/plupload/jquery.upload.js');

		$html[]	= html::import(A('content.url').'/common/field.gallery.js');
		$html[]	= html::import(A('content.url').'/common/field.gallery.css');

		$html[] = '	<div class="gallery-area" id="'.$attrs['id'].'">';
		$html[] = '	<div class="gallery-toolbar">';
		$html[] = '		<a class="btn btn-icon-text upload" id="'.$attrs['id'].'-upload" href="'.U('system/attachment/uploadprocess', $upload).'"><i class="icon icon-upload icon-upload-image"></i><b>'.t('上传').'</b></a>';
		$html[] = '		<a class="btn btn-icon-text select" href="'.U('system/attachment/upload/image', $upload).'"><i class="icon icon-image"></i><b>'.t('图库').'</b></a>';
		$html[] = '	</div>';
		$html[] = '<div id="'.$attrs['id'].'-upload-progress" class="gallery-progressbar progressbar"><span class="progress"></span><span class="percent"></span></div>';
		$html[] = '<div class="controls gallery-data" id="'.$attrs['id'].'-upload-dragdrop">';
		$html[] = '<table class="table sortable gallery-list">';
		$html[] = '<tbody></tbody>';
		$html[] = '</table>';
		$html[] = '</div>';
		$html[] = '</div>';
		$html[] = '<script type="text/javascript">';
		$html[] = '	$(function(){';
		$html[] = '		$(\'#'.$attrs['id'].'\').galleryeditor("'.$attrs['name'].'",'.json_encode($attrs['value']).','.json_encode($upload).');';
		$html[] = '	});';
		$html[] = '</script>';
		$html[] = '<label for="'.$attrs['id'].'" generated="true" class="error"></label>';

		return implode("\n",$html);
	}
}
?>