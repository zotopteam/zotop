{template 'dialog.header.php'}

<div class="container-fluid container-primary">
	<div class="jumbotron">
		<div class="media-left">
			<img class="media-object" src="{ZOTOP_URL_APPS}/{$dir}/app.png"/>
		</div>
		<div class="media-body">
			<h2 class="media-heading">{t('安装 “%s” 到您的系统中',$app['name'])}</h2>
			<div class="media-description">{t('将该应用安装到您的系统之后，您的网站就可以获得该应用提供的功能')}</div>					
		</div>
	</div>				
</div>

<div class="container-fluid container-default scrollable">
	{form::header()}		
		<table class="table table-border rounded">
			<tbody>
				<tr>
					<td colspan="2">
						<p class="nomargin">{$app['description']}</p>						
					</td>
				</tr>
				<tr>
					<td class="w60">{t('版本')} :</td>
					<td>{$app['version']}</td>
				</tr>
				<tr>
					<td class="w60">{t('作者')} :</td>
					<td>{$app['author']}</td>
				</tr>
				{if $app['homepage']}
				<tr>
					<td class="w60">{t('网站')} :</td>
					<td><a target="_blank" href="{$m['homepage']}">{$app['homepage']}</a></td>
				</tr>
				{/if}
			</tbody>
		</table>
	{form::footer()}
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