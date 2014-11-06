{template 'header.php'}


{form::header()}
<div class="main">
	<div class="main-header">
		<div class="title">{t('数据导入')}</div>

		<div class="position">
			<a href="{u('dbimport/admin/index')}">{t('规则管理')}</a>
			<s class="arrow">></s>
			{$title}			
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="field">
			<caption>{t('基本设置')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('规则名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>50,'required'=>'required','placeholder'=>t('如导入新闻数据')))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('规则说明'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</td>
			</tr>			
		</table>
		
		<table class="field">
			<caption>{t('数据源设置')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('数据库类型'),'source',true)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>array('mysql'=>t('MySql'),'sqlite'=>t('Sqlite')),'name'=>'source[driver]','value'=>$data['source']['driver']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据库地址'),'hostname',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'source[hostname]','value'=>$data['source']['hostname'],'required'=>'required','placeholder'=>t('如localhost或者ip地址')))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据库端口'),'hostport',true)}</td>
				<td class="input">
					{form::field(array('type'=>'number','name'=>'source[hostport]','value'=>$data['source']['hostport'],'required'=>'required','placeholder'=>t('一般为3306')))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据库用户名'),'username',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'source[username]','value'=>$data['source']['username'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据库密码'),'password',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'source[password]','value'=>$data['source']['password']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据库字符集'),'charset',true)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>array('utf8'=>t('UTF8'),'gbk'=>t('GBK'),'gb2312'=>t('GB2312')),'name'=>'source[charset]','value'=>$data['source']['charset']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据库名称'),'database',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'source[database]','value'=>$data['source']['database'],'placeholder'=>t('请填写数据库名称')))}

				</td>
			</tr>			


		</table>

		<div class="maps">
			
		</div>		

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存并返回')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	
	var data = {json_encode($data)};

	function getmaps(){

		if ( $('[name="source[hostname]"]').val() && $('[name="source[database]"]').val() ){

			// 数据合并
			$.each($('form.form').serializeArray(), function(i,field){
				data[field.name] = field.value;
			});

			// 载入对应关系
			$('.maps').html('<i class="icon icon-loading"></i>');

			$.post("{U('dbimport/admin/post_maps')}", data, function(html){				
				$('.maps').html(html);
			});
		}
	}

	// 测试数据库连接
	$(function(){
		
		getmaps();

		$('[name="source[driver]"],[name="source[charset]"]').on('click',function(){
			getmaps();
		});

		$('[name="source[hostname]"],[name="source[hostport]"],[name="source[username]"],[name="source[password]"],[name="source[database]"],[name="source[charset]"]').on('change',function(){
			getmaps();
		});			

		$('form.form').find('.submit').disable(true);
	})	

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

	$(function(){
		$('[name="settings[list]"]').on('click',function(){
			if( $(this).val()==1 ){
				$('.options-list').show();
			}else{
				$('.options-list').hide();
			}
		});				
	})
</script>

{template 'footer.php'}