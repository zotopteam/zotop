{template 'header.php'}

{if m('content.category.get',$category.rootid, 'childid')}
<nav class="nav nav-inline">
	<ul>
		<li><a href="{m('content.category.get',$category.rootid, 'url')}" {if $category.rootid==$category.id}class="active"{/if}>{t('全部')}</a></li>
		{content action="category" cid="$category.rootid"}
		<li><a href="{$r.url}" {if $r.id==$category.id}class="active"{/if}>{$r.name}</a></li>
		{/content}
	</ul>
</nav>
{/if}

<div class="container">

	<ul class="breadcrumb">
	    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
	    <li class="active">正文</li>
	</ul>


		<div class="content content-box content-page">
			<h1 class="content-title">
				{$content.title}
			</h1>
			<div class="content-info">

				<b>{t('作者')}:</b> {if $content.author} {$content.author} {else} {c('site.name')} {/if} &nbsp;&nbsp;&nbsp;
				<b>{t('来源')}:</b> {if $content.source} {$content.source} {else} {c('site.name')} {/if} &nbsp;&nbsp;&nbsp;
				<b>{t('发布')}:</b> {format::date($content.createtime)} &nbsp;&nbsp;&nbsp;
				<b>{t('点击')}:</b> {$content.hits}


			</div>			

			{if $content.pages}
			<div class="content-pages">					
					
				{if $content.page.titlecount}
				<nav class="text-center">
					<dl class="content-pagetitles">
						<dt>{t('本文导航')}</dt>
						{loop $content.pages $i $p}
						<dd class="text-overflow {if $content.page.active==$i}active{/if}">
							<i class="fa fa-caret-right"></i> <a href="{$p.url}">{t('第$1页',$i)} {$p.title}</a>
						</dd>
						{/loop}
					</dl>
				</nav>			
				{/if}

				<div class="content-body text-justify">
					{$content.page.content}
				</div>

				<nav class="text-center">
					<div class="content-pagination btn-toolbar">
						<div class="btn-group">						
							{if $content.page.prevurl}
							<a href="{$content.page.prevurl}" class="btn btn-default prev"><i class="fa fa-angle-left"></i> {t('上一页')}</a>
							{/if}	

							{loop $content.pages $i $p}
							<a href="{$p.url}" class="btn btn-default {if $content.page.active==$i}active{/if}">{$i}</a>
							{/loop}

							{if $content.page.nexturl}
							<a href="{$content.page.nexturl}" class="btn btn-default next">{t('下一页')} <i class="fa fa-angle-right"></i></a>
							{/if}
						</div>

						<div class="btn-group">
							<a href="javascript:;" class="btn btn-default fullcontent">{t('阅读全文')}</a>
						</div>

						<script>
							$(function(){
								$('.fullcontent').on('click',function(){
									$('.content-pages').load('{U('content/detail/fullcontent/'.$content.id)}');
								});
							})
						</script>
					</div>
				</nav>

			</div>
			{else}
				<div class="content-body text-justify">
					{$content.content}
				</div>				
			{/if}


			<div class="content-prev-next">
				<div class="content-prev">
					<b>{t('上一篇')}</b> ：
					{content cid="$category.id" prev="$content.id" size="1"}
					<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}</a>
					{/content}
					{if empty($tag_content)} {t('暂无内容')} {/if}
				</div>

				<div class="content-next">
					<b>{t('下一篇')}</b> ：
					{content cid="$category.id" next="$content.id" size="1"}
					<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}</a>
					{/content}
					{if empty($tag_content)} {t('暂无内容')} {/if}
				</div>
			</div>			
		</div><!-- content -->

</div> 

{template 'footer.php'}