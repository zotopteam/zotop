{template 'dialog.header.php'}

	{form::header()}
		<table class="field">
			<tr>
				<td class="label">{form::label($attrs['id'],'id',true)}</td>
				<td class="input">
					{if empty($data['id'])}
					{form::field(array('type'=>'text','name'=>'id','value'=>$data['id'],'required'=>'required','remote'=>u('developer/project/check/id/'.$data['id'])))}
					{form::tips(t('应用的唯一标示符，勿与其它模块重复，只允许英文、数字'))}
					{else}
					{form::field(array('type'=>'hidden','name'=>'id','value'=>$data['id']))}
					{form::field(array('type'=>'text','name'=>'id','value'=>$data['id'],'disabled'=>'disabled'))}
					{form::tips(t('应用的唯一标识符不能修改，如果想修改请重建一个新应用'))}
					{/if}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label($attrs['dir'],'dir',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'dir','value'=>$data['dir'],'required'=>'required','remote'=>u('developer/project/check/dir/'.$data['dir'])))}
					{form::tips(t('应用的目录名称，勿与其它模块重复，只允许英文、数字'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label($attrs['name'],'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
					{form::tips(t('应用名称，请为您的应用起一个准确的名称'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label($attrs['type'],'type',true)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>array('module'=>t('模块'),'plugin'=>t('插件')),'name'=>'type','value'=>$data['type']))}
					{form::tips(t('具有完整功能的应用为模块，为其它应用服务的为插件'))}
				</td>
			</tr>
			<tr style="display:none;">
				<td class="label">{form::label($attrs['tables'],'tables',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'tables','value'=>$data['tables']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label($attrs['dependencies'],'dependencies',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'dependencies','value'=>$data['dependencies']))}
					{form::tips(t('多个依赖应用的标识之间用英文逗号隔开'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label($attrs['description'],'description',false)}</td>
				<td class="input">
				{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'required'=>'required'))}
				{form::tips(t('请详细填写您的应用的描述或者说明'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label($attrs['version'],'version',false)}</td>
				<td class="input">
					{form::field(array('type'=>'number','name'=>'version','value'=>$data['version']))}
					{form::tips(t('当前版本号，只允许使用数字'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('开发者信息'),'author',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'author','value'=>$data['author'],'placeholder'=>$attrs['author']))}
					<div class="blank"></div>
					{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'placeholder'=>$attrs['email']))}
					<div class="blank"></div>
					{form::field(array('type'=>'url','name'=>'homepage','value'=>$data['homepage'],'placeholder'=>$attrs['homepage']))}
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