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
		
		<div class="content-body">

			<div id="slider-{$content.id}" class="carousel slide" data-ride="carousel">
			  <ol class="carousel-indicators">
				{loop $content.gallery $i $r}
				<li data-target="#slider-{$content.id}" data-slide-to="{$i}" {if $i==0}class="active"{/if}></li>
				{/loop}
			  </ol>
			  <div class="carousel-inner" role="listbox">
			  	{loop $content.gallery $i $r}
			    <div class="item {if $i==0}active{/if}">
			      <img src="{$r.image}" alt="{$r.title}">
			    </div>
			    {/loop}
			  </div>
			  <a class="left carousel-control" href="#slider-{$content.id}" role="button" data-slide="prev">
			    <span class="icon-prev fa fa-angle-left" aria-hidden="true"></span>
			    <span class="sr-only">{t('前')}</span>
			  </a>
			  <a class="right carousel-control" href="#slider-{$content.id}" role="button" data-slide="next">
			    <span class="icon-next fa fa-angle-right" aria-hidden="true"></span>
			    <span class="sr-only">{t('后')}</span>
			  </a>
			</div>			
		</div>

		<div class="content-body">
			{$content.summary}
		</div>

	</div><!-- content -->

</div> 

{template 'footer.php'}