{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div><!-- side -->

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<form action="{u('member/admin/index')}" method="post" class="searchbar">
			{if $keywords}
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" style="width:200px;" x-webkit-speech/>
			{else}
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" x-webkit-speech/>
			{/if}
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>
		<div class="action">
			{if count($models) == 1}
				{loop $models $m}
				<a class="btn btn-highlight" href="{u('member/admin/add/'.$m['id'])}">{t('添加%s',$m['name'])}</a>
				{/loop}
			{else}
			<div class="menu btn-menu">
				<a class="btn btn-highlight" href="javascript:void(0);">{t('添加会员')}<b class="arrow"></b></a>
				<div class="dropmenu">
					<div class="dropmenulist">
						{loop $models $m}
							{if !$m['disabled']}
							<a href="{u('member/admin/add/'.$m['id'])}" data-placement="right" title="{$m['description']}">{t('添加%s',$m['name'])}</a>
							{/if}
						{/loop}
					</div>
				</div>
			</div>
			{/if}
		</div>
	</div>
	{form::header()}
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		<table class="table zebra list">
			<thead>
				<tr>
					<td class="select"><input type="checkbox" class="checkbox select-all"></td>
					<td class="w40 center">{t('状态')}</td>
					<td>{t('用户名')} ({t('昵称')})</td>
					<td class="w140">{t('会员模型/会员组')}</td>
					<td class="w60">{t('积分')}</td>
					<td class="w60">{t('金钱')}</td>
					<td class="w120">{t('手机')}</td>
					<td class="w80">{t('邮箱')}</td>
					<td class="w120">{t('最后登录')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
					<td class="center">	{if $r['disabled']}<i class="icon icon-false false" title="{t('已禁用')}"></i>{else}<i class="icon icon-true true" title="{t('正常')}"></i>{/if}</td>
					<td>
						<div class="title textflow">{$r['username']} <span>( {$r['nickname']} )</span></div>
						<div class="manage">
							<a href="{u('member/admin/edit/'.$r['id'])}">{t('编辑')}</a>
							<s></s>
							<a href="{u('member/admin/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>{$models[$r['modelid']]['name']}<br/>{$groups[$r['groupid']]['name']}</td>
					<td>{$r['point']}</td>
					<td>{$r['amount']}</td>
					<td>
						<div class="textflow">
							{if $r['mobile']}
								{if $r['mobilestatus']}<i class="icon icon-true true" title="{t('已验证')}"></i>{else}<i class="icon icon-false false" title="{t('未验证')}"></i>{/if}
								{$r['mobile']}
							{else}
								<span class="gray">{t('未填')}</span>
							{/if}
						</div>
					</td>
					<td>
						<span title="{$r['email']}">
							{if $r['emailstatus']}
								<i class="icon icon-true true"></i> {t('已验证')}
								{else}
								<i class="icon icon-false false"></i> {t('未验证')}
							{/if}
						</span>
					</td>
					<td>
						{if intval($r['logintimes'])>0}
							<div>{$r['loginip']}</div>
							<div class="f12">{format::date($r['logintime'])}</div>
						{else}
							{t('尚未登录')}
						{/if}
					</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{/if}
	</div>
	{form::footer()}
	<div class="main-footer">

		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">

		<a class="btn operate" href="{u('member/admin/operate/disabled/1')}" rel="status">{t('禁用')}</a>
		<a class="btn operate" href="{u('member/admin/operate/disabled/0')}" rel="status">{t('启用')}</a>
		<a class="btn operate" href="{u('member/admin/operate/move')}" rel="move" style="display:none;">{t('移动')}</a>
		<a class="btn operate" href="{u('member/admin/operate/delete')}" rel="delete">{t('删除')}</a>

	</div><!-- main-footer -->
</div>


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
				return false;
			}

			var rel = $(this).attr('rel');
			var href = $(this).attr('href');
			var text = $(this).text();
			var data = $('form').serializeArray();
				data.push({name:'operation',value:text});

			if ( rel == 'move' ) {

			}else{
				$.loading();
				$.post(href,$.param(data),function(msg){
					$.msg(msg);
				},'json');
			}

			return true;
		});

	});
});
</script>
{template 'footer.php'}