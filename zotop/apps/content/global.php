<?php
//注册类库
zotop::register(array(
	'content_hook'		=>	ZOTOP_PATH_APPS.DS.'content'.DS.'libraries'.DS.'hook.php',
));

/**
 * 开始菜单
 */
zotop::add('system.start','content_hook::start');


/**
 * 全局导航
 */
zotop::add('system.global.navbar','content_hook::global_navbar');


/**
 * 全局消息
 */
zotop::add('system.global.msg','content_hook::global_msg');


/**
 * 会员投稿菜单
 */
zotop::add('member.navlist','content_hook::membernavlist');


/**
 * 注册控件
 */
form::field('title', 'content_hook::field_title');
form::field('summary', 'content_hook::field_summary');


/**
 * 模板标签注册
 */
template::tag('content','tag_content');

/**
 * 模版标签解析 {content cid="1"}……{/content}
 *
 * 标签说明
 *
 *  cid|categoryid  指定要查询的栏目ID，调用多个ID用英文逗号”,”隔开
 *  pid|parentid    指定要调用内容的父ID，默认为 0
 *  mid|modelid     指定要调用的内容模型ID，调用多个ID用英文逗号”,”隔开，同一个model的内容都会被选择出来,如“article”
 *  keywords        指定内容包含的关键词,多个关键词用英文逗号“,”隔开，例如:tags="测试,test"
 *  image           指定查询结果是否必须含有或者不含缩略图，默认为不筛选 ，image="true" 筛选缩略图不为空的内容，image="false" 筛选缩略图为空的内容
 *  orderby         指定排序条件，多个排序条件用英文逗号“,”隔开,例如：orderby="createtime desc"
 *  size            内容表查询结果返回条数限制(必须是正整数)，默认为 10
 *  page            当前页码，设置后自动开启分页, 为数字的时候自动获取当前页码，其它为指定页码
 *  cache           缓存时间，单位：秒，true = 系统缓存设置 false = 永久缓存 0 不缓存
 *  return          返回值变量 return：返回结果变量名，默认为 r
 *
 *  内部标签
 *
 *  {$r.id},{$r.title},{$r.style},{$r.url},{$r.createtime},{$r.updatetime} ……
 *
 *  扩展标签
 *
 *  {$r.newflag} {$r.new}
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

/**
 * 内容详细页面显示钩子
 */
zotop::add('content.show','content_hook::content_hits');
zotop::add('content.show','content_hook::content_tags');
zotop::add('content.show','content_hook::content_pages');
zotop::add('content.fullcontent','content_hook::content_full');
?>