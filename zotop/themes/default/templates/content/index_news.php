{template 'header.php'}

<style type="text/css">
	.row-img-text {margin:20px 0;}
	.row-img-text .main-inner{margin:0;margin-left:450px;}
	.row-img-text .side{margin:0;margin-left:-100%;width:450px;}

	.row-img-text div.slider{width:420px;height:360px;overflow: hidden;}
	.row-img-text div.slider img{width:420px;height:360px;}

	.row-img-text ul.image-text {margin-top: -10px;margin-bottom: 5px;}
	.row-img-text ul.image-text .text b{margin-bottom: 10px;}
	.row-img-text ul.image-text .text b a{font-size: 24px;}
	.row-img-text ul.image-text .text p{height:68px;}

	.row-box2 .box-0{float:left;width:580px;overflow:hidden;}
	.row-box2 .box-1{float:right;width:580px;overflow:hidden;}
	.row-box2 .box-body{min-height:320px;}
</style>


<div class="channel clearfix">
	<h1>{m('content.category.get',$category.rootid, 'name')}</h1>
	<ul>
		{content action="category" cid="$category.rootid" return="c"}			
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/content}
	</ul>
</div>

<div class="position none">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
    </ul>
</div>

<div class="row row-img-text">
	<div class="main">
		<div class="main-inner">
			{block 'top-news'}

			{block 'hand-news'}
		</div>
	</div>
	<div class="side">
		{block 'image-news'}
	</div>
</div>

<div class="row row-box2">

{content action="category" cid="$category.id" return="c"}
	<div class="box box-{($i%2)}">
	<div class="box-head">
		<div class="box-title">{$c.name}</div>
		<div class="box-action"><a class="more" href="{$c.url}">{t('更多')}</a></div>
	</div>
	<div class="box-body">
		<ul class="image-text">
			{content cid="$c.id" size="1" image="true"}
			<li>
				<div class="image"><a href="{$r.url}"><img src="{$r.image}" alt="{$r.title}"/></a></div>
				<div class="text">
					<b><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,25)}{$r.new}</a></b>
					<p>{str::cut($r.summary,90)}</p>
				</div>
			</li>
			{/content}
		</ul>
		<ul class="list">
			{content cid="$c.id" size="8"}
			<li><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a></li>
			{/content}
		</ul>
	</div><!-- box-body -->
	</div><!-- box -->
	{$i++}
{/content}

</div>

{template 'footer.php'}