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

	<div class="content">
		<h1 class="content-title">{$content.title}</h1>

		<div class="content-info">

			<b>{t('作者')}:</b> {if $content.author} {$content.author} {else} {c('site.name')} {/if}
			<b>{t('来源')}:</b> {if $content.source} {$content.source} {else} {c('site.name')} {/if}
			<b>{t('发布')}:</b> {format::date($content.createtime)}
			<b>{t('点击')}:</b> {$content.hits}

			<div class="fr share">{block 'share'}</div>
		</div>

		<div class="content-body gallery">
			<ul	id="galleryview" class="none">
			{loop $content.gallery $r}
				<li><img src="{$r.image}" data-description="{$r.description}" alt=""/></li>
			{/loop}
			</ul>
		</div>

		<div class="blank"></div>
		<div class="blank"></div>

		<div class="content-body">{$content.summary}</div>

		<div class="blank"></div>
		<div class="blank"></div>

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

</div><!-- row -->

{html::import(__THEME__.'/js/galleryview/jquery.galleryview.js')}
{html::import(__THEME__.'/js/galleryview/jquery.timers-1.2.js')}
{html::import(__THEME__.'/js/galleryview/jquery.galleryview.css')}
<script type="text/javascript">
$(function(){
	$('#galleryview').galleryView({
		panel_width: 1150,
		panel_height : 600,
		panel_scale : 'fit',
		frame_width : 120,
		frame_height : 60,
		infobar_opacity: 0.8,
		enable_overlays: true,
		autoplay: true
	}).show();
})
</script>

{template 'footer.php'}