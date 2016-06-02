{template 'member/header.php'}

{form class="form-horizontal"}

	{field type="hidden" name="modelid" value="$data.modelid"}

	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">{$title}</div>
		</div>
		<div class="panel-body">

				{if $data.email}
				<div class="form-group">
					<label for="static" class="col-sm-2 control-label">{t('当前邮箱地址')}</label>
					<div class="col-sm-8">
						{field type="static" value="$data.email"}
					</div>
				</div>
				{/if}

				<div class="form-group">
					<label for="email" class="col-sm-2 control-label required">{t('新邮箱地址')}</label>
					<div class="col-sm-8">						
						{field type="email" name="email" required="required" remote="u('member/mine/check/email')"}
					</div>
				</div>				

				<div class="form-group">
					<label for="verifycode" class="col-sm-2 control-label required">{t('邮箱验证码')}</label>
					<div class="col-sm-5">
						
						<div class="input-group">
							{field type="verifycode" name="verifycode" required="required" remote="u('member/mine/check/email')"}
						
							<span class="input-group-btn">
								<a id="sendverifycode" class="btn btn-primary" data-loading-text="{t('发送中...')}" href="{U('member/verifycode/sendemail')}">
								{t('获取邮箱验证码')}
								</a>
							</span>
						</div>

					</div>
				</div>		

		</div><!-- panel-body -->
		<div class="panel-footer">
			{field type="submit" value="t('提交')"}
		</div>
	</div> <!-- panel -->
{/form}


<script type="text/javascript">
	$(function(){
		var validator = $('form.form').validate({
			submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$(form).find('.submit').button('loading');
				$.post(action, data, function(msg){
					$(form).find('.submit').button('reset');
					$.msg(msg);	
				},'json');
			}
		});

	    var count,counts=60;
		var $btn    = $("#sendverifycode");
		var $target = $("[name=email]");
		var btntext = $btn.html();

	    // 发送验证码并倒计时
	    $btn.on('click', function(e) {
	        e.preventDefault();

	        // 发送之前验证字段
	        if ( !validator.element('[name=email]') ){
	        	return false;
	        }

	        $btn.button('loading');
	        $.post($btn.attr('href'),{'target':$target.val()},function(msg){            	
                if( msg.state ){
			        count = counts;	        
			        var timer = setInterval(function () {
			            if(count == 0){
			                clearInterval(timer);
			                $btn.button('reset');
			            }else{
			                count--;
			                $btn.html(btntext + " ("+count+")");
			            }
			        },1000);
                }else{
                	$btn.button('reset');
                }
                $.msg(msg);
	        },'json');
	    });		
	});
</script>



{template 'member/footer.php'}