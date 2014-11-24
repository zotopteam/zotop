<?php
// 区块缓存路径
define('BLOCK_PATH_CACHE', ZOTOP_PATH_RUNTIME . DS . 'block');

//system_start
zotop::add('system.start', 'block_system_start');

function block_system_start($nav)
{
    $nav['block'] = array(
        'text'          => A('block.name'),
        'href'          => u('block/admin'),
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
        'href'          => u('block/admin'),
        'icon'          => A('block.url') . '/app.png',
        'description'   => A('block.description'),
        'allow'         => priv::allow('block'),
        'current'       => (ZOTOP_APP == 'block'));

    return $nav;
}


/**
 * 模板hook，解析模板中的区块标签 {block '……'} 
 *
 * @param string $str 完整的模板代码
 * @param object $tpl 模板对象
 * @return  $str 解析后的模板代码
 */
zotop::add('template.parse', 'block_template_parse');

function block_template_parse($str, $tpl)
{
    return preg_replace("/\{block(\s+[^}]+?)\}/ie", "block_tag_parse('\\1',\$tpl)", $str);
}

/**
 * 获取区块解析后的数据
 *
 * @param string $str 区块参数
 * @param obj $tpl 当前模板对象
 * @return string  区块数据
 */
function block_tag_parse($str, $tpl)
{
    $attrs = $tpl->_attrs($str);
    $attrs = $attrs ? $attrs : array('id'=>trim(trim(trim($str),"'"),'"'));

    if ( $id = intval($attrs['id']) )
    {
        // 缓存已经存在，直接返回缓存的区块数据
        if ( file_exists(BLOCK_PATH_CACHE . DS . "{$id}.html") )
        {
            return file_get_contents(BLOCK_PATH_CACHE . DS . "{$id}.html");
        }

        // 缓存不存在，自动生成缓存并换回数据
        return m('block.block.publish', $id, $tpl, $attrs);
    }

    return '<div class="error block-error">'.t('区别编号错误').'</div>';
}
?>