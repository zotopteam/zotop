{template 'header.php'}

<style type="text/css">
.mainslider{margin-bottom: 24px;}
.mainslider div.slider{height:400px;}
.mainslider div.slider img{width:1150px;height:400px;}	
</style>



<div class="channel clearfix">
	<h1>{t('天天商城')}</h1>
	<ul>
		{shop action="category" return="c"}
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/shop}
	</ul>
</div>

<div class="mainslider">
	{block 'shop-mainslider'}
</div>

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
				<div class="image"><a href="{$r.url}" title="{$r.description}"><img src="{thumb($r.thumb,260,220)}" alt="{$r.name}"/></a></div>
				<div class="title"><a href="{$r.url}" title="{$r.name}">{$r.name}</a></div>
			</li>
			{/shop}
		</ul>
	</div><!-- box-body -->
	</div><!-- box -->
{/shop}



{template 'footer.php'}