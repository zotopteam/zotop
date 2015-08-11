{template 'header.php'}

<ul class="breadcrumb">
    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
	<li>搜索 “{$keywords}” 结果</li>
</ul>

<div class="blank"></div>

<div class="container-fluid">

{if $keywords}
	
    <form class="navbar-search-form" action="{u('content/search')}" method="get" role="search">
      <div class="form-group">
        <div class="input-group">
          <input type="text" name="keywords" class="form-control" value="{$_GET['keywords']}" placeholder="请输入关键词">
          <div class="input-group-addon"><button type="submit" class="btn-transparent"><fa class="fa fa-search"></fa> 搜索一下</button></div>
        </div>
      </div>
    </form>

	{if $data}
		

			<div class="pagelist">
			
			{loop $data $r}
			<div class="media">
				<a href="{$r.url}">
				<div class="media-left"><img src="{thumb($r.image,200,150)}" alt="{$r.title}"></div>
				<div class="media-body">
					<div class="media-heading" {$r.style}>{$r.title} {$r.new}</div>
					<div class="media-info hidden-sm">
						<i class="fa fa-folder"></i> {m('content.category.get',$r.categoryid, 'name')}
						&nbsp;&nbsp;&nbsp;
						<i class="fa fa-calendar"></i> {format::date($r.createtime,'u|date')}
						&nbsp;&nbsp;&nbsp;
						<i class="fa fa-user"></i> {$r.hits}
					</div>				
					<div class="media-description hidden">{str::cut($r.summary,140)}</div>
				</div>
				</a>
			</div>
			{/loop}


			<nav class="pagenav">{$pagination}</nav>
		</div>

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

	{else}
		<div class="blank"></div>
		<div class="blank"></div>
		<div class="alert alert-danger" role="alert">{t('没有找到相关数据')}</div>
		<div class="blank"></div>
		<div class="blank"></div>
	{/if}

	

{else}
	<div class="blank"></div>
	<div class="blank"></div>
	<div class="alert alert-danger" role="alert">{t('请输入关键词进行搜索')}</div>
	<div class="blank"></div>
	<div class="blank"></div>
{/if}
</div>

{template 'footer.php'}