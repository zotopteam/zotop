{template 'header.php'}

<div class="side side-main">
	<div class="side-header">
		<div class="title">{t('附件')}</div>
	</div><!-- side-header -->
	<div class="side-body scrollable">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li {if $folderid==0} class="active"{/if}>
				<a href="{u('system/attachment/index')}">
					<i class="fa fa-folder fa-fw"></i><span>{t('全部附件')}</span>
				</a>
			</li>
			{loop m('system.attachment_folder.category') $f}
			<li{if $folderid==$f['id']} class="active"{/if}>
				<a href="{u('system/attachment/index/'.$f['id'])}" >
					<i class="fa fa-folder fa-fw"></i> <span>{$f['name']}</span>
				</a>
			</li>
			{/loop}
			<li class="blank"></li>
			<li>
				<a href="{u('system/attachment/folder')}">
					<i class="fa fa-sitemap fa-fw"></i><span>{t('分类管理')}</span>
				</a>
			</li>
		</ul>
	</div><!-- side-body -->
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a id="upload" class="btn btn-primary" href="javascript:;">
				<i class="fa fa-upload fa-fw"></i> <b>{t('附件上传')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable" id="upload-dragdrop">
		{if empty($data)}
			<div class="nodata">
				<i class="fa fa-frown-o"></i>
				<h1>
					{t('暂时没有任何数据')}
				</h1>
			</div>
		{else}

		{form}
		<table class="table table-hover table-nowrap list">
		<thead>
			<tr>
				<th class="select"><input type="checkbox" class="checkbox select-all"></th>
				<th class="text-center" width="40"></th>
				<th>{t('名称')}</th>
				<th class="hidden-xs">{t('大小')}</th>
				<th class="hidden-xs">{t('类型')}</th>
				<th class="hidden-xs">{t('分类')}</th>
				<th class="hidden-xs">{t('上传时间')}</th>
			</tr>
		</thead>
		<tbody>
			{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td class="text-center" width="40">
					{if in_array($r['ext'], array('jpg','jpeg','png','gif','bmp'))}
						<div class="image-preview tooltip-block">
							<div class="thumb"><img src="{$r['url']}"></div>
							<div class="tooltip-block-content"><img src="{$r['url']}" class="preview"></div>
						</div>					
					{else}
						<b class="fa fa-file-o fa-{$r['type']} fa-{$r['ext']} fa-4x text-primary"></b>
					{/if}
				</td>
				<td>
					<div class="title text-overflow">
						{$r['name']}
					</div>
					<div class="description text-overflow">
						<span>{t('格式')}：{strtoupper($r['ext'])}</span>

						{if $r['width'] and $r['height']}
							&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-success">{t('宽高')}：{$r['width']}px × {$r['height']}px</span>
						{/if}

						<span>&nbsp;&nbsp;&nbsp;&nbsp;{t('说明')}：{$r['description']}</span>
					</div>
					<div class="manage">
						<a href="{u('system/attachment/download/'.$r['id'])}"><i class="fa fa-download fa-fw"></i> {t('下载')}</a>
						<s>|</s>
						<a class="js-prompt" data-value="{$r['name']}" data-prompt="{t('请输入文件名称')}" href="{u('system/attachment/edit/name/'.$r['id'])}"><i class="fa fa-edit fa-fw"></i> {t('重命名')}</a>
						<s>|</s>
						<a class="js-prompt" data-value="{$r['description']}" data-prompt="{t('请输入文件描述')}" href="{u('system/attachment/edit/description/'.$r['id'])}"><i class="fa fa-download fa-fw"></i> {t('备注')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('system/attachment/delete/'.$r['id'])}"><i class="fa fa-trash fa-fw"></i> {t('删除')}</a>
					</div>
				</td>
				<td class="hidden-xs">{format::size($r['size'])}</td>
				<td class="hidden-xs">
					{if $r.folderid}
					{m('system.attachment_folder.category', $r.folderid, 'name')}
					{else}
					{t('----')}
					{/if}
				</td>
				<td class="hidden-xs">{m('system.attachment.types', $r.type)}</td>
				<td class="hidden-xs">
					<div class="js-userinfo" data-userid="{$r.userid}">{m('system.user.get', $r.userid, 'username')}</div>
					<div class="text-muted">{format::date($r['uploadtime'])}</div>
				</td>
			</tr>
			{/loop}
		</tbody>
		</table>
		
	</div><!-- main-body -->
	<div class="main-footer">
		<input type="checkbox" class="checkbox select-all">
		<a class="btn btn-default operate" href="{u('system/attachment/operate/delete')}">{t('删除')}</a>
		{pagination::instance($total,$pagesize,$page)}
	</div><!-- main-footer -->
	{/form}
	{/if}
</div><!-- main -->

<div id="upload-progress" class="progress hidden">
	<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
		<span class="progress-percent">0%</span>
	</div>
</div>

<style type="text/css">
	div.image-preview{padding:2px;background:#fff;border:solid 1px #ebebeb;display:inline-block;line-height:0;}
	div.image-preview .thumb {display:table-cell;vertical-align:middle;text-align:center;width:62px;height:62px;background:#eee;*display:block;*font-size:62px; }
	div.image-preview .thumb img {vertical-align:middle;max-width:62px;max-height:62px;}
	div.progress{position:absolute;width:400px;top:22px;right:150px;background: #fff;}
</style>

<script type="text/javascript">
$(function(){
	var tablelist = $('table.list').data('tablelist');

	//底部全选
	$('input.select-all').click(function(e){
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
				var data = $('form').serializeArray();
					data.push({name:'operation',value:text});
				$.loading();
				$.post(href,$.param(data),function(msg){
					msg.state && location.reload();
					$.msg(msg);
				},'json');
			}
		});
	});
});

</script>

<script type="text/javascript" src="{A('system.url')}/assets/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/jquery.upload.js"></script>
<script type="text/javascript">
	$(function(){
		var uploader = $("#upload").upload({
			url : "{u('system/attachment/uploadprocess')}",
			multiple:true,
			params:{folderid:'{intval($folderid)}'},
			maxsize:'20mb',
			fileext: '{$allowexts}',
			filedescription : '{t('选择文件')}',
			uploaded: function(up,file,msg){
				$.msg(msg);
			},
			complete: function(up,files){
				location.reload();
				
			},
			error: function(error,detail){
				$.error(detail);
			}
		});
	});
</script>
{template 'footer.php'}