{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="nav nav-tabs tabdropable">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="active"{/if}><a href="{$n.href}"><i class="{$n.icon}"></i> <span>{$n.text}</span></a></li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<div class="container-fluid container-primary">
			<div class="jumbotron text-center">
				<h1><i class="fa fa-upload"></i> {t('上传新应用')}</h1>
				<p>{t('如果您有 .zip 格式的应用文件，可以在下面上传，上传完成后可以在未安装应用里面找到它')}</p>
			</div>
		</div>

		<div class="container-fluid">

			<div id="upload" class="upload-zone" data-ride="plupload" data-url="{u('system/app/uploadprocess')}" data-exts="zip" data-description="zip file" data-maxsize="20mb" data-multiple="false" data-progress-bar="#upload-progress">
					
					<button  class="btn btn-primary upload-button">
						<i class="fa fa-upload fa-fw"></i>{t('点击上传')}
					</button>

					<p class="upload-dragdrop-tips none">{t('点击上传或者拖到此区域上传')}</p>
			</div>

			<div class="blank"></div>

			<div id="upload-progress" class="progress">
				<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:1%">
				0%
				</div>
			</div>

		</div>

	</div><!-- main-body -->
	<div class="main-footer text-overflow">
		<div class="footer-text">
		{t('只支持 .zip 格式的应用安装包')}
		</div>
	</div><!-- main-footer -->
</div>

<script type="text/javascript" src="{A('system.url')}/assets/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/jquery.upload.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/i18n/zh_cn.js"></script>

<script type="text/javascript">
	$(function(){
		var uploader = $("#upload").upload({
			url : "{u('system/app/uploadprocess')}",
			multi:false,
			maxsize:'20mb',
			fileext: 'zip',
			filedescription : 'zip file',
			progress : function(up,file){
				up.self.html('{t('上传中……')}' +up.total.percent + '%');
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
{template 'footer.php'}