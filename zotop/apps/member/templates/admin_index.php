{template 'header.php'}

{template 'member/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		{if $keywords}
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title pull-center">{t('搜索')}</div>
		<form action="{u('member/admin/index')}" class="searchbar input-group" method="get" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>		
		{else}	
		<div class="title">{$title}</div>

		<form action="{u('member/admin/index')}" class="searchbar input-group" method="get" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>
		
		<div class="action">
			{if count($models) == 1}
				{loop $models $m}
				<a class="btn btn-primary" href="{u('member/admin/add/'.$m['id'])}"> <i class="fa fa-plus fa-fw"></i> <span>{t('添加%s',$m['name'])}</span></a>
				{/loop}
			{else}
			<div class="btn-group">
				<a class="btn btn-primary btn-icon-text dropdown-toggle" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-plus"></i> <b>{t('添加')}</b> <i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
				{loop $models $m}
					{if !$m['disabled']}
					<li><a href="{u('member/admin/add/'.$m['id'])}" data-placement="right" title="{$m['description']}">{t('添加%s',$m['name'])}</a></li>
					{/if}
				{/loop}
				</ul>
			</div>			
			{/if}
		</div>
		{/if}

	</div>
	{form::header()}
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		<table class="table table-hover table-nowrap list">
			<thead>
				<tr>
					<td class="select"><input type="checkbox" class="checkbox select-all"></td>
					<td class="text-center" width="40">{t('状态')}</td>
					<td>{t('用户名')}</td>
					<td>{t('昵称')}</td>
					{if count($models) > 1}
					<td>{t('会员模型')}</td>
					{/if}
					<td>{t('会员组')}</td>
					<td>{t('积分')}</td>
					<td>{t('金钱')}</td>
					<td>{t('手机')}</td>
					<td>{t('邮箱')}</td>
					<td>{t('最后登录')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
					<td class="text-center">	{if $r['disabled']}<i class="fa fa-times-circle fa-2x text-muted" title="{t('已禁用')}"></i>{else}<i class="fa fa-check-circle fa-2x text-success" title="{t('正常')}"></i>{/if}</td>
					<td>
						<div class="title text-overflow">{$r['username']}</div>
						<div class="manage">
							<a href="{u('member/admin/edit/'.$r['id'])}"><i class="fa fa-edit"></i> {t('编辑')}</a>
							<s>|</s>
							<a href="{u('member/admin/delete/'.$r['id'])}" class="js-confirm"><i class="fa fa-times"></i> {t('删除')}</a>
						</div>
					</td>
					<td>{$r.nickname}</td>
					{if count($models) > 1}
					<td>{$models[$r['modelid']]['name']}</td>
					{/if}
					<td>{$groups[$r['groupid']]['name']}</td>
					<td>{$r['point']}</td>
					<td>{$r['amount']}</td>
					<td>
						{if $r['mobile']}
							{$r['mobile']}

							{if $r['mobilestatus']}
							<i class="fa fa-check-circle text-success" data-toggle="tooltip" title="{t('已验证')}"></i>
							{else}
							<i class="fa fa-times-circle text-muted" data-toggle="tooltip"  title="{t('未验证')}"></i>
							{/if}								
						{else}
							<span class="text-muted"></span>
						{/if}
					</td>
					<td>
						{if $r['email']}
							{$r['email']}

							{if $r['emailstatus']}
							<i class="fa fa-check-circle text-success" data-toggle="tooltip" title="{t('已验证')}"></i>
							{else}
							<i class="fa fa-times-circle text-muted" data-toggle="tooltip"  title="{t('未验证')}"></i>
							{/if}								
						{else}
							<span class="text-muted"></span>
						{/if}
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

		<a class="btn btn-default operate" href="{u('member/admin/operate/disabled/1')}" rel="status">{t('禁用')}</a>
		<a class="btn btn-default operate" href="{u('member/admin/operate/disabled/0')}" rel="status">{t('启用')}</a>
		<a class="btn btn-default operate" href="{u('member/admin/operate/move')}" rel="move" style="display:none;">{t('移动')}</a>
		<a class="btn btn-default operate" href="{u('member/admin/operate/delete')}" rel="delete">{t('删除')}</a>

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