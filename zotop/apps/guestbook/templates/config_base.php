{template 'header.php'}

{template 'guestbook/admin_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('留言本设置')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->

	{form action="U('guestbook/config/save')"}

	<div class="main-body scrollable">
		<div class="container-fluid">
			<div class="form-horizontal">
				<div class="form-title">{t('基本设置')}</div>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('留言本标题'),'title',true)}</div>
					<div class="col-sm-6">
						{form::field(array('type'=>'text','name'=>'title','value'=>c('guestbook.title'),'required'=>'required'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('留言本说明'),'description',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'editor','name'=>'description','value'=>c('guestbook.description'),'rows'=>'10'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('留言本图片'),'image',false)}</div>
					<div class="col-sm-6">
						{form::field(array('type'=>'image','name'=>'image','value'=>c('guestbook.image'),'watermark'=>0))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('显示留言列表'),'showlist',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'bool','name'=>'showlist','value'=>c('guestbook.showlist')))}
						<div class="blank"></div>
						{form::field(array('type'=>'radio','options'=>array(1=>t('只显示已审核留言'),0=>t('显示全部')),'name'=>'showaudited','value'=>c('guestbook.showaudited')))}
						<div class="blank"></div>
						{form::field(array('type'=>'radio','options'=>array(1=>t('只显示已回复留言'),0=>t('显示全部')),'name'=>'showreplied','value'=>c('guestbook.showreplied')))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('每页显示条数'),'pagesize',true)}</div>
					<div class="col-sm-6">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'pagesize','value'=>c('guestbook.pagesize'),'max'=>100,'min'=>10,'required'=>'required'))}
							<span class="input-group-addon">{t('条')}</span>
						</div>
						{form::tips(t('留言列表每页显示的条数'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('管理员名称'),'adminname',true)}</div>
					<div class="col-sm-6">
						{form::field(array('type'=>'text','name'=>'adminname','value'=>c('guestbook.adminname'),'required'=>'required'))}
						{form::tips(t('留言本显示的管理员名称'))}
					</div>
				</div>
				
			</div>

			<div class="form-horizontal">
				<div class="form-title">{t('留言设置')}</div>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('留言字数'),'min_max',true)}</div>
					<div class="col-sm-6">
						<div class="input-group">
						{form::field(array('type'=>'number','name'=>'minlength','value'=>c('guestbook.minlength'),'placeholder'=>t('最少字数'),'required'=>'required'))}
						<span class="input-group-addon">-</span>
						{form::field(array('type'=>'number','name'=>'maxlength','value'=>c('guestbook.maxlength'),'placeholder'=>t('最多字数'),'required'=>'required'))}
						</div>
						<label for="minlength" class="error">{t('必须是数字且不能为空')}</label>
						<label for="maxlength" class="error">{t('必须是数字且不能为空')}</label>
						{form::tips(t('留言内容字数范围限制'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('超链接检测'),'maxlinks',true)}</div>
					<div class="col-sm-6">
						
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'maxlinks','value'=>c('guestbook.maxlinks'),'min'=>1,'required'=>'required'))}
							<span class="input-group-addon">{t('个')}</span>
						</div>

						{form::tips(t('当留言内容中链接数目超出设置个数时，将其放入垃圾队列（垃圾留言通常含有许多超链接）'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('开启验证码'),'captcha',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'bool','name'=>'captcha','value'=>c('guestbook.captcha')))}
					</div>
				</div>

				{if A('member')}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('只允许会员留言'),'captcha',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'bool','name'=>'memberallowed','value'=>c('guestbook.memberallowed')))}
					</div>
				</div>
				{/if}
				
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{/form}

</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').button('reset');
			},'json');
		}});
	});
</script>
{template 'footer.php'}