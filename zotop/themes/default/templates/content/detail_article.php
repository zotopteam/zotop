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
		<div class="col-sm-10 main">

		<div class="content content-box content-page">
			<h1 class="content-title">
				{$content.title}
			</h1>
			<div class="content-info">

				<b>{t('作者')}:</b> {if $content.author} {$content.author} {else} {c('site.name')} {/if} &nbsp;&nbsp;&nbsp;
				<b>{t('来源')}:</b> {if $content.source} {$content.source} {else} {c('site.name')} {/if} &nbsp;&nbsp;&nbsp;
				<b>{t('发布')}:</b> {format::date($content.createtime)} &nbsp;&nbsp;&nbsp;
				<b>{t('点击')}:</b> {$content.hits}


			</div>			
			<div class="content-body text-justify">
				{$content.content}
			</div>

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