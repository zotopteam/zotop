{template 'header.php'}
<div class="side">
	<div class="side-header">{t('单元测试')}</div>
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			{loop $navlist $nav}
			<li><a href="{$nav[href]}"{if request::url(false)==$nav['href']} class="current"{/if}><b>{$nav[text]}</b></a></li>
			{/loop}
		<ul>
	</div>
</div>
<div class="main side-main no-footer">
	<div class="main-header"><div class="title">{$title}</div></div>
	<div class="main-body scrollable">

		<div style="margin:20px;">
			<div>
				<a class="btn btn-highlight btn-upload" id="upload-select" href="javascript:void(0)">
					{t('上传%s', $typename)}
				</a>
				<a class="btn btn-upload-start disabled" id="upload-start" href="javascript:void(0)">
					{t('开始上传')}
				</a>
				<a class="btn btn-upload-stop disabled" id="upload-stop" href="javascript:void(0)">
					{t('停止上传')}
				</a>
			</div>

			<div class="filelist-body"  id="filelist" style="margin-top:10px;">

			</div>
		</div>

		<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/plupload/plupload.css" />
		<script type="text/javascript" src="{A('system.url')}/common/plupload/plupload.full.js"></script>
		<script type="text/javascript" src="{A('system.url')}/common/plupload/jquery.uploader.js"></script>
		<script type="text/javascript" src="{A('system.url')}/common/plupload/i18n/zh_cn.js"></script>
		<script type="text/javascript">
			$(function(){
				var uploader = $("#filelist").plupload({
					url : "{u('test/index/uploadprocess')}",
					runtimes : 'flash',
					//autostart:true,
					//max_file_count:2,
					//max_file_size:'10KB',
					chunk_size : '10KB',
					filters : [{title : "Image files", extensions : "jpeg,jpg,gif,png"}]
				});
			});
		</script>
	</div>
</div>
{template 'footer.php'}