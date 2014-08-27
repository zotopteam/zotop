{template 'dialog.header.php'}
{form::header()}
<div class="main no-footer">
	<div class="main-header">
		<div class="title">{$role['name']}-{t('成员管理')}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<table class="table zebra list">
		<thead>
			<tr>
			<td class="w40 center"><input type="checkbox" name="select-all" class="select-all"></td>
			<td>{t('用户名')}</td>
			<td class="w120">{t('真实姓名')}</td>
			<td class="w160">{t('电子邮件')}</td>
			<td class="w160">{t('最后登陆')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $dataset $data}
			<tr{if $role['id']==$data['roleid']} class="selected"{/if}>
				<td class="center"><input type="checkbox" name="id[]" class="select" value="{$data['id']}"{if $role['id']==$data['roleid']} checked="checked"{/if}></td>
				<td><b>{$data['username']}</b></td>
				<td>{$data['realname']}</td>
				<td>{$data['email']}</td>
				<td>{$data['loginip']}<div class="f12 time">{format::date($data['logintime'])}</div></td>
			</tr>
		{/loop}
		</tbody>
		</table>
	</div><!-- main-body -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action, data, function(msg){
				if( msg.state ){
					$dialog.close();
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}