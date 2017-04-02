{template 'header.php'}
{template 'block/admin_side.php'}

<div class="main side-main">
	<div class="main-header">

		{if $keywords}
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$block.name} - {$title} - {t('搜索 "%s"',$keywords)}</div>
		{else}
		<div class="goback"><a href="{U('block/admin/data/'.$block.id)}"><i class="fa fa-angle-left"></i><span>{$block.name}</span></a></div>
		<div class="title">{$block.name} - {$title} </div>
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
				<tr>
					<td>{t('标题')}</td>
					<td class="text-right" width="200">{t('操作')}</td>
				</tr>
			</tr>
		</thead>
		<tbody>

		{loop $data $r}
				<tr>
					<td>
						<input type="hidden" name="id[]" value="{$r.id}">
						<div class="title text-overflow">
							{if $r.url}
								<a href="{U($r.data.url)}" target="_blank" {if $r.data.style}style="{$r.data.style}"{/if}>{$r.data.title}</a>
							{else}
								<span {if $r.data.style}style="{$r.data.style}"{/if}>{$r.data.title}</span>
							{/if}

							{if $r.data.image} 
								<i class="fa fa-image text-success tooltip-block" data-placement="bottom">
									<div class="tooltip-block-content"><img src="{$r.data.image}" class="preview"></div>
								</i> 
							{/if}								
							{if $r.dataid}<i class="fa fa-share-alt text-success" title="{t('关联数据')}"></i> {/if}
							{if $r.stick}<i class="fa fa-arrow-up text-success" title="{t('已置顶')}"></i> {/if}
						</div>
					</td>
					<td class="manage text-right">					
							{if $r.url}
							<a href="{U($r.url)}" target="_blank"><i class="fa fa-eye"></i> {t('查看')}</a>
							<s>|</s>
							{/if}
							<a href="{U('block/datalist/back/'.$r.id)}" class="js-ajax-post"><i class="fa fa-reply"></i> {t('重新推荐')}</a>
							<s>|</s>
							<a href="{U('block/datalist/edit/'.$r.id)}" data-width="800px" data-height="400px" class="js-open"><i class="fa fa-edit"></i> {t('编辑')}</a>
							<s>|</s>
							<a href="{U('block/datalist/delete/'.$r.id)}" class="js-confirm"><i class="fa fa-times"></i> {t('删除')}</a>
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
		$('form.form').submit();
		return false;
	};

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action, data, function(msg){

				if( msg.state ){
					parent.location.reload();
					$dialog.close();
				}

				$.msg(msg);

			},'json');
		}});
	});

</script>
{template 'footer.php'}