{template 'dialog.header.php'}

<div class="main">
	<div class="main-header">
		
		{if $keywords}
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title pull-center">{t('搜索 "%s"',$keywords)}</div>
		{else}		
		<div class="title">{if $title} {$title} {else} {m('content.content.status',$status)} {/if}</div>
		{/if}

		<form action="{request::url()}" class="searchbar input-group" method="get" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>


	</div><!-- main-header -->

	<div class="main-body scrollable">

		{if empty($data)}		
		<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}

		{form class="nomargin"}

		{field type="hidden" name="blockid" value="$block.id" required="required"}

		<table class="table table-hover table-nowrap list" id="datalist">
		<thead>
			<tr>
				<td class="select"><input type="checkbox" class="checkbox select-all"></td>
				<td colspan="2" class="title condensed">{t('标题')}</td>
				<td class="hits text-center hidden-xs hidden-sm" width="80">{t('点击')}</td>
				<td class="category hidden-xs hidden-sm">{t('栏目')}</td>
				<td class="datetime hidden-xs hidden-sm">{t('发布者/发布时间')}</td>
			</tr>
		</thead>
		<tbody>

		{loop $data $r}
			<tr data-id="{$r.id}" data-categoryid="{$r.categoryid}" data-listorder="{$r.listorder}" data-stick="{$r.stick}" >
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r.id}"></td>
				<td class="model text-center condensed vm" width="5%">
					{if $r.image}
					<div class="image image-icon">
						<img src="{$r.image width="50" height="50"}" alt="{t('缩略图')}" title="{m('content.model.get',$r.modelid,'name')}" class="img-responsive img-rounded">					
					</div>
					{else}
					<i class="fa fa-{$r.modelid} {m('content.model.get',$r.modelid,'icon')} fa-2x text-{$r.modelid}" title="{m('content.model.get',$r.modelid,'name')}"></i>
					{/if}
				</td>
				<td class="title condensed">
					<p class="title" {if $r.style}style="{$r.style}"{/if}>					

						{$r.title}

						{if $r.image} 
						<i class="fa fa-image text-success tooltip-block hidden" data-placement="bottom">
							<span class="tooltip-block-content"><img src="{$r.image}" class="preview"></span>
						</i> 
						{/if}
					
						{if $r.stick}
						<i class="fa fa-arrow-up text-success" data-toggle="tooltip" title="{t('置顶')}"></i>
						{/if}

						{if $f = C('content.newflag') }
							{if (ZOTOP_TIME - $r[$f]) <= C('content.newflag_expires') * 3600}<i class="new">{t('新')}</i>{/if}
						{/if}
					</p>
				</td>
	
				<td class="hits text-center hidden-xs hidden-sm">{$r.hits}</td>
				<td class="category hidden-xs hidden-sm" width="100px">
					<div class="text-overflow">{m('content.category.get',$r.categoryid,'name')}</div>
				</td>
				<td class="datetime hidden-xs hidden-sm">
					<div class="userinfo" role="{$r.userid}">{m('system.user.get', $r.userid, 'username')}</div>
					<div class="time">{$r.createtime date="datetime"}</div>
				</td>
			</tr>
		{/loop}
		
		</tbody>
		</table>
		{/form}

		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		{if $data}
			{pagination::instance($total,$pagesize,$page)}
		{/if}
	</div><!-- main-footer -->

</div><!-- main -->

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){

		var tablelist = $('#datalist').data('tablelist');

		if( tablelist.checked() == 0 ){
			$.error('{t('请选择要选取的项')}');
			return false;
		}

		$('form.form').submit();
		return false;
	};

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();

			$.post(action, data, function(datalist){

				$.post('{u('block/datalist/insert/'.$block.id)}',{app:'content',datalist:datalist},function(msg){
					
					if( msg.state ){
						parent.location.reload();
						$dialog.close();
					}

					$.msg(msg);					

				},'json');

			},'json');
		}});
	});

</script>
{template 'dialog.footer.php'}