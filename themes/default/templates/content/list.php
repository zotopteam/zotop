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
{else}
<div class="blank"></div>
{/if}

<div class="container">

	<ul class="breadcrumb hidden">
	    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
	</ul>

	<div class="panel pagelist">
		{content cid="$category.id" page="true" size="10"}
		<div class="media media-sm">
			<a href="{$r.url}">
			{if $r.image and $loop.index%2==0}
			<div class="media-left"><img src="{$r.image width="400" height="300"}" alt="{$r.title}"></div>
			{/if}
			<div class="media-body">
				<div class="media-heading">
					<span class="pull-right text-muted hidden-xs">{$r.createtime date="date"}</span>
					<h4 {$r.style}>{$r.title}{$r.new}</h4> 
				</div>
				<div class="media-summary hidden-xs">{$r.summary nl2p="true" length="100"}</div>
			</div>
			{if $r.image and $loop.index%2==1}
			<div class="media-right"><img src="{$r.image width="400" height="300"}" alt="{$r.title}"></div>
			{/if}					
			</a>
		</div>
		{else}
			<div class="nodata">
				{t('暂时没有数据，请稍后访问')}
			</div>
		{/content}

		<nav class="text-center">{$pagination}</nav>				
	</div>

</div>

{template 'footer.php'}