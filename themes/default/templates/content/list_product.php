{template 'header.php'}

<ul class="breadcrumb">
    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
	{content action="position" cid="$category.id"}
	<li><a href="{$r.url}">{$r.name}</a></li>
	{/content}
</ul>

<div class="container-fluid">

	{if m('content.category.get',$category.id, 'childid')}
	<div class="channel clearfix">
		<a href="{m('content.category.get',$category.id, 'url')}" class="btn btn-default {if $category.id == $category.id}btn-success{/if}" role="button">{m('content.category.get',$category.id, 'name')}</a>
		{content action="category" cid="$category.id" return="c"}			
		<a href="{$c.url}" class="btn btn-default item-{$n} {if $category.id == $c.id}btn-success{/if}" role="button">{$c.name}</a>
		{/content}
	</div>
	{elseif $category.parentid != 0}
	<div class="channel clearfix">
		<a href="{m('content.category.get',$category.parentid, 'url')}" class="btn btn-default {if $category.id == $category.parentid}btn-success{/if}" role="button">{m('content.category.get',$category.parentid, 'name')}</a>
		{content action="category" cid="$category.parentid" return="c"}			
		<a href="{$c.url}" class="btn btn-default item-{$n} {if $category.id == $c.id}btn-success{/if}" role="button">{$c.name}</a>
		{/content}	
	</div>
	{/if}

	
	<div class="row">	
	<div class="pagelist">

		{content cid="$category.id" modelid="product" page="true" size="10"}
		<div class="col-xs-6 col-md-4 dataitem">

		<div class="thumbnail">
			<a href="{$r.url}">
			<div class="image"><img src="{thumb($r.image,400,300)}" alt="{$r.title}"></div>
			<div class="caption">
				<div class="title text-overflow" {$r.style}>{$r.title} {$r.new}</div>
				<div class="info hidden">
					<i class="fa fa-folder"></i> {m('content.category.get',$r.categoryid, 'name')}
					&nbsp;&nbsp;&nbsp;
					<i class="fa fa-calendar"></i> {format::date($r.createtime,'u|date')}
					&nbsp;&nbsp;&nbsp;
					<i class="fa fa-user"></i> {$r.hits}
				</div>				
				<div class="description hidden">{str::cut($r.summary,140)}</div>
			</div>
			</a>
		</div>

		</div>
		{/content}
	</div><!-- pagelist -->
	</div><!-- row -->
	

	<nav>{$pagination}</nav>

      <script type="text/javascript" src="{__THEME__}/js/jquery-ias.min.js"></script>  
      <script>
      $(function($){
          var ias = $.ias({
              container:'.pagelist',
              item:'.dataitem',
              pagination:'.pagination',
              next:'.next'
          });

          ias.extension(new IASSpinnerExtension({html:'<div class="pageloader text-center"><div class="fa fa-spinner fa-spin fa-2x"></div></div>'}));
          //ias.extension(new IASTriggerExtension({html: '<div class="pageloader text-center"><a href="javascript:void(0);" class="btn btn-default btn-block">加载更多</a></div>'}));
          ias.extension(new IASNoneLeftExtension({html: '<div class="pageloader text-center"><a href="javascript:void(0);" class="btn btn-default" disabled>已经是全部了</a></div>'})); 
      }); 
      </script> 

</div>

{template 'footer.php'}