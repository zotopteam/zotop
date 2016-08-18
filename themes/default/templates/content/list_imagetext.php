{template 'header.php'}



<div class="container">

	<ul class="breadcrumb">
	    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
	</ul>


	<div class="pagelist">
	
	{content cid="$category.id" page="true" size="5"}
	<div class="media">
		<a href="{$r.url}">
		<div class="media-left"><img src="{thumb($r.image,200,200)}" alt="{$r.title}"></div>
		<div class="media-body">
			<div class="media-heading" {$r.style}>{$r.title} {$r.new}</div>
			<div class="media-info hidden">
				<i class="fa fa-folder"></i> {m('content.category.get',$r.categoryid, 'name')}
				&nbsp;&nbsp;&nbsp;
				<i class="fa fa-calendar"></i> {format::date($r.createtime,'u|date')}
				&nbsp;&nbsp;&nbsp;
				<i class="fa fa-user"></i> {$r.hits}
			</div>				
			<div class="media-description visible-lg-block">{str::cut($r.summary,240)}</div>
		</div>
		</a>
	</div>
	{/content}

	</div>

	<nav class="pagenav">{$pagination}</nav>

      <script type="text/javascript" src="{__THEME__}/js/jquery-ias.min.js"></script>  
      <script>
      $(function($){
          var ias = $.ias({
              container:'.pagelist',
              item:'.media',
              pagination:'.pagination',
              next:'.next'
          });

          ias.extension(new IASSpinnerExtension({html:'<div class="pageloader text-center"><div class="fa fa-spinner fa-spin fa-2x"></div></div>'}));
          //ias.extension(new IASTriggerExtension({html: '<div class="pageloader text-center"><a href="javascript:void(0);" class="btn btn-default btn-block">加载更多</a></div>'}));
          ias.extension(new IASNoneLeftExtension({html: '<div class="pageloader text-center"><a href="javascript:void(0);" class="btn btn-default btn-block" disabled>已经是全部了</a></div>'})); 
      }); 
      </script> 

</div>

{template 'footer.php'}