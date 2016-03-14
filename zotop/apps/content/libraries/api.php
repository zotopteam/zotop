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
		$start = array_merge($start, array(

			'content'=>array(
				'text'        => A('content.name'),
				'href'        => u('content/content'),
				'icon'        => A('content.url').'/app.png',
				'description' => A('content.description'),
				'allow'       => priv::allow('content')
			),

			'content_category'=>array(
				'text'        => t('栏目管理'),
				'href'        => u('content/category'),
				'icon'        =>zotop::app('content.url').'/icons/category.png',
				'description' => t('栏目设置、添加、删除、排序'),
				'allow'       => priv::allow('content','category')
			),
					
		));

		// 设置提示信息
		/*
		if ( $pending = m('content.content.statuscount',0,'pending') )
		{
			$start['content']['msg']   = t('%s 条待审',$pending);
			$start['content']['badge'] = $pending;
		}
		*/
	
		return $start;
	}

	/**
	 * 全局导航
	 *
	 * @param $nav array 已有数据
	 * @return array
	 */
	public static function global_navbar($nav)
	{

		$nav['content'] = array(
			'text'        => t('内容'),
			'href'        => u('content/content'),
			'icon'        => A('content.url').'/app.png',
			'description' => A('content.description'),
			'allow'       => priv::allow('content'),
			'active'      => (ZOTOP_APP == 'content')
		);

		return $nav;
	}

	/**
	 * 注册全局消息
	 *
	 * @param $msg array 已有数据
	 * @return array
	 */
	public static function global_msg($msg)
	{
		// 设置提示信息
		
		if ( $pending = m('content.content.statuscount',0,'pending') )
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
	 * 后台内容项管理菜单
	 * 
	 * @param  $content $array 当前内容数据
	 * @return array
	 */
	public static function manage_menu($content)
	{
		$menu = array(
			'view'   => array('text'=>t('访问'),'icon'=>'fa fa-eye','href'=>$content['url'],'target'=>'_blank'),
			'edit'   => array('text'=>t('编辑'),'icon'=>'fa fa-edit','href'=>U('content/content/edit/'.$content['id'])),
			'stick'  => array('text'=>t('置顶'),'icon'=>'fa fa-arrow-up','href'=>U('content/content/stick/'.$content['id'].'/1'), 'class'=>'js-ajax-post'),
			'delete' => array('text'=>t('删除'),'icon'=>'fa fa-times','href'=>U('content/content/delete/'.$content['id']), 'class'=>'js-confirm')
		);
		
		if ( $content['stick'] )
		{
			$menu['stick']  = array('text'=>t('取消置顶'),'icon'=>'fa fa-arrow-down','href'=>U('content/content/stick/'.$content['id'].'/0'), 'class'=>'js-ajax-post');
		}
		
		// hook
		$menu = zotop::filter('content.manage_menu',$menu);

		// 格式化输出
		$array = array();

		foreach ($menu as $k => $m)
		{
			if ( priv::allow('content/manage/'.$k) )
			{
				$array[$k] = arr::take($m,'text','icon') + array('attrs'=>$m);
			}					
		}

		return $array;
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

		$attrs['id']         = $id;
		$attrs['class']      = isset($attrs['class']) ? 'title ruler '.$attrs['class'] : 'title ruler';
		$attrs['stylefield'] = isset($attrs['stylefield']) ? $attrs['stylefield'] : 'style';

		$html[] = '<div class="input-group">';
		$html[] = form::field_text($attrs);
		$html[] = form::field_hidden(array('name'=>$attrs['stylefield'],'value'=>$attrs['style'],'tabindex'=>-1));
		$html[] = '<span class="input-group-btn">';
		$html[] = '	<a href="javascript:void(0);" tabindex="-1" class="btn btn-default btn-icon" rel="bold" title="'.t('加粗').'"><i class="fa fa-bold"></i></a>';
		$html[] = '	<a href="javascript:void(0);" tabindex="-1" class="btn btn-default btn-icon" rel="color" title="'.t('颜色').'"><i class="fa fa-font"></i></a>';
		$html[] = '</span>';
		$html[] = '</div>';
		$html[] = '<label for="'.$id.'" generated="true" class="error"></label>';
		$html[]	= html::import(A('content.url').'/common/field.title.js');

		return implode("\n",$html);
	}

	/**
	 * 摘要控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function field_summary($attrs)
	{
		return form::field_textarea($attrs);
	}	

	/**
	 * 多图控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function field_images($attrs)
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
		$html[] = '		<a class="btn btn-icon-text upload" id="'.$attrs['id'].'-upload" href="'.U('system/upload/uploadprocess', $upload).'"><i class="icon icon-upload"></i><b>'.t('上传').'</b></a>';
		$html[] = '		<a class="btn btn-icon-text select" href="'.U('system/upload/image', $upload).'"><i class="icon icon-image"></i><b>'.t('已上传').'</b></a>';
		$html[] = '		<a class="btn btn-icon-text select" href="'.U('system/upload/library/image', $upload).'"><i class="icon icon-images"></i><b>'.t('图像库').'</b></a>';
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

	/**
	 * 模板标签解析
	 * 
	 * @return mixed
	 */
	public static function content_template_tags()
	{
		template::tag('content');

		/**
		 * 模版标签 {content action="list"}……{/content}
		 *
		 * 标签说明
		 *
		 *	cid|categoryid	指定要查询的栏目ID，调用多个ID用英文逗号”,”隔开
		 *	pid|parentid	指定要调用内容的父ID，默认为 0
		 *	mid|modelid		指定要调用的内容模型ID，调用多个ID用英文逗号”,”隔开，同一个model的内容都会被选择出来,如“article”
		 *	keywords		指定内容包含的关键词,多个关键词用英文逗号“,”隔开，例如:tags="测试,test"
		 *	image			指定查询结果是否必须含有或者不含缩略图，默认为不筛选 ，image="true" 筛选缩略图不为空的内容，image="false" 筛选缩略图为空的内容
		 * 	orderby			指定排序条件，多个排序条件用英文逗号“,”隔开,例如：orderby="createtime desc"
		 *	size			内容表查询结果返回条数限制(必须是正整数)，默认为 10
		 *	page			当前页码，设置后自动开启分页, 为数字的时候自动获取当前页码，其它为指定页码
		 *	cache			缓存时间，单位：秒，true = 系统缓存设置 false = 永久缓存 0 不缓存
		 *	return			返回值变量 return：返回结果变量名，默认为 r
		 *
		 *	内部标签
		 *
		 *	{$r.id},{$r.title},{$r.style},{$r.url},{$r.createtime},{$r.updatetime}
		 *
		 *	扩展标签
		 *
		 *	{$r.newflag} {$r.new}
		 */		

		function tag_content($attrs)
		{
			$result = array();

			switch(strtolower($attrs['action']))
			{
				case 'category':
					// 获取分类数据 {content action="category" cid="1"}{/content}
					$result = m('content.category')->getChild($attrs['cid'], true);
					break;
				case 'position':
					// 获取导航分类数据 {content action="position" cid="1"}{/content}
					$result = m('content.category')->getParents($attrs['cid']);
					break;
				default:
					$result = m('content.content')->tag_content($attrs);
					break;
			}

			return $result;
		}
	}

	/**
	 * HOOK 前台内容显示之前的数据处理 点击
	 * 
	 * @param  array $content 内容数组
	 * @param  object $controller 控制器对象
	 * @return array 处理后的内容
	 */
	public static function content_hits($content, $controller)
	{
		// 点击
		m('content.content')->hit($content['id']);

		return $content;		
	}

	/**
	 * HOOK 前台内容显示之前的数据处理 标签数组
	 * 
	 * @param  array $content 内容数组
	 * @return array 处理后的内容
	 */
	public static function content_tags($content, $controller)
	{
		// 关键词转化为标签
		$content['tags'] = $content['keywords'] ? explode(',', $content['keywords']) : array();

		return $content;		
	}	

	/**
	 * HOOK 前台内容显示之前的数据处理 内容分页处理
	 * 
	 * @param  array $content 内容数组
	 * @return array 处理后的内容
	 */
	public static function content_pages($content, $controller)
	{
		if ( strpos( $content['content'], 'mce-pagebreak') === FALSE ) return $content;

		$regex          = "/<p class\\=\"mce-pagebreak\">(.*?)<\\/p>/";			
		$pagecount      = preg_match_all($regex, $content['content'], $matches);
		$titles         = $matches[1];
		$contents       = preg_replace($regex, '[pagebreak]', $content['content']);
		$contents       = explode('[pagebreak]', trim($contents,'[pagebreak]'));	
		$uri            = m('content.content')->url($content['id'],$content['alias'],$content['url'], false);
		$page           = isset($_GET['page']) ? min(intval($_GET['page']), $pagecount) : 1; //页码
		$pagetitlecount = 0;			

		$content['pages'] = array();

		for ($p=1; $p <= $pagecount  ; $p++)
		{ 
			$prevpage = $p == 1 ? '' : $p - 1;
			$nextpage = $p == $pagecount ? '' : $p + 1;
			$title    = trim(trim($titles[$p-1],'&nbsp;'));

			if ( $title ) 
			{
				$pagetitlecount = $pagetitlecount + 1;
			}

			$content['pages'][$p] = array(
				'title'    => $title ? $title : '',
				'content'  => $contents[$p-1],
				'url'      => U($uri,array('page'=>$p)),
				'nexturl'  => $nextpage ? U($uri,array('page'=>$nextpage)) : '',
				'prevurl'  => $prevpage ? U($uri,array('page'=>$prevpage)) : '',
				'nextpage' => $nextpage,
				'prevpage' => $prevpage
			);
		}

		$content['page']               = array();
		$content['page']['count']      = $pagecount;
		$content['page']['titlecount'] = $pagetitlecount;

		// 将分页的内容赋值
		if ( $page and $content['pages'] )
		{
			$content['page']['active']  = $page;
			$content['page']['prevurl'] = $content['pages'][$page]['prevurl'];
			$content['page']['nexturl'] = $content['pages'][$page]['nexturl'];
			$content['page']['title']   = $content['pages'][$page]['title'];
			$content['page']['content'] = $content['pages'][$page]['content'];
		}
		
		return $content;		
	}

	/**
	 * HOOK 处理阅读全文
	 * 
	 * @param  array $content 内容数组
	 * @return array 处理后的内容
	 */
	public static function content_full($content, $controller)
	{
		
		// 去掉空的分页标题
		$content['content'] = str_replace('<p class="mce-pagebreak">&nbsp;</p>', '', $content['content']);

		return $content;
	}

}
?>