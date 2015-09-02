<?php
defined('ZOTOP') OR die('No direct access allowed.');

/**
 * 区块 api
 *
 * @package		shop
 * @version		1.0
 * @author		zotop team
 * @copyright	zotop team
 * @license		http://www.zotop.com
 */
class block_api
{
	/**
	 * 开始按钮
	 * 
	 * @param  array $nav 
	 * @return array
	 */
	public static function start($nav)
	{
	    $nav['block'] = array(
	        'text'          => A('block.name'),
	        'href'          => U('block/admin'),
	        'icon'          => A('block.url') . '/app.png',
	        'description'   => A('block.description'),
	        'allow'         => priv::allow('block')
	    );

	    return $nav;
	}

	/**
	 * 全局导航
	 * 
	 * @param  array $nav 
	 * @return array
	 */
	public static function globalnavbar($nav)
	{
	    $nav['block'] = array(
	        'text'          => t('区块'),
	        'href'          => u('block/admin'),
	        'icon'          => A('block.url') . '/app.png',
	        'description'   => A('block.description'),
	        'allow'         => priv::allow('block'),
	        'current'       => (ZOTOP_APP == 'block'));

	    return $nav;
	}

	/**
	 * 清理区块缓存文件
	 * 
	 * @return [type] [description]
	 */
	public static function refresh()
	{
		folder::clear(BLOCK_PATH_CACHE);
	} 

	
	/**
	 * 解析模板标签
	 * 
	 * @param  string $str 模板
	 * @param obj $tpl 当前模板对象
	 * @return 返回解析后的模板
	 */
	public static function template_parse($str, $tpl)
	{
	    return preg_replace("/\{block(\s+[^}]+?)\}/ie", "block_api::template_parse_tag('\\1',\$tpl)", $str);
	}

	/**
	 * 获取区块解析后的数据
	 *
	 * @param string $str 区块参数
	 * @param obj $tpl 当前模板对象
	 * @return string  区块数据
	 */
	public static function template_parse_tag($str, $tpl)
	{
	    $attrs = $tpl->parse_attrs($str);

	    if ( $id = intval($attrs['id']) )
	    {
	        return '<?php echo block_show(' . $tpl->array_attrs($attrs) .', $this)?>';
	    }

	    return '<div class="error block-error">'.t('区别编号错误').'</div>';
	}

	/**
	 * 标题控件
	 *
	 * @param $attrs array 控件参数
	 * @return string 控件代码
	 */
	public static function field_commend($attrs)
	{
		$options = m('block.block')->field('id,name')->where('commend','>',0)->select();
		$options = arr::hashmap($options,'id','name');

		if ( $options )
		{
			$attrs['type'] 		= 'checkbox';
			$attrs['options'] 	= $options;

			return form::field($attrs);
		}

		return '<div class="field-text">'.t('没有找到推荐区块，请进入区块管理添加').'</div>';
	}
}
?>