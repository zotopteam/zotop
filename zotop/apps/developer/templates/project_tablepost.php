{template 'dialog.header.php'}

	{form::header()}
		<table class="field">
			<tr>
				<td class="label">{form::label(t('数据表名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required','remote'=>u('developer/project/checktable/'.$data['name'])))}
					{form::tips(t('不含前缀的数据表名称，只允许英文数字和下划线'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据表注释'),'comment',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'comment','value'=>$data['comment'],'required'=>'required'))}
					{form::tips(t('数据表注释信息'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据表类型'),'engine',true)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>array('MyISAM'=>t('MyISAM'),'InnoDB'=>t('InnoDB')),'name'=>'engine','value'=>$data['engine']))}
					{form::tips(t('数据表类型，合理选择有助于提升效率'))}
				</td>
			</tr>
		</table>
	{form::footer()}
	</form>
	<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
	<script type="text/javascript">

		// 对话框设置
		$dialog.callbacks['ok'] = function(){
			$('form.form').submit();
			return false;
		};

		// 表单验证
		$(function(){
			$('form.form').validate({submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$.loading();
				$.post(action,data,function(msg){
					msg.state && $dialog.close();
					$.msg(msg);
				},'json');
			}});
		});
	</script>

{template 'dialog.footer.php'}