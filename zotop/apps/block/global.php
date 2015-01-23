<?php
// 区块缓存路径
define('BLOCK_PATH_CACHE', ZOTOP_PATH_RUNTIME . DS . 'block');

// 注册类库到系统中
zotop::register('block_api', A('block.path') . DS . 'libraries' . DS . 'api.php');

// 开始菜单
zotop::add('system.start', 'block_api::start');

// 快捷导航
zotop::add('system.globalnavbar', 'block_api::globalnavbar');

// 一键刷新
zotop::add('system.refresh', 'block_api::refresh');


/**
 * 模板hook，解析模板中的区块标签 {block '……'} 
 *
 * @param string $str 完整的模板代码
 * @param object $tpl 模板对象
 * @return  $str 解析后的模板代码
 */
zotop::add('template.parse', 'block_api::template_parse');


/**
 * 模板标签显示
 *
 * @param string $str 区块参数
 * @param obj $tpl 当前模板对象
 * @return string  区块数据
 */
function block_show($attrs, $tpl)
{
    // 缓存已经存在，直接返回缓存的区块数据
    if ( file_exists(BLOCK_PATH_CACHE . DS . "{$attrs['id']}.html") )
    {
        return file_get_contents(BLOCK_PATH_CACHE . DS . "{$attrs['id']}.html");
    }

    // 缓存不存在，自动生成缓存并换回数据
    return m('block.block.publish', $attrs, $tpl);    
}

// 注册控件：推荐位
form::field('blockcommend', 'block_api::field_commend');
?>