{template 'header.php'}
<div class="side">
{template 'block/side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title"> {t('搜索“%s”',$keywords)} </div>

		<form class="searchbar" method="post" action="{u('block/block')}">
			<input type="text" name="keywords" value="{$keywords}" style="width:200px" placeholder="{t('请输入关键词')}"/>
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>

		<div class="action">
			<div class="menu btn-menu">
				<a class="btn btn-highlight" href="javascript:void(0);">{t('新建区块')}<i class="arrow"></i></a>
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
		{form::header()}
		<table class="table zebra list" id="datalist" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="none">&nbsp;</td>
			<td>{t('标题')}</td>
			<td class="w140">{t('标识')}</td>
			<td class="w180">{t('模板标签')}</td>

			<td class="w80">{t('类型')}</td>
			<td class="w80">{t('分类')}</td>
			<td class="w140">{t('更新时间')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="none">&nbsp;<input type="hidden" name="orderid[]" value="{$r['id']}"></td>

				<td>
					<div class="title textflow" title="{$r['title']}"{if $r['style']}style="{$r['style']}"{/if}>
					{$r['name']}<span>{$r['description']}</span>
					</div>
					<div class="manage">
						<a href="{u('block/block/data/'.$r['id'])}">{t('数据管理')}</a>
						<s></s>
						<a class="ajax-post" href="{u('block/block/publish/'.$r['id'])}">{t('发布')}</a>
						<s></s>
						<a href="{u('block/block/edit/'.$r['id'])}">{t('设置')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('block/block/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
				<td>{$r['uid']}</td>
				<td><input value="{$r['tag']}" class="text" style="width:80%"/></td>
				<td><div class="textflow">{$types[$r['type']]}</div></td>
				<td><div class="textflow">{$categories[$r['categoryid']]['name']}</div></td>
				<td>
					<div>{m('system.user.get', $r['userid'], 'username')}</div>
					<div class="f12 time">{format::date($r['updatetime'])}</div>
				</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('共搜索到 %s 个相关数据',count($data))}</div>
	</div><!-- main-footer -->

</div><!-- main -->
{template 'footer.php'}