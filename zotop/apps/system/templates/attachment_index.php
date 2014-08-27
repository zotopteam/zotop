{template 'header.php'}
<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/attachment.css" />
<div class="side main-side">
	<div class="side-header">
		{t('附件分类')}
		<div class="action">
			<a href="{u('system/attachment/folder')}">
				{t('分类管理')}
			</a>
		</div>
	</div><!-- side-header -->
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			<li {if $folderid==0} class="current"{/if}>
				<a href="{u('system/attachment/index/list')}">
					<i class="icon icon-folder"></i> {t('全部附件')}
				</a>
			</li>
			{loop $folders $f}
			<li {if $folderid==$f['id']} class="current"{/if}>
				<a href="{u('system/attachment/index/list/'.$f['id'])}">
					<i class="icon icon-folder"></i> {$f['name']}
				</a>
			</li>
			{/loop}
		</ul>
	</div><!-- side-body -->
</div>

{form::header()}
<div class="main main-side">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			{if $folderid}
			<a href="{u('system/attachment/index/list')}">全部附件</a>
			<s class="arrow">></s>
			{$folders[$folderid]['name']}
			{/if}
		</div>
		<div class="action">
			<a id="upload" class="btn btn-highlight" href="javascript:;">
				{t('附件上传')}
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="select"><input type="checkbox" class="checkbox select-all"></td>
				<td class="w50"></td>
				<td>{t('名称')}</td>
				<td class="w100">{t('大小')}</td>
				<td class="w100">{t('分类')}</td>
				<td class="w120">{t('上传时间')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td class="center">
					{if in_array($r['ext'],array('jpg','jpeg','png','gif','bmp'))}
						<div class="image-preview" data-src="{$r['url']}">
							<div class="thumb"><img src="{$r['url']}"></div>
						</div>
					{else}
						<b class="icon-ext icon-{$r['type']} icon-{$r['ext']}"></b>
					{/if}
				</td>
				<td class="vt">
					<div class="title">
						{$r['name']}
						{if $r['width'] and $r['height']}
						<span> <cite class="nowrap green">{$r['width']}px × {$r['height']}px </cite></span>
						{/if}
						<span>{$r['description']}</span>
					</div>
					<div class="manage">
						<a href="{u('system/attachment/download/'.$r['id'])}">{t('下载')}</a>
						<s></s>
						<a class="dialog-prompt" data-value="{$r['name']}" data-prompt="{t('请输入分类名称')}" href="{u('system/attachment/edit/name/'.$r['id'])}">{t('重命名')}</a>
						<s></s>
						<a class="dialog-prompt" data-value="{$r['description']}" data-prompt="{t('请输入分类名称')}" href="{u('system/attachment/edit/description/'.$r['id'])}">{t('备注')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/attachment/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
				<td>{format::size($r['size'])}</td>
				<td>{$folders[$r['folderid']]['name']}</td>
				<td>
					{$r['uploadip']}
					<div class="f12 time">{format::date($r['uploadtime'])}</div>
				</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">

		<a class="btn operate" href="{u('system/attachment/operate/delete')}">{t('删除')}</a>
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
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

$(function(){
	$( '.image-preview' ).tooltip({placement:'auto bottom',container:'body',html:true,title:function(){
		return '<img src="'+$(this).attr('data-src')+'" style="max-width:400px;"/>';
	}});
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
			params:{
				folderid : {$folderid}
			},
			maxsize:'20mb',
			fileext: '{$allowexts}',
			filedescription : '{t('选择文件')}',
			progress : function(up,file){
				up.self.html('{t('上传中……')}' +up.total.percent + '%');
			},
			complete : function(up,files){
				up.self.html(up.content);
				location.reload();
			},
			error : function(error,detail){
				$.error(detail);
			}
		});
	});
</script>
{template 'footer.php'}