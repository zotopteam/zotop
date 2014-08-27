<?php
// 区块缓存路径
define('BLOCK_PATH_CACHE', ZOTOP_PATH_RUNTIME . DS . 'block');

//是否启用独立模板
define('BLOCK_TEMPLATE_ALONE', false);

//注册类库
//zotop::register(array());

//system_start
zotop::add('system.start', 'block_system_start');
function block_system_start($nav)
{
    $nav['block'] = array(
        'text'          => A('block.name'),
        'href'          => u('block/block'),
        'icon'          => A('block.url') . '/app.png',
        'description'   => A('block.description'),
        'allow'         => priv::allow('block'));

    return $nav;
}

//system_globalnavbar
zotop::add('system.globalnavbar', 'block_system_globalnavbar');
function block_system_globalnavbar($nav)
{
    $nav['block'] = array(
        'text'          => t('区块'),
        'href'          => u('block/block'),
        'icon'          => A('block.url') . '/app.png',
        'description'   => A('block.description'),
        'allow'         => priv::allow('block'),
        'current'       => (ZOTOP_APP == 'block'));

    return $nav;
}

/**
 * 区块调用函数
 *
 * @param string $uid 区块的唯一编号
 * @param obj $tpl 当前模板对象
 * @return string  区块数据
 */
function block($uid, $tpl)
{   
    // 缓存已经存在，直接返回缓存的区块数据
    if ( file_exists(BLOCK_PATH_CACHE . DS . "{$uid}.html") )
    {
        return file_get_contents(BLOCK_PATH_CACHE . DS . "{$uid}.html");
    }

    // 缓存不存在，自动生成缓存并换回数据
	$block = m('block.block');


    if ( $c = $block->publish($uid, $tpl) )
    {
        return $c;
    }

    return '<div class="error block-error">' . $block->error() . '</div>';
}

/**
 * 解析区块标签 {block '……'} 
 *
 * @param mixed $str 模板代码
 * @return  $str 解析后的模板代码
 */
zotop::add('template.parse', 'block_template_parse');

function block_template_parse($str)
{
    return preg_replace("/\{block\s+(.+)\}/", '<?php echo block(\\1, $this); ?>', $str);
}
?>