{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary" href="javascript:;" id="upload">
				<i class="fa fa-upload fa-fw"></i><b>{t('上传新主题')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{if empty($themes)}
			<div class="nodata">
				{t('暂时没有可用主题，你可以上传或者安装一个主题')}
			</div>			
		{else}
			<div class="container-fluid">
				{loop $themes $id $theme}
				<div class="media theme">
					<div class="media-left">
						<div class="image"><img src="{$theme['image']}"></div>
					</div>

					<div class="media-body">
						<div class="media-heading name"><b>{$theme['name']}</b> <span>v{$theme['version']}</span></div>
						<div class="author">
							<span>{t('作者')} <a href="{$theme['homepage']}" target="_blank">{$theme['author']}</a></span>
							<span>{t('邮件')} <a href="mailto:{$theme['email']}">{$theme['email']}</a></span>
							<span>{t('网站')} <a href="{$theme['homepage']}" target="_blank">{$theme['homepage']}</a></span>
						</div>
						<div class="description">{$theme['description']}</div>
						<div class="action">
							<a href="{U('system/theme/template','theme='.$id)}">{t('模板管理')}</a>
							<s></s>
							{if in_array($id, $current)}
							<b class="true" href="javascript:;"><i class="icon icon-true true"></i> {t('使用中')}</b>
							{else}
							<a class="dialog-confirm" href="{U('system/theme/delete/'.$id)}">{t('删除')}</a>
							{/if}
						</div>					
					</div>
				</div>
				{/loop}
			</div>
		{/if}


	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">
			{t('主题和模版决定了站点的外观，你可以修改网站已有主题或者上传一个新的主题（只支持.zip格式主题包）')}
		</div>
	</div><!-- main-footer -->
</div><!-- main -->

<style type="text/css">
.theme{padding:10px 0;}
.theme div.image{margin-right:20px;width: 300px;height: 220px;overflow: hidden;}
.theme div.image img{width:100%;border:solid 1px #ebebeb;background:#f7f7f7;padding:5px;}
.theme div.name{margin-bottom:15px;}
.theme div.name b{font-size:26px;font-weight:normal;}
.theme div.name span{padding-left:10px;}
.theme div.author{margin-bottom:15px;}
.theme div.description{line-height:22px;height:65px;overflow:hidden;}
.theme div.action{margin-top:15px;}
.theme div.action s{display:inline-block;font-size:0;overflow:hidden;line-height:0;width:1px;height:12px;background:#CCC;margin:-2px 5px 0 5px;vertical-align:middle;}
.theme div.action a{font-size:14px;}
</style>

<script type="text/javascript" src="{A('system.url')}/assets/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/jquery.upload.js"></script>
<script type="text/javascript">
	$(function(){
		var uploader = $("#upload").upload({
			url :"{u('system/theme/uploadprocess')}",
			//runtimes :'flash',
			multi:false,
			dragdrop:false,
			maxsize:'20mb',
			fileext:'zip',
			filedescription :'zip file',
			progress :function(up,file){
				up.self.html('{t('上传中……')}' +up.total.percent + '%');
			},
			uploaded :function(up,file,data){
				$.msg(data);
			},
			complete :function(up,files){
				up.self.html(up.content);
			},
			error :function(error,detail){
				$.error(detail);
			}
		});
	});
</script>

{template 'footer.php'}