{template 'header.php'}

<ul class="breadcrumb">
    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
	{content action="position" cid="$category.id"}
	<li><a href="{$r.url}">{$r.name}</a></li>
	{/content}
    <li class="active">正文</li>
</ul>

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-10 main">

		<div class="content content-box content-page">
			<h1 class="content-title">
				{$content.title}
			</h1>
			<div class="content-info clearfix">
				<div>
					<span>施工状态：{if $content.state}已竣工{else}施工中{/if}</span>&nbsp;&nbsp;&nbsp;
					<span>安装方案：{$content.program}</span>
				</div>
			</div>

			<div class="content-body">
				<div class="custerinfo clearfix">
					<h4 class="title">{$content.customer} {$content.house}</h4>				
					<div class="image"><img src="{$content.image}" alt="{$content.title}"></div>
					<div class="description">{$content.demand}</div>
				</div>
			</div>
			
			<div class="content-body-title">方案设计</div>
			<div class="content-body text-justify">
				{$content.content}
			</div>

		<div class="content-body-title">施工图集·工艺</div>
		<div class="content-body gallery">
			{if $content.gallery}


			<div id="galleryslider" class="carousel slide" data-ride="carousel">
			  <ol class="carousel-indicators">
				{loop $content.gallery $i $r}
				<li data-target="#galleryslider" data-slide-to="{$i}" {if $i==0}class="active"{/if}></li>
				{/loop}
			  </ol>
			  <div class="carousel-inner" role="listbox">
			  	{loop $content.gallery $i $r}
			    <div class="item {if $i==0}active{/if}">
			      <img src="{thumb($r.image,400,300)}" alt="{$r.title}">    
			    </div>
			    {/loop}
			  </div>
			  <a class="left carousel-control" href="#galleryslider" role="button" data-slide="prev">
			    <span class="icon-prev fa fa-angle-left" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#galleryslider" role="button" data-slide="next">
			    <span class="icon-next fa fa-angle-right" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
			</div>
			{/if}

			<div class="blank"></div>
			{format::textarea($content.technology)}
		</div>

		
		
		<div class="content-body-title">业主反馈</div>
		<div class="content-body">{$content.feedback}</div>


			<div class="content-tool">
				<div class="share">
					<div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone"></a><a href="#" class="bds_tsina" data-cmd="tsina"></a><a href="#" class="bds_tqq" data-cmd="tqq"></a><a href="#" class="bds_renren" data-cmd="renren"></a><a href="#" class="bds_weixin" data-cmd="weixin"></a></div>
					<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdPic":"","bdStyle":"0","bdSize":"16"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
				</div>				
			</div>

			<div class="content-prev-next">
				<div class="content-prev">
					<b>{t('上一篇')}</b> ：
					{content cid="$category.id" prev="$content.id" size="1"}
					<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}</a>
					{/content}
					{if empty($tag_content)} {t('暂无内容')} {/if}
				</div>

				<div class="content-next">
					<b>{t('下一篇')}</b> ：
					{content cid="$category.id" next="$content.id" size="1"}
					<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}</a>
					{/content}
					{if empty($tag_content)} {t('暂无内容')} {/if}
				</div>
			</div>			
		</div><!-- content -->

		</div><!-- main -->
		
		{if m('content.category.get',$category.rootid, 'childid')}

		<div class="col-sm-2 side">
			<div class="blank hidden-md hidden-lg"></div>
			<dl class="list-group">
				<dt class="list-group-item">{m('content.category.get',$category.rootid, 'name')}</dt>
				{content action="category" cid="$category.rootid"}
				<dd class="list-group-item"><a href="{$r.url}" {$r.style}>{$r.title}</a> {$r.new}</dd>
				{/content}			
			</dl>
		</div><!-- side -->

		{/if}

	</div><!-- row -->	

</div> 

{template 'footer.php'}