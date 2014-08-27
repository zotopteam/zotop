{template 'header.php'}
<div class="side">
{template 'block/side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title"> {t('常用区块')} </div>

		<form class="searchbar" method="post" action="{u('block/block')}">
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" x-webkit-speech/>
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>

		<div class="action">
			<div class="menu btn-menu">
				<a class="btn btn-highlight" href="javascript:void(0);"><i class="icon icon-add"></i><b>{t('区块')}</b><b class="arrow"></b></a>
				<div class="dropmenu">
					<div class="dropmenulist">
						{loop $types $k $v}
							<a href="{u('block/block/add/'.$k.'/'.$category['id'])}">{t('新建%s区块',$v)}</a>
						{/loop}
					</div>
				</div>
			</div>
		</div>
	</div><!-- main-header -->

	<div class="main-body scrollable">

		{if empty($data)}
		<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}
		<ul class="navlist">
			{loop $data $r}
			<li>
				<a href="{u('block/block/data/'.$r['id'])}" title="{$r['description']}" class="nav clearfix">
					<img src="{A('block.url')}/icons/{$r['type']}.png">
					<h2>{$r['name']}</h2>
					<p>
					{if $r['description']}{$r['description']}{else}{t('更新时间')}: {format::date($r['updatetime'])}{/if}
					</p>
				</a>
			</li>
			{/loop}
		<ul>
		{/if}

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('拖动列表项可以调整顺序')}</div>
	</div><!-- main-footer -->

</div><!-- main -->

{template 'footer.php'}