{template 'header.php'}
<div class="side">
	{template 'guestbook/side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			<li{if $status == ''} class="current"{/if}><a href="{u('guestbook/admin/index')}">{t('全部')} </a></li>
			{loop $statuses $s $t}
			<li{if $status == $s} class="current"{/if}><a href="{u('guestbook/admin/index/'.$s)}">{$t}</a> <span class="f12 red">({$statuscount[$s]})</span></li>
			{/loop}
		</ul>
		<form action="{u('guestbook/admin')}" class="searchbar" method="post">
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" x-webkit-speech/>
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{u('guestbook/admin/add')}" data-width="600" data-height="200">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{form::header()}
		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="select"><input type="checkbox" class="checkbox select-all"></td>
			<td class="w50 center">{t('状态')}</td>
			<td class="w50 center">{t('回复')}</td>
			<td>{t('内容')}</td>
			<td class="w140">{t('发布者/发布时间')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td class="center"><i class="icon icon-{$r['status']} {$r['status']}" title="{$statuses[$r['status']]}"></td>
				<td class="center">{if $r['replytime']}<i class="icon icon-true true" title="{t('已回复')}"></i>{else}<i class="icon icon-false false" title="{t('未回复')}"></i>{/if}</td>
				<td>
					<div class="title textflow" title="{$r['content']}">{$r['content']}</div>
					<div class="manage">
						<a class="dialog-open" href="{u('guestbook/admin/reply/'.$r['id'])}" data-width="600px" data-height="320px">{t('回复')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('guestbook/admin/delete/'.$r['id'])}">{t('删除')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('guestbook/admin/deletebyip/'.$r['id'])}">{t('删除此IP全部留言')}</a>
						<s></s>
						<a class="dialog-open" href="{u('system/ipbanned/add/'.$r['createip'])}" data-width="600px" data-height="160px">{t('禁止此IP留言')}</a>
					</div>
				</td>
				<td>
					<div title="{t('邮箱')} : {$r['email']}&#10;IP : {$r['createip']}">{$r['name']}</div>
					<div class="f12 time">{format::date($r['createtime'])}</div>
				</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">
		<a class="btn operate" href="{u('guestbook/admin/operate/delete')}">{t('删除')}</a>
		{loop $statuses $s $t}
		<a class="btn operate" href="{u('guestbook/admin/operate/'.$s)}">{$t}</a>
		{/loop}
	</div><!-- main-footer -->
</div><!-- main -->
<script type="text/javascript">
$(function(){
	var tablelist = $('table.list').data('tablelist');

	//底部全选
	$('input.select-all').on('click',function(e){
		tablelist.selectAll(this.checked);
	});

	//操作
	$("a.operate").each(function(){
		$(this).on("click", function(event){
			event.preventDefault();
			if( tablelist.checked() == 0 ){
				$.error('{t('请选择要操作的项')}');
			}else{
				var href = $(this).attr('href');
				var text = $(this).text();
				var data = $('form.form').serializeArray();;
					data.push({name:'operation',value:text});
				$.loading();
				$.post(href,$.param(data),function(msg){
					$.msg(msg);
				},'json');
			}
		});
	});
});
</script>
{template 'footer.php'}