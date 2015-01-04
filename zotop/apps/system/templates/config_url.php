{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('全局设置')}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="icon {$n['icon']}"></i>{/if} {$n['text']}</a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<table class="field">
				<tbody>
				<tr>
					<td class="label">{form::label(t('链接模式'),'url_pathinfo',false)}</td>
					<td class="input">
						<table class="controls radio">
							<tr>
								<td>
								<label title="{t('默认模式需要服务器支持[pathinfo]，如果不支持请使用兼容模式')}">
									<input type="radio" name="url_model" value="pathinfo"{if c('system.url_model') == 'pathinfo'} checked="checked"{/if}/> {t('默认模式')}
									<span>{t('示例')} : http://www.zotop.com/index.php/content/detail/1</span>
									<b>{t('推荐')}</b>
								</label>
								</td>
							</tr>
							<tr>
								<td>
								<label>
									<input type="radio" name="url_model" value="normal"{if c('system.url_model') == 'normal'} checked="checked"{/if}/> {t('兼容模式')}
									<span>{t('示例')} : http://www.zotop.com/index.php?r=content/detail/1</span>
								</label>
								</td>
							</tr>
							<tr>
								<td>
								<label title="{t('重写模式可以去掉URL里面的index.php，该功能需要服务器支持URL_REWRITE')}">
									{if rewrite::check()}
									<input type="radio" name="url_model" value="rewrite"{if c('system.url_model') == 'rewrite'} checked="checked"{/if}/> {t('重写模式')}
									<span>{t('示例')} : http://www.zotop.com/content/detail/1</span>
									{else}
									<input type="radio" name="url_model" value="2" disabled="disabled"/> {t('重写模式')}
									<span>{t('示例')} : http://www.zotop.com/content/detail/1</span>
									<span class="red">{t('服务器不支持URL_REWRITE')}</span>
									{/if}
								</label>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('伪静态'),'url_suffix',false)}</td>
					<td class="input">
						<?php echo form::field(array(
							'type'=>'radio',
							'options'=>array(
								''			=> t('关闭'),
								'.html'		=> '.html',
								'.htm'		=> '.htm',
								'.shtml'	=> '.shtml'
							),
							'name'=>'url_suffix',
							'value'=>c('system.url_suffix')
						))
						?>
						{form::tips(t('链接为默认模式或者重写模式时URL后增加的后缀，如<b>.html</b>，<b>.htm<b/>'))}
					</td>
				</tr>
				<tr>
					<td class="label">
						{form::label(t('路由规则'),'url_router',false)}
						<i class="icon icon-help tooltip-block" data-placement="right">
							<div class="tooltip-block-content">								

								<h3>{t('示例参考')}：</h3>
								<p>{html::encode('content/<c>/<id> => content/<c:index|list>-<id:num>.html')}</p>
								<p>{html::encode('content/detail/<id> => content-<id:num>.html')}</p>

								<h3>{t('参数说明')}：</h3>
								<p>{t(':num 数字')}</p>
								<p>{t(':str 字母、数字及下划线')}</p>
								<p>{t(':\d+ 直接使用正则表达式，:\d+ 等同于 :num')}</p>								
							</div>
						</i>						
					</td>
					<td class="input">
						<table class="table list border zebra sortable" id="datalist">
							<thead>
								<tr>
									<td	class="drag">&nbsp;</td>
									<td>{t('应用/控制器/动作/参数……')}</td>
									<td>{t('路由规则')}&nbsp;</td>
									<td class="manage">{t('操作')}</td>
								</tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
								<tr>
								<td colspan="4"><a href="javascript:;" onclick="datalist.addrow()"><i class="icon icon-add"></i><b>{t('添加一行')}</b></a></td>
								<tr>
							</tfoot>
						</table>
					</td>
				</tr>
				</tbody>
			</table>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	var datalist = [];

		// 添加节点
		datalist.addrow = function(pattern, route){
			
			var rowHtml = '<tr>'+
			'<td class="drag" title="{t('拖动排序')}" data-placement="left">&nbsp;</td>'+
			'<td><input type="text" class="text" style="width:90%" name="url_route[]" value="'+ ( route || '' ) +'"></td>'+
			'<td><input type="text" class="text" style="width:90%" name="url_pattern[]" value="'+ ( pattern || '' ) +'"></td>'+
			'<td class="manage"><a href="javascript:;" class="delete" onclick="datalist.delrow(this)" title="{t('删除')}"><i class="icon icon-delete"></i></a></td>'+
			'</tr>';

			$('#datalist tbody').append(rowHtml);
		}

		// 删除节点
		datalist.delrow = function(ele) {
			$(ele).closest('tr').remove();
		}

	// 生成默认数据
	$(function(){

		var dataset = {json_encode((object)C('router'))};
			//dataset = dataset.length ? dataset : {'':''};

		for (var key in dataset) {
			datalist.addrow(key, dataset[key]);
		}
	})

	//sortable
	$(function(){
		$("table.sortable").sortable({
			items: "tbody > tr",
			axis: "y",
			placeholder:"ui-sortable-placeholder",
			helper: function(e,tr){
				tr.children().each(function(){
					$(this).width($(this).width());
				});
				return tr;
			},
			stop:function(event,ui){
			}
		});
	});

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				if( msg.state ){
					location.href="{u('system/config/url')}"
				}else{
					$(form).find('.submit').disable(false);
				}
			},'json');
		}});
	});
</script>
{template 'footer.php'}