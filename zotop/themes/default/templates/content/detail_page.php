{template 'header.php'}

<div class="container">

	<ul class="breadcrumb">
	    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
	    <li class="active">正文</li>
	</ul>

	<div class="row">
		<div class="col-sm-10 col-main">

		<div class="content content-box content-page">
			<h1 class="content-title">
				{$content.title}
			</h1>

			{if $content.pages}
			<div class="content-pages">					
					
				{if $content.page.titlecount}
				<nav class="text-center">
					<dl class="content-pagetitles">
						<dt>{t('本文导航')}</dt>
						{loop $content.pages $n $p}
						<dd class="text-overflow {if $content.page.active==$n}active{/if}">
							<i class="fa fa-caret-right"></i> <a href="{$p.url}">{t('第$1页',$n)} {$p.title}</a>
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

							{loop $content.pages $n $p}
							<a href="{$p.url}" class="btn btn-default {if $content.page.active==$n}active{/if}">{$n}</a>
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

		</div><!-- main -->

		<div class="col-sm-2 col-side">
			<div class="blank hidden-md hidden-lg"></div>
			<dl class="list-group">
				<dt class="list-group-item">{$category.name}</dt>
				{content cid="$category.id"}
				<dd class="list-group-item"><a href="{$r.url}" {$r.style}>{$r.title}</a> {$r.new}</dd>
				{/content}			
			</dl>
		</div><!-- side -->

	</div><!-- row -->

</div> 

{template 'footer.php'}