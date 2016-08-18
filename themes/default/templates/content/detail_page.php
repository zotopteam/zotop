{template 'header.php'}

<nav class="nav nav-inline">
	<ul>
		{content cid="$category.id"}
		<li><a href="{$r.url}" {if $r.id==$content.id}class="active"{/if}>{$r.title}</a></li>
		{/content}
	</ul>
</nav>

<div class="container">

	<ul class="breadcrumb hidden">
	    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
	    <li class="active">{$content.title}</li>
	</ul>


	<div class="content content-box content-page">
		<h1 class="content-title hidden">
			{$content.title}
		</h1>

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
	</div><!-- content -->

</div> 

{template 'footer.php'}