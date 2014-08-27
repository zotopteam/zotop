{template 'header.php'}

<div class="channel clearfix">
	<h1>{t('天天商城')}</h1>
	<ul>
		{shop action="category" return="c"}
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/shop}
	</ul>
</div>

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{shop action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/shop}
    </ul>
</div>

<div class="blank"></div>

{if $category.shop}
<div class="content">
	<div class="content-body">{$category.content}</div>
</div>
{/if}

<ul class="image-list goods-list">
	{shop cid="$category.id" page="true" size="20"}
	<li class="item-{$n} {if $n%4==3}last{/if}">
		<div class="image"><a href="{$r.url}" title="{$r.description}"><img src="{thumb($r.thumb,260,220)}" alt="{$r.name}"/></a></div>
		<div class="title"><a href="{$r.url}" title="{$r.name}">{$r.name}</a></div>
	</li>
	{/shop}
</ul>

<div class="pagination">{$pagination}</div>
{template 'footer.php'}