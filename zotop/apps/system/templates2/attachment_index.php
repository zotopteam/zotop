{template 'header.php'}

<div class="side side-main">
	<div class="side-header">
		{t('附件')}
	</div><!-- side-header -->
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			<li>
				<a href="{u('system/attachment/index')}" {if $folderid==0} class="current"{/if}>
					<i class="icon icon-folder"></i> {t('全部附件')}
				</a>
			</li>
			{loop m('system.attachment_folder.category') $f}
			<li>
				<a href="{u('system/attachment/index/'.$f['id'])}" {if $folderid==$f['id']} class="current"{/if}>
					<i class="icon icon-folder"></i> {$f['name']}
				</a>
			</li>
			{/loop}
			<li class="blank"></li>
			<li>
				<a href="{u('system/attachment/folder')}">
					<i class="icon icon-category"></i>  {t('分类管理')}
				</a>
			</li>
		</ul>
	</div><!-- side-body -->
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			{if $folderid}
			<a href="{u('system/attachment/index')}">全部附件</a>
			<s class="arrow">></s>
			{m('system.attachment_folder.category', $folderid, 'name')}
			{/if}
		</div>
		<div class="action">
			<a id="upload" class="btn btn-icon-text btn-highlight" href="javascript:;">
				<i class="icon icon-upload"></i> <b>{t('附件上传')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	{if empty($data)}
	<div class="nodata">{t('暂时没有任何数据')}</div>
	{else}
	<div class="main-body scrollable">

		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="select"><input type="checkbox" class="checkbox select-all"></td>
				<td class="w80"></td>
				<td>{t('名称')}</td>
				<td class="w100">{t('大小')}</td>
				<td class="w100">{t('分类')}</td>
				<td class="w120">{t('上传时间')}</td>
			</tr>
		</thead>
		<tbody>
			{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td class="center">
					{if in_array($r['ext'], array('jpg','jpeg','png','gif','bmp'))}
						<div class="image-preview tooltip-block">
							<div class="thumb"><img src="{$r['url']}"></div>
							<div class="tooltip-block-content"><img src="{$r['url']}" class="preview"></div>
						</div>
					{else}
						<b class="icon icon-ext icon-{$r['type']} icon-{$r['ext']} f48"></b>
					{/if}
				</td>
				<td class="vt">
					<div class="title textflow">
						{$r['name']}
					</div>
					<div class="description textflow">
						<span>{strtoupper($r['ext'])}</span>
						{if $r['width'] and $r['height']}<span> <cite class="nowrap green">{$r['width']}px × {$r['height']}px </cite></span>{/if}
						<span>{$r['description']}</span>
					</div>
					<div class="manage">
						<a href="{u('system/attachment/download/'.$r['id'])}">{t('下载')}</a>
						<s></s>
						<a class="dialog-prompt" data-value="{$r['name']}" data-prompt="{t('请输入文件名称')}" href="{u('system/attachment/edit/name/'.$r['id'])}">{t('重命名')}</a>
						<s></s>
						<a class="dialog-prompt" data-value="{$r['description']}" data-prompt="{t('请输入文件描述')}" href="{u('system/attachment/edit/description/'.$r['id'])}">{t('备注')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/attachment/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
				<td>{format::size($r['size'])}</td>
				<td>{m('system.attachment_folder.category', $r.folderid, 'name')}</td>
				<td>
					<div class="userinfo" role="{$r.userid}">{m('system.user.get', $r.userid, 'username')}</div>
					<div class="f12 time">{format::date($r['uploadtime'])}</div>
				</td>
			</tr>
			{/loop}
		</tbody>
		</table>
		
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">

		<a class="btn operate" href="{u('system/attachment/operate/delete')}">{t('删除')}</a>
	</div><!-- main-footer -->
	{/if}
</div><!-- main -->

<div id="upload-progress" class="total-progressbar progressbar"><span class="progress"></span><span class="percent">0%</span></div>

{form::footer()}

<style type="text/css">
	div.image-preview{padding:2px;background:#fff;border:solid 1px #ebebeb;display:inline-block;line-height:0;}
	div.image-preview .thumb {display:table-cell;vertical-align:middle;text-align:center;width:62px;height:62px;background:#eee;*display:block;*font-size:62px; }
	div.image-preview .thumb img {vertical-align:middle;max-width:62px;max-height:62px;}

	div.total-progressbar{position:absolute;width:400px;top:6px;right:150px;background: #fff;display: none;}

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

<script type="text/javascript" src="{A('system.url')}/common/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/jquery.upload.js"></script>
<script type="text/javascript">
	$(function(){
		var uploader = $("#upload").upload({
			url : "{u('system/attachment/uploadprocess')}",
			multi:true,
			params:{folderid:{$folderid}},
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