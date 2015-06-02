{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		<li><a href="{u('member/index')}">{t('会员中心')}</a></li>
        <li>{t('投稿')}</li>
    </ul>
</div>

<div class="row row-s-m">
<div class="main">
<div class="main-inner">

	<div class="main-header">
		<div class="title">
			{if $keywords} {t('搜索 "%s"',$keywords)} {elseif $categoryid}	{$category['name']}	{else} {$title} {/if}
		</div>
		<div class="action">
			{if count($postmodels) < 4}

				{loop $postmodels $i $m}
					<a class="btn btn-highlight btn-icon-text" href="{u('content/contribute/add/'.$categoryid.'/'.$m['id'])}" title="{$m['description']}">
						<i class="icon icon-add"></i><b>{$m['name']}</b>
					</a>
				{/loop}

			{else}
			<div class="menu btn-menu">
				<a class="btn btn-highlight" href="javascript:void(0);">{t('添加')} <i class="icon icon-angle-down"></i></a>
				<div class="dropmenu">
					<div class="dropmenulist">
						{loop $postmodels $i $m}
							<a href="{u('content/contribute/add/'.$categoryid.'/'.$m['id'])}" data-placement="right" title="{$m['description']}">{t('添加%s',$m['name'])}</a>
						{/loop}
					</div>
				</div>
			</div>
			{/if}			
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="table list" id="datalist" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td>{t('标题')}</td>
			<td class="w80">{t('状态')}</td>
			<td class="w80">{t('点击')}</td>
			{if !$categoryid}
			<td class="w100">{t('栏目')}</td>
			{/if}
			<td class="w140">{t('发布时间')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td>
					<div class="title textflow" {if $r['style']}style="{$r['style']}"{/if}>
					{$r['title']}{if $r['thumb']}<i class="icon icon-image" data-src="{$r['thumb']}"></i>{/if}
					</div>
					<div class="manage">


						<a href="{u('content/contribute/edit/'.$r['id'])}">{t('编辑')}</a>
						<s></s>

						<a href="{$r['url']}" target="_blank">{t('访问')}</a>
						<s></s>

						{zotop::run('content.manage',$r)}
						<a class="dialog-confirm" href="{u('content/contribute/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
				<td><i class="icon icon-{$r['status']} {$r['status']}"></i> {$statuses[$r['status']]}</td>
				<td>{$r['hits']}</td>
				{if !$categoryid}
				<td><div class="textflow">{$categorys[$r['categoryid']]['name']}</div></td>
				{/if}
				<td>{format::date($r['createtime'])}</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>
	</div><!-- main-footer -->



</div> <!-- main-inner -->
</div> <!-- main -->
<div class="side">
	{template 'member/side.php'}
</div> <!-- side -->
</div> <!-- row -->

{template 'footer.php'}