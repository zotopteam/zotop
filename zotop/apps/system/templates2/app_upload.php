{template 'header.php'}
<div class="side">
	{template 'system/system_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}><a href="{$n['href']}">{$n['text']}</a></li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<div class="main-body-title">
			{t('如果您有 .zip 格式的应用文件，可以在这里上传，上传完成后可以在未安装应用里面找到它')}
		</div>

		<div id="upload" style="height:200px;line-height:200px;margin:20px 0;text-align:center;border:solid 2px #ebebeb;cursor:pointer;font-size:18px;">
				<i class="icon icon-upload"></i>
				<b class="undragdrop">{t('点击上传')}</b>
				<b class="dragdrop none">{t('点击上传或者拖到此区域上传')}</b>
		</div>

		<div id="upload-progress" class="progressbar none"><span class="progress"><b class="percent">20%</b></span></div>

	</div><!-- main-body -->
	<div class="main-footer textflow">
		<div class="tips">
		{t('只支持 .zip 格式的应用安装包')}
		</div>
	</div><!-- main-footer -->
</div>

<script type="text/javascript" src="{A('system.url')}/common/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/jquery.upload.js"></script>
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