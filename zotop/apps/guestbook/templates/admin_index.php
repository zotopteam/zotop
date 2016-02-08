{template 'header.php'}

{template 'guestbook/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>

		<div class="btn-group">
			<a href="{u('guestbook/admin/index')}" class="btn {if $status == ''}btn-success{else}btn-default{/if}"><i class="fa fa-all fa-fw"></i><b>{t('全部')}</b></a></li>
			{loop m('guestbook.guestbook.status') $s $t}
			<a href="{u('guestbook/admin/index/'.$s)}" class="btn {if $status == $s}btn-success{else}btn-default{/if}">
				<i class="fa fa-{$s} fa-fw"></i><span>{$t}</span>
				{if m('guestbook.guestbook.statuscount',$s)}<em class="badge badge-xs badge-danger">{m('guestbook.guestbook.statuscount',$s)}</em>{/if}
			</a>
			{/loop}			
		</div>

		<form action="{u('guestbook/admin')}" class="searchbar input-group" method="post" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>
		<div class="action">
			<a class="btn btn-primary js-open" href="{u('guestbook/admin/add')}" data-width="600" data-height="400">
				<i class="fa fa-plus"></i> <b>{t('添加')}</b>
			</a>
		</div>
	</div><!-- main-header -->

	{if empty($data)}
		<div class="nodata">{t('暂时没有任何数据')}</div>
	{else}

	<div class="main-body scrollable">

		{form::header()}
		<table class="table table-hover table-nowrap list">
		<thead>
			<tr>
			<td class="select"><input type="checkbox" class="checkbox select-all"></td>
			<td>{t('留言信息')}</td>
			</tr>
		</thead>
		<tbody>

		{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td>
					<div class="title">
						<span class="pull-right">{t('发布时间')} : {format::date($r['createtime'])}</span>
						<span>{t('姓名')} : {$r['name']}</span>
						<span class="text-muted">{t('邮箱')}：{$r['email']} {t('IP')}：{$r['createip']}</span>
					</div>
					<hr>
					<p class="{if $r.status=='publish'}text-success{else}text-default{/if}">{$r['content']}</p>
					{if $r.reply}
					<hr>
					<p class="text-danger">
						 {c('guestbook.adminname')} {format::date($r.replytime)}: {$r.reply}
					</p>
					{/if}
					<hr>
					<div class="manage">
						<a class="js-open" href="{u('guestbook/admin/reply/'.$r['id'])}" data-width="600" data-height="400"><i class="fa fa-reply"></i> {t('回复')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('guestbook/admin/delete/'.$r['id'])}"><i class="fa fa-times"></i> {t('删除')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('guestbook/admin/deletebyip/'.$r['id'])}"><i class="fa fa-times-circle"></i> {t('删除此IP全部留言')}</a>
						<s>|</s>
						<a class="js-open" href="{u('system/ipbanned/add/'.$r['createip'])}" data-width="600" data-height="400"><i class="fa fa-ban"></i> {t('禁止此IP留言')}</a>
					</div>
				</td>
			</tr>
		{/loop}
		
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		

		<input type="checkbox" class="checkbox select-all middle">
		<a class="btn btn-default js-operate" href="{u('guestbook/admin/operate/delete')}"><i class="fa fa-times fa-fw"></i><span>{t('删除')}</span></a>

		<div class="btn-group">
			{loop m('guestbook.guestbook.status') $s $t}
			{if $status!=$s}
			<a class="btn btn-default js-operate" href="{u('guestbook/admin/operate/'.$s)}"><i class="fa fa-{$s} fa-fw"></i><span>{$t}</span></a>
			{/if}
			{/loop}
		</div>

		{pagination::instance($total,$pagesize,$page)}

	</div><!-- main-footer -->
	
	{/if}
</div><!-- main -->
<script type="text/javascript">
$(function(){
	var tablelist = $('table.list').data('tablelist');

	//底部全选
	$('input.select-all').on('click',function(e){
		tablelist.selectAll(this.checked);
	});

	//操作
	$("a.js-operate").each(function(){
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