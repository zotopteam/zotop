{template 'header.php'}

<div class="channel clearfix">
	<h1>{m('content.category.get',$category.rootid, 'name')}</h1>
</div>

<div class="position none">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
    </ul>
</div>

<div class="row row-category">
	<ul class="image-list category-list">
		{content action="category" cid="$category.rootid" return="c"}			
			<li class="item-{$n} {if $n%4 == 3}last{/if}">
				<div class="image"><a href="{$c.url}" title="{$c.name}"><img src="{$c.image}" alt="{$c.name}"></a></div>
				<div class="title"><a href="{$c.url}">{$c.name}</a></div>
			</li>
		{/content}
	</ul>	
</div>

<hr class="split"/>

<div class="row row-box2">
{content action="category" cid="$category.id" return="c"}	
	<div class="box box-{($i%2)}">
	<div class="box-head">
		<div class="box-title">{$c.name}</div>
		<div class="box-action"><a class="more" href="{$c.url}">{t('更多')}</a></div>
	</div>
	<div class="box-body">
		<ul class="image-text">
			{content cid="$c.id" size="2" thumb="true"}
			<li>
				<div class="image"><a href="{$r.url}"><img src="{$r.thumb}" alt="{$r.title}"/></a></div>
				<div class="text">
					<b class="textflow"><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a></b>
					<p>{str::cut($r.summary,90)}</p>
				</div>
			</li>
			{/content}
		</ul>
	</div><!-- box-body -->
	</div><!-- box -->
	{$i++}
{/content}

</div>

<style>
	ul.category-list{margin:0 0;}
	ul.category-list li{width:280px;margin:10px 10px 0 0;}
	ul.category-list li.last{margin-right:0;}
	ul.category-list li div.image{height:180px;}
	ul.category-list li div.image img{width: 100%;height: 100%;}
	ul.category-list li div.title{background: #008aff;padding: 5px 0; text-align: center;}
	ul.category-list li div.title a{color:#fff;font-size: 18px;}

	ul.image-text{}
	ul.image-text div.image{width:180px;height:120px;}
	ul.image-text div.text b a{font-weight: normal;font-size: 18px;}
	ul.image-text div.text p{margin-top:10px;line-height: 22px;}

	.row-box2 .box-0{float:left;width:550px;overflow:hidden;}
	.row-box2 .box-1{float:right;width:550px;overflow:hidden;}
	.row-box2 .box-body{min-height:290px;}

	hr.split{margin:20px 0;}


</style>

{template 'footer.php'}