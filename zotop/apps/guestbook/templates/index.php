<?php
/**
 * title:留言模板
 * description:默认留言首页模板
*/
?>
{template 'header.php'}


{if c('guestbook.image')}
	<div class="banner"><img src="{c('guestbook.image')}" alt="{$description}"></div>
{/if}

<div class="guestbook">
	<div class="guestbook-head">
		<h2 class="guestbook-title">{$title}</h2>
		<h5 class="guestbook-description">{$description}</h5>
		<a href="#guestbook-add" class="btn btn-highlight btn-icon-text guestbook-add"><i class="icon icon-add"></i><b>{t('发表留言')}</b></a>
	</div>

	{if c('guestbook.showlist') and $data}
		<div class="guestbook-body box-border">
			<dl class="guestbook-list">
				<dt>{t('留言列表')}</dt>
				{loop $data $r}
				<dd>
					<div class="avatar">
						{if $r.avatar}
						<img src="{$r.avatar}" alt="{$r.name}">
						{else}
						<img src="{m('system.user.avatar', $r.userid)}" alt="{$r.name}">
						{/if}
					</div>
					<div class="text">
						<div class="title"><a><b>{$r.name}</b></a> </div>
						<div class="content-reply">
							<div class="content">{$r.content}</div>
							<div class="createtime">{format::date($r.createtime)}</div>
							{if !empty($r.reply)}
							<div class="reply">
								<b>{c('guestbook.adminname')} : </b>
								{$r.reply}
							</div>
							{/if}
						</div>
					</div>
				</dd>
				{/loop}
			</dl>
			<div class="pagination clearfix">{pagination::instance($total, $pagesize, $page)}</div>
		</div>
	{/if}
</div>

<div class="blank"></div>
<div class="blank"></div>


<div class="box box-border" id="guestbook-add">
<div class="box-head">
	<div class="box-title">	{t('发表留言')} </div>
</div>
<div class="box-body">
{form::header(u('guestbook/index/add'))}
	<div class="field field-s-m">
		<div class="label">{form::label(t('您的名字'),'name',true)}</div>
		<div class="input">{form::field(array('type'=>'text','name'=>'name','required'=>'required'))}</div>
	</div>
	<div class="field field-s-m">
		<div class="label">{form::label(t('您的邮箱'),'email',true)}</div>
		<div class="input">{form::field(array('type'=>'email','name'=>'email','required'=>'required'))}</div>
	</div>
	<div class="field field-s-m">
		<div class="label">{form::label(t('留言内容'),'content',true)}</div>
		<div class="input">{form::field(array('type'=>'textarea','name'=>'content','required'=>'required','minlength'=>c('guestbook.minlength'),'maxlength'=>c('guestbook.maxlength')))}</div>
	</div>
	{if c('guestbook.captcha')}
	<div class="field field-s-m">
		<div class="label">{form::label(t('验证码'),'captcha',true)}</div>
		<div class="input">{form::field(array('type'=>'captcha','name'=>'captcha','required'=>'required'))}</div>
	</div>
	{/if}
	<div class="field field-s-m">
		<div class="input">{form::field(array('type'=>'submit','value'=>t('提交留言')))} <span class="result"></span></div>
	</div>

{form::footer()}
</div>
</div>

<style type="text/css">
	.guestbook-head{position: relative;margin: 15px 0;}

	.guestbook-add{position: absolute;top:10px;right:20px;}
	.guestbook-title{font-size:24px;margin: 10px 0;}
	.guestbook-description{font-size: 16px;font-weight: normal;}

	.guestbook-body{margin:10px 0;}

	.guestbook-list dt{font-size: 18px;padding:5px 0;border-bottom:solid 1px #ddd;}
	.guestbook-list dd{margin:5px 0;padding: 5px 0;border-bottom:solid 1px #ddd;}

	.guestbook-list .avatar{float:left;width:100px;margin-right:-100px;}
	.guestbook-list .avatar img{width:80px;height:80px;}

	.guestbook-list .text{margin-left:100px;}

	.guestbook-list .title b{font-size: 16px;}
	.guestbook-list .content{color: #666;font-size: 14px;line-height: 1.5em;margin: .5em 0;word-wrap: break-word;}
	.guestbook-list .createtime{color:#999;}
	.guestbook-list .reply{color: red;margin-top: 10px;}
</style>
<script type="text/javascript" src="{__THEME__}/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			submitHandler:function(form){

				$(form).find('.submit').disable(true);
				$(form).find('.result').html('<i class="icon icon-loading"></i>');

				$.post($(form).attr('action'), $(form).serialize(), function(msg){

					if( msg.state ){

						$(form).find('.result').addClass('success').html(msg.content);
						$(form)[0].reset();

						return true;
					}

					$(form).find('.submit').disable(false);
					$(form).find('.result').addClass('error').html(msg.content);
					return false;
				},'json');
			}
		});
	});
</script>

{template 'footer.php'}