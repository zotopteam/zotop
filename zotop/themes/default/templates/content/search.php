{template 'header.php'}

{if $keywords}

	<div class="channel clearfix">
		<h1>{t('搜索')} : “{$keywords}”</h1>
	</div>

	<div class="blank"></div>
	<div class="blank"></div>


	<div class="row">

	{if $data}

		<ul class="image-text pagelist">
			{loop $data $r}

			{if $n>0 and $n%5==0}<hr/>{/if}
			
			<li>
				{if $r.thumb}<div class="image fr"><a href="{$r.url}"><img src="{$r.thumb}" alt="{$r.title}"/></a></div>{/if}
				<div class="text">
					<b><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a></b>
					<p>{str::cut($r.summary,200)}</p>
				</div>
			</li>	
			{/loop}
		</ul>

		<div class="blank"></div>

		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

	{else}

		<div class="nodata">{t('没有找到相关数据')}</div>

	{/if}

	</div>

{else}
	<div class="nodata error">{t('请输入关键词进行搜索')}</div>
{/if}


{template 'footer.php'}