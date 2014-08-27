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

		<div style="display:none;">

			<a class="btn btn-upload-start disabled" id="upload-start" href="javascript:void(0)">
				{t('开始上传')}
			</a>
			<a class="btn btn-upload-stop disabled" id="upload-stop" href="javascript:void(0)">
				{t('停止上传')}
			</a>
		</div>


		<div id="upload-select" style="height:200px;line-height:200px;margin:20px 0;text-align:center;border:solid 2px #ebebeb;cursor:pointer;font-size:14px;">
				<i class="icon icon-upload"></i>
				<b class="undragdrop">{t('点击上传')}</b>
				<b class="dragdrop none">{t('点击上传或者将图片拖到此区域上传')}</b>
		</div>

		<div id="upload-select-progress" class="progressbar none"><span class="progress"><b class="percent">20%</b></span></div>

		<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/plupload/plupload.css" />
		<script type="text/javascript" src="{A('system.url')}/common/plupload/plupload.full.js"></script>
		<script type="text/javascript" src="{A('system.url')}/common/plupload/i18n/zh_cn.js"></script>
		<script type="text/javascript" src="{A('system.url')}/common/plupload/jquery.upload.js"></script>
		<script type="text/javascript">
			$(function(){
				var uploader = $("#upload-select").upload({
					url : "{u('test/index/uploadprocess')}",
					//runtimes : 'html4',
					multi:true,
					maxsize:'2mb',
					fileext: 'jpeg,jpg,gif,png',
					filedescription : "Image files",
					progress : function(up,file){
						up.self.html('上传中：' +up.total.percent + '%');
					},
					uploaded : function(up,file,data){
						$.msg(data);
					},
					complete : function(up,files){
						up.self.html(up.content);
					},
					error : function(error,detail){
						$.error(detail);
					}
				});
			});
		</script>
	</div>
</div>
{template 'footer.php'}