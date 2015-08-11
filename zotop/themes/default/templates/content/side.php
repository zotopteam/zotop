<dl class="list">
	<dt>{t('最新内容')}</dt>
	{content cid="$category.rootid" size="10"}
	<dd><i>▪</i><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,18)}</a>{$r.new}</dd>
	{/content}	
</dl>

<div class="blank"></div>

<dl class="list">
	<dt>{t('热门浏览')}</dt>
	{content cid="$category.rootid" orderby="hits desc" size="10"}
	<dd><i>▪</i><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,18)}</a>{$r.new}</dd>
	{/content}	
</dl>

<div class="blank"></div>

<dl class="list image-list none">
	<dt>{t('热门图片')}</dt>
	{content cid="$category.rootid" image="true" size="5" orderby="hits desc"}
	<dd>
		<div class="image"><a href="{$r.url}"><img src="{$r.image}" alt="{$r.title}"/></a></div>
		<div class="text">
			<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a>
		</div>
	</dd>
	{/content}	
</dl>
