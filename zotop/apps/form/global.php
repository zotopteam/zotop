<?php
/*
* 自定义表单 全局文件
*
* @package		form
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/

// 注册类库到系统中
zotop::register(array(
    'form_api' => A('form.path') . DS . 'libraries' . DS . 'api.php',
));

// 在开始页面注册一个快捷方式
zotop::add('system.start', 'form_api::start');


// 注册一个控件
//form::field('form_test', 'form_api::test');


template::tag('formdata');

function tag_formdata($attrs)
{
	$params = arr::take($attrs,'formid','orderby','page','size','cache','return','keywords');

	@extract($params);

	if ( $formid )
	{
		$fields = m('form.field.getall', $formid);

		if ( $fields )
		{
			foreach($fields as $i=>$r)
			{
				if ( $r['list'] ) $list[$r['name']] = $r;

				if ( $r['order'] ) $order[$r['name']] = $r['order'];

				if ( $r['search'] ) $search[$r['name']] = $r['label'];
			}
			
			$db = m('form.data.init', $formid);					
	

			// 查询排序
			$orderby = $orderby ? $orderby : $order;
			$orderby ? $db->orderby($orderby) : $db->orderby('id','desc');

			// 模糊查询
			if ( $search and $keywords )	
			{
				$_search = array();

				foreach ($search as $key => $r)
				{
					$_search[] = 'or';
					$_search[] = array($key,'like', $keywords);
				}

				array_shift($_search);

				$db->where($_search);
			}

			// 查询结果
			$size = intval($size) ? intval($size) : 10;

			// 分页
			if ( intval($page) )
			{
				$return = $db->getPage($page, $size, $total);
			}
			else
			{
				$return = $db->limit($size)->getAll();
			}

			return $return;								
		}
	}	

	return array();	
}
?>