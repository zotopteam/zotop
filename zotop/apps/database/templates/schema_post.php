{template 'dialog.header.php'}
<div class="main no-footer scrollable">

	{form::header()}
		<table class="field">
			<tr>
				<td class="label">{form::label(t('位于'),'position',false)}</td>
				<td class="input">
					{form::field(array('type'=>'select','name'=>'position','value'=>$data['position'],'options'=>$position,'class'=>'short'))}
				</td>
			</tr>		
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required','remote'=>zotop::url('database/schema/checkfield/'.$tablename.'/'.$data['name'])))}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('类型'),'type',false)}/{form::label(t('长度'),'length',false)}</td>
				<td class="input">
					{form::field(array('type'=>'select','name'=>'type','value'=>$data['type'],'options'=>$types,'class'=>'short'))}
					{form::field(array('type'=>'text','name'=>'length','value'=>$data['length'],'class'=>'short','title'=>t('请输入 字段长度 或者 字段长度,精度'),'pattern'=>'[0-9,]+'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('默认值'),'default',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'default','value'=>$data['default']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('自增'),'autoinc',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'autoinc','value'=>$data['autoinc']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('UNSIGNED'),'unsigned',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'unsigned','value'=>$data['unsigned']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('NOT NULL'),'notnull',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'notnull','value'=>$data['notnull']))}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('注释'),'comment',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'comment','value'=>$data['comment']))}
				</td>
			</tr>
		</table>
	{form::footer()}
	</form>
	<script type="text/javascript" src="{a('system.url')}/common/js/jquery.validate.min.js"></script>
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

					if( msg.state ){
						$dialog.close();
					}
					$.msg(msg);

				},'json');
			}});
		});
	</script>

</div>

{template 'dialog.footer.php'}