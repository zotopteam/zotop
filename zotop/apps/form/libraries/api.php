<?php
defined('ZOTOP') OR die('No direct access allowed.');
/*
* 自定义表单 api
*
* @package		form
* @version		1.0
* @author		zotop
* @copyright	zotop
* @license		http://www.zotop.com
*/
class form_api
{
	/**
	 * 注册快捷方式
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function start($start)
	{
		$start['form'] = array(
			'text' => A('form.name'),
			'href' => U('form/admin'),
			'icon' => A('form.url') . '/app.png',
			'description' => A('form.description')
		);

		foreach ( m('form.form.cache') as $id => $form)
		{
			
			if ( $form['disabled'] ) continue;

			$start['form_'.$form['table']] = array(
				'text' => $form['name'],
				'href' => U('form/data/index/'.$id),
				'icon' => A('form.url') . '/app.png',
				'description' => $form['description']
			);
		}

		return $start;
	}


	/**
	 * 测试控件，请修改或者删除此处代码，详细修改方式请参见文档
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function test($attrs)
	{
		// 控件属性
		$html['field'] = form::field_text($attrs);

		return implode("\n",$html);
	}

	/**
	 * formdata 模板标签解析
	 * 
	 * @param  array $attrs 标签属性数组
	 * @return array 获取的数据结果
	 */
	public function data($attrs)
	{
		$params = arr::take($attrs,'formid','select','orderby','page','size','cache','return','search','keywords');

		@extract($params);

		if ( $formid and $fields = m('form.field.cache', $formid) )
		{
			foreach($fields as $k=>$r)
			{
				if ( $r['list'] ) $_list[$k] = $r;

				if ( $r['order'] ) $_orderby[$k] = $r['order'];

				if ( $r['search'] ) $_search[$k] = $r['label'];
			}
			
			$db = m('form.data.init', $formid);	

			// 查询排序，优先自定义排序，默认排序
			$orderby = $orderby ? $orderby : $_orderby;
			$orderby ? $db->orderby($orderby) : $db->orderby('id','desc');

			// 模糊查询， 优先自定义搜索字段，默认搜索字段
			$search = $search ? explode(',', $search) : $_search;

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

			// 选择的字段
			$select = $select ? $select : implode(',', array_keys($_list));

			// 分页 page="true"
			if ( strtolower($page) == 'true' )
			{
				$return = $db->select('id,'.$select)->getPage($page, $size, $total);

				$return['data'] = form_api::process_data($return['data'], $fields);
			}
			else
			{
				$return = $db->select('id,'.$select)->limit($size)->getAll();
				$return = form_api::process_data($return, $fields);
			}

			//debug::dump($return);

			return $return;								
		}	

		return array();	
	}

	/**
	 * 处理从数据库中获取的数据
	 * 
	 * @param  array $data   从数据库中获取的数据
	 * @param  array $fields 对应的字段信息
	 * @return array 处理后的数据         
	 */
	public function process_data($data, $fields)
	{
		if( is_array($data) and is_array($fields) )
		{
			foreach ($data as &$row)
			{
				foreach ($row as $key => &$val)
				{
					$val = m('form.field.show', $val, $fields[$key]);
				}
			}

			return $data;
		}

		return array();
	}	
}
?>