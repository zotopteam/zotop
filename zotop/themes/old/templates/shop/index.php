{template 'header.php'}
<div class="channel clearfix">
	<h1>{t('天天商城')}</h1>
	<ul>
		{shop action="category" return="c"}
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/shop}
	</ul>
</div>

<div class="position none">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{shop action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/shop}
    </ul>
</div>

<div class="blank" id="about"></div>

{if $category.content}
<div class="content">
	<div class="content-body">{$category.content}</div>
</div>
{/if}

<div class="blank" id="best"></div>

{if $category.childid}

	{shop action="category" cid="$category.id" return="c"}
		<div class="box">
		<div class="box-head">
			<div class="box-title">{$c.name}</div>
			<div class="box-action"><a class="more" href="{$c.url}">{t('更多')}</a></div>
		</div>
		<div class="box-body">
			<ul class="image-list goods-list">
				{shop cid="$c.id" size="8"}
				<li class="item-{$n} {if $n%4==3}last{/if}">
					<div class="image"><a href="{$r.url}" title="{$r.description}"><img src="{thumb($r.image,260,220)}" alt="{$r.name}"/></a></div>
					<div class="title"><a href="{$r.url}" title="{$r.name}">{$r.name}</a></div>
				</li>
				{/shop}
			</ul>
		</div><!-- box-body -->
		</div><!-- box -->
	{/shop}

{else}

		<div class="box">
		<div class="box-head">
			<div class="box-title">{t('产品推荐')}</div>
			<div class="box-action"><a class="more" href="{$category.url}">{t('更多')}</a></div>
		</div>
		<div class="box-body">
			<ul class="image-list goods-list">
				{shop cid="$category.id" size="8"}
				<li class="item-{$n} {if $n%4==3}last{/if}">
					<div class="image"><a href="{$r.url}" title="{$r.description}"><img src="{thumb($r.image,260,220)}" alt="{$r.name}"/></a></div>
					<div class="title"><a href="{$r.url}" title="{$r.name}">{$r.name}</a></div>
				</li>
				{/shop}
			</ul>
		</div><!-- box-body -->
		</div><!-- box -->

{/if}

{template 'footer.php'}