{template 'header.php'}

<div class="channel clearfix">
	<h1>{m('content.category.get',$category.rootid, 'name')}</h1>
	<ul>
		{content action="category" cid="$category.rootid" return="c"}
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/content}
	</ul>
</div>

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
        <li>{$content.title}</li>
    </ul>
</div>

<div class="row">
<div class="main">
<div class="main-inner">
	<div class="content">
		<h1 class="content-title">{$content.title}</h1>

		<div class="content-info">

			<a class="btn btn-highlight fl" href="{$content.downloadurl}">{t('立即下载')}</a>

			<div class="fr share">{block 'share'}</div>

			<b>{t('大小')}:</b> {$content.filesize}
			<b>{t('点击')}:</b> {$content.hits}
			<b>{t('已下载')}:</b> {$content.download} {t('次')}
			<b>{t('发布')}:</b> {format::date($content.createtime)} 

			
		</div>


		<div class="content-body">
			{$content.content}
		</div>

		{if $content.tags}
		<div class="content-tags">
			<b>{t('标签')}</b> ：{loop $content.tags $t} <a href="{u('content/tag/'.$t)}">{$t}</a> {/loop}
		</div>
		{/if}

		<div class="content-prev">
			<b>{t('上篇')}</b> ：
			{content cid="$category.id" prev="$content.id" size="1"}
			<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a>
			{/content}
			{if empty($tag_content)} {t('暂无内容')} {/if}
		</div>

		<div class="content-next">
			<b>{t('下篇')}</b> ：
			{content cid="$category.id" next="$content.id" size="1"}
			<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a>
			{/content}
			{if empty($tag_content)} {t('暂无内容')} {/if}
		</div>

		{content keywords="$content.keywords" ignore="$content.id" size="5"/}
		{if $tag_content}
		<div class="content-related">
			<h5>{t('相关内容')}</h5>
			<ul class="list">
			{loop $tag_content $r}
			<li><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a></li>
			{/loop}
			</ul>
		</div>
		{/if}

	</div><!-- content -->

</div>
</div><!-- main -->

<div class="side">
	{template 'content/side.php'}
</div><!-- side -->
</div><!-- row -->
{template 'footer.php'}


