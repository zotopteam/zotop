{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="field">
			<caption>{t('基本信息')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
				</td>
			</tr>
			{if empty($data['id'])}
			<tr>
				<td class="label">{form::label(t('标识'),'id',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'id','value'=>$data['id'],'minlength'=>5,'maxlength'=>32,'required'=>'required'))}
					{form::tips('只能由英文字母、数字和下划线组成')}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据表名'),'tablename',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'tablename','value'=>$data['tablename'],'minlength'=>4,'maxlength'=>32,'required'=>'required'))}
					{form::tips('只能由英文字母、数字和下划线组成，不含数据表前缀')}
				</td>
			</tr>
			{/if}
			<tr>
				<td class="label">{form::label(t('描述'),'description',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('禁用'),'disabled',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>$data['disabled']))}
				</td>
			</tr>
		</tbody>
	</table>
	<table class="field">
		<caption>{t('注册设置')}</caption>
		<tbody>

		<tr>
			<td class="label">{form::label(t('允许注册'),'settings[register]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'bool','name'=>'settings[register]','value'=>$data['settings']['register']))}
			</td>
		</tr>

		<tr>
			<td class="label">{form::label(t('注册页面模板'),'settings[register_template]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'template','name'=>'settings[register_template]','value'=>$data['settings']['register_template'],'required'=>'required'))}
			</td>
		</tr>

		<tr>
			<td class="label">{form::label(t('开启验证码'),'settings[register_captcha]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'bool','name'=>'settings[register_captcha]','value'=>$data['settings']['register_captcha']))}
			</td>
		</tr>

		<tr>
			<td class="label">{form::label(t('默认点数'),'settings[point]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'number','name'=>'settings[point]','value'=>(int)$data['settings']['point']))}
			</td>
		</tr>
		<tr>
			<td class="label">{form::label(t('默认金额'),'settings[amount]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'number','name'=>'settings[amount]','value'=>(int)$data['settings']['amount']))}
			</td>
		</tr>
		<tr>
			<td class="label">{form::label(t('默认用户组'),'settings[groupid]',true)}</td>
			<td class="input">
				{if $data['id']}
				{form::field(array('type'=>'select','options'=>$groups,'name'=>'settings[groupid]','value'=>$data['settings']['groupid'],'required'=>'required'))}
				{else}
				{form::field(array('type'=>'input','name'=>'groupname','value'=>'','required'=>'required'))}
				{/if}
			</td>
		</tr>
		<tr>
			<td class="label">{form::label(t('注册协议'),'register_protocol',false)}</td>
			<td class="input">
				{form::field(array('type'=>'editor','name'=>'register_protocol','value'=>$data['settings']['register_protocol']))}
			</td>
		</tr>
		</tbody>
	</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){

				if ( !msg.state ){
					$(form).find('.submit').disable(false);
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'footer.php'}