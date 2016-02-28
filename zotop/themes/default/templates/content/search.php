{template 'header.php'}

<div class="banner banner-small text-center">
	<div class="container">
		<h1>{t('内容搜索')}</h1>
		<div class="blank"></div>
	    <form class="navbar-search-form" action="{u('content/search')}" method="get" role="search">
	      <div class="form-group">
	        <div class="input-group">
	          <input type="text" name="keywords" class="form-control input-lg" value="{$_GET['keywords']}" placeholder="{t('请输入关键词')}">
	          <div class="input-group-addon"><button type="submit" class="btn-transparent"><fa class="fa fa-search"></fa> <b>{t('搜索一下')}</b></button></div>
	        </div>
	      </div>
	    </form>
	</div>
</div>

<div class="container">
	
	{if $keywords}
		
		{if $data}			
				
				<div class="breadcrumb">
					{t('$1为您找到相关结果约 $2 个',C('site.name'),$total)}
				</div>

				<div class="panel pagelist">
				
				{loop $data $r}
				<div class="media">
					<a href="{$r.url}">
					{if $r.image}
					<div class="media-left"><img src="{thumb($r.image,200,150)}" alt="{$r.title}"></div>
					{/if}
					<div class="media-body">
						<div class="media-heading">
							<h4>{$r.title}</h4>  {$r.new}
						</div>		
						<p class="media-summary">{str::cut($r.summary,140)}</p>
						<div>
							<span class="text-success">{$r.url}</span>
							<span class="text-muted">{m('content.category.get',$r.categoryid, 'name')}</span>
							<span class="text-muted">{format::date($r.createtime,'u|date')}</span>

						</div>						
					</div>
					</a>
				</div>
				{/loop}

				
			</div>

			<nav class="pagenav">{pagination::instance($total,$pagesize,$page)}</nav>		

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