{template 'dialog.header.php'}
<div class="main">
		<div class="main-header">
			<div class="logo"><img src="{ZOTOP_URL_APPS}/{$dir}/app.png"/></div>
			<div class="title">{t('安装 “%s” 应用到您的系统中',$data['name'])}</div>
			<div class="description">{t('将该应用安装到您的系统之后，您的网站就可以获得该应用提供的功能')}</div>
		</div>
		<div class="main-body scrollable">
		{form::header()}

				<table class="table">
					<tr>
						<td colspan="2" height="60px">{$data['description']}</td>
					</tr>
					<tr>
						<td class="w60">{t('版本')} :</td>
						<td>{$data['version']}</td>
					</tr>
					<tr>
						<td class="w60">{t('作者')} :</td>
						<td>{$data['author']}</td>
					</tr>
					{if $data['homepage']}
					<tr>
						<td class="w60">{t('网站')} :</td>
						<td><a target="_blank" href="{$m['homepage']}">{$data['homepage']}</a></td>
					</tr>
					{/if}
				</table>

		{form::footer()}
		</div>
</div>
<style type="text/css">
.main-header{height:70px;max-height:150px;background: #e5f3fb;padding:40px 20px;line-height:30px;}
.main-body{top:150px;bottom:0;padding:20px 40px;}

.main-header div.logo{float:left;width:100px;height:100%;text-align:center;}
.main-header div.logo img{width:64px;height:64px;}
.main-header div.title{font-size:28px;padding:0px;margin-top:-5px;width:600px;}
</style>
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
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