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

			<div class="pagelist">

				<ul class="breadcrumb">
				    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
					{content action="position" cid="$category.id"}
					<li><a href="{$r.url}">{$r.name}</a></li>
					{/content}
				</ul>

				<div class="row">
					{content cid="$category.id" page="true" size="20"}
					<div class="col-xs-6 col-md-4 dataitem">
		 
						<div class="thumbnail product">
							<a href="{$r.url}">
							<div class="image"><img src="{thumb($r.image,400,300)}" alt="{$r.title}"></div>
							<div class="title text-overflow" {$r.style}>{$r.title} {$r.new}</div>			
							<div class="description text-overflow">{str::cut($r.summary,140)}</div>
							</a>
						</div>

					</div>
					{/content}

					{if $n==0}
						<div class="nodata">
							{t('暂时没有数据，请稍后访问')}
						</div>
					{/if}
				</div>
			
				<nav class="text-center">{$pagination}</nav>

			</div><!-- pagelist -->

		

	<script type="text/javascript" src="{__THEME__}/js/jquery-ias.min.js"></script>  
	<script>
	$(function($){
	  // var ias = $.ias({
	  //     container:'.pagelist',
	  //     item:'.dataitem',
	  //     pagination:'.pagination',
	  //     next:'.next'
	  // });

	  //ias.extension(new IASSpinnerExtension({html:'<div class="pageloader text-center"><div class="fa fa-spinner fa-spin fa-2x"></div></div>'}));
	  //ias.extension(new IASTriggerExtension({html: '<div class="pageloader text-center"><a href="javascript:void(0);" class="btn btn-default btn-block">加载更多</a></div>'}));
	  //ias.extension(new IASNoneLeftExtension({html: '<div class="pageloader text-center"><a href="javascript:void(0);" class="btn btn-default" disabled>已经是全部了</a></div>'})); 
	});
	</script>
	<script>
		// $(function(){
		// 	$('.dataitem').on('mouseenter',function(e){
		// 		$(this).find('.thumbnail').addClass('flipInY');
		// 	}).on('mouseleave',function(e){
		// 		$(this).find('.thumbnail').removeClass('flipInX').removeClass('flipInY');
		// 	})
		// })
	</script>

</div>

{template 'footer.php'}