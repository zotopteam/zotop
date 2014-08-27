{template 'header.php'}

<div class="side">
	<div class="side-header">{t('单元测试')}</div>
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			{loop $navlist $nav}
			<li><a href="{$nav[href]}"{if request::url(false)==$nav['href']} class="current"{/if}><b>{$nav[text]}</b></a></li>
			{/loop}
		<ul>
	</div>
</div>
<div class="main side-main">
	<div class="main-header"><div class="title">{$title}</div></div>
	<div class="main-body scrollable">
		<div>
		<br><br><br><br><br><br><br><br><br><br><br><br>
		</div>
		<div style="height:600px;">
		<a href="javascript:;" class="btn" id="model">model</a>
		<a href="javascript:;" class="btn" id="prompt">输入测试</a>
		<a href="javascript:;" class="btn" id="confirm">confirm</a>
		<a href="javascript:;" class="btn" id="alert">alert</a>
		<a href="javascript:;" class="btn" id="paopao" data-align="right top">气泡测试</a>
		</div>
		<script style="text/javascript">

			$(function(){
				$('#model').on('click',function(){
					var d = $.dialog({
						width: 1000,
						height: 300,
						//quickClose: true,
						data : 'ddddd',
						title : 'dddd',
						content: '按钮回调函数返回 false 则不许关闭',
						url: '{u('test/index/dialog-6-iframe')}',
						statusbar: 'statusbar',
						onclose: function () {
							if (this.returnValue) {
								alert(this.returnValue);
							}
						},
						ok: function () {
							this.shake();
							this.statusbar('提交中…');
							this.content('ok');
							return false;
						},
						cancel: function () {}
					},true);
					return false;
				});

				$('#paopao').on('click',function(){
					$(this).dialog({
						width: 800,
						height: 300,
						title : '测试标题',
						//content: this,
						url: '{u()}',
						statusbar: 'statusbar',
						ok: function () {
							this.statusbar('提交中…');
							this.content('<div style="height:300px;width:500px;">ok</div>');
							return false;
						},
						cancel: function () {},
						onclose: function(){
							$.success('onclose');
						}
					});
					return false;
				});


				$('#prompt').on('click',function(){
					$.prompt('请输入数据',function(v){
						alert(v);
					},'1').title('提示');
				});


				$('#confirm').on('click',function(){
					$.confirm('请输入数据',function(v){
						this.statusbar('<i class="icon icon-loading"></i>');
						return false;
					});
				});

				$('#alert').on('click',function(){
					$.alert('请输入数据');
				});
			});

			$.loading();

			$.msg({
				state : true,
				content : '测试一下消息sss',
				time: 50000
			})

			</script>
	</div>
	<div class="main-footer">{t('测试对话框')}</div>
</div>
{template 'footer.php'}