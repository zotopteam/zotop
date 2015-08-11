<div class="box">
<div class="box-head">
	<h2 class="box-title">{t('最新内容')}</h2>
</div>
<div class="box-body">
	<ul class="list">
		{content  cid="$category.rootid"}
		<li><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,18)}{$r.new}</a></li>
		{/content}
	</ul>
</div><!-- box-body -->
</div><!-- box -->

<div class="blank"></div>

<div class="box">
<div class="box-head">
	<h2 class="box-title">{t('大家都在看')}</h2>
</div>
<div class="box-body">
	<ul class="list">
		{content cid="$category.rootid" size="10" orderby="hits desc" cache="true"}
		<li><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,18)}{$r.new}</a></li>
		{/content}
	</ul>
</div><!-- box-body -->
</div><!-- box -->

<div class="blank"></div>

<div class="box">
<div class="box-head">
	<h2 class="box-title">{t('热门图片')}</h2>
</div>
<div class="box-body">
	<ul class="image-text">
	{content cid="$category.rootid" image="true" size="5" orderby="hits desc" cache="true"}
	<li>
		<div class="image"><a href="{$r.url}"><img src="{$r.image}" alt="{$r.title}"/></a></div>
		<div class="text">
			<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a>
		</div>
	</li>
	{/content}
	</ul>
</div><!-- box-body -->
</div><!-- box -->
