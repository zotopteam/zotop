{template 'dialog.header.php'}
<div class="main scrollable">

	<div class="container-fluid container-primary">
		<div class="jumbotron">
			<div class="media-left">
				<img class="media-object" src="{ZOTOP_URL_APPS}/{$app['dir']}/app.png"/>
			</div>
			<div class="media-body">
				<h2 class="media-heading">{t('您确定要卸载 “%s” ?',$app['name'])}</h2>
				<div class="media-description">{t('卸载后，您将无法使用继续使用该应用，该应用的数据会被全部删除，并且无法被恢复')}</div>					
			</div>
		</div>				
	</div>

	<div class="container-fluid container-default scrollable">
		{form::header()}
		<table class="table table-condensed">
			<tbody>
				<tr>
					<td>{t('您可以使用 <b class="text-important">禁用</b> 功能暂时关闭该应用')}</td>
				</tr>
				<tr>
					<td>{t('警告：<b class="text-important">卸载程序可能会删除该应用全部数据</b>，强烈建议您在卸载之前备份该应用的全部数据')}</td>
				</tr>
				<tr>
					<td>{t('卸载后，您可以从 未安装应用 中找到该应用，并重新安装该应用')}</td>
				</tr>
				<tr>
					<td>{t('如果您想完全删除该应用，请删除应用目录下的 “%s” 文件夹',$app['dir'])}</td>
				</tr>						
			</tbody>
		</table>
		{form::footer()}
	</div>

</div>
<style type="text/css">
.container-primary{height: 180px;overflow: hidden;}
.container-default{position:absolute;top:180px;bottom:0;right:0;left:0;}
.media-object{width:60px;height: 60px;margin-right: 10px;}
</style>

<script type="text/javascript">
	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	// 表单验证

	$(function(){
		$('form.form').validate({submitHandler:function(){
			var action = $(this).attr('action');
			var data = $('form').serialize();
			$.loading();
			$.post(action,data,function(msg){
				$.msg(msg);
				if( msg.state ){
					$dialog.close();
				}

			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}