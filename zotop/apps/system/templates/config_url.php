{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('系统设置')}</div>
		<ul class="nav nav-tabs">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="active"{/if}>
				<a href="{$n.href}"><i class="{$n.icon} fa-fw"></i><span class="hidden-xs hidden-sm">{$n.text}</span></a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->

	{form::header()}

	<div class="main-body scrollable">			
		
		<div class="container-fluid">	

			<fieldset class="form-horizontal">

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('链接模式'),'url_model',false)}</div>
					<div class="col-sm-8">
						<div class="form-radio">
							<div>
								<label title="{t('默认模式需要服务器支持[pathinfo]，如果不支持请使用兼容模式')}">
									<input type="radio" name="url_model" value="pathinfo" {if c('system.url_model')=='pathinfo'}checked="checked"{/if}/>
									<span>{t('默认模式')}</span>
									<span>{t('示例')} : http://www.zotop.com/index.php/content/detail/1</span>
									<em class="text-success">{t('推荐')}</em>
								</label>
							</div>
							<div>
								<label>
									<input type="radio" name="url_model" value="normal" {if c('system.url_model')=='normal'}checked="checked"{/if}/>
									<span>{t('兼容模式')}</span>
									<span>{t('示例')} : http://www.zotop.com/index.php?r=content/detail/1</span>
								</label>
							</div>
							<div>
								<label title="{t('重写模式可以去掉URL里面的index.php，该功能需要服务器支持URL_REWRITE')}">
									{if rewrite::check()}
									<input type="radio" name="url_model" value="rewrite" {if c('system.url_model')=='rewrite'}checked="checked"{/if}/>
									<span>{t('重写模式')}</span>
									<span>{t('示例')} : http://www.zotop.com/content/detail/1</span>
									{else}
									<input type="radio" name="url_model" value="2" disabled="disabled"/>
									<span>{t('重写模式')}</span>
									<span>{t('示例')} : http://www.zotop.com/content/detail/1</span>
									<span class="text-error">{t('服务器不支持URL_REWRITE')}</span>
									{/if}
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('伪静态'),'url_suffix',false)}</div>
					<div class="col-sm-8">
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
						{form::tips(t('链接为默认模式或者重写模式时URL后增加的后缀，如<b>.html</b>，<b>.htm</b>'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">
						{form::label(t('路由规则'),'url_router',false)} 
						<i class="fa fa-question-circle tooltip-block" data-placement="right">
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
					</div>
					<div class="col-sm-8">
						<table class="table table-hover table-border sortable" id="datalist">
							<thead>
								<tr>
									<td	class="drag">&nbsp;</td>
									<td>{t('应用')} / {t('控制器')} / {t('动作')} / {t('参数')} / ……</td>
									<td>{t('路由规则')}&nbsp;</td>
									<td class="manage">{t('操作')}</td>
								</tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td colspan="3"><a class="btn btn-primary btn-sm" href="javascript:;" onclick="datalist.addrow()"><i class="fa fa-plus fa-fw"></i> <b>{t('添加一行')}</b></a></td>
								<tr>
							</tfoot>
						</table>
					</div>
				</div>
			</fieldset>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<script type="text/javascript">
	var datalist = [];

		// 添加节点
		datalist.addrow = function(pattern, route){
			
			var rowHtml = '<tr>'+
			'<td class="drag">&nbsp;</td>'+
			'<td><input type="text" class="form-control text" name="url_route[]" value="'+ ( route || '' ) +'" placeholder="{t('示例参考')}: content/<c>/<id>"></td>'+
			'<td><input type="text" class="form-control text" name="url_pattern[]" value="'+ ( pattern || '' ) +'" placeholder="{t('示例参考')}: content/<c:index|list>-<id:num>.html"></td>'+
			'<td class="manage"><a href="javascript:;" class="delete" onclick="datalist.delrow(this)"><i class="fa fa-trash"></i> {t('删除')}</a></td>'+
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
			dataset = $.isEmptyObject(dataset) ?  {'':''} : dataset;

		for (var key in dataset) {
			datalist.addrow(key, dataset[key]);
		}
	})

	//sortable
	$(function(){
		$("table.sortable").sortable({
			items: "tbody > tr",
			handle: "td.drag",
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
			$(form).find('.submit').button('loading');
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				if( msg.state ){
					location.href="{u('system/config/url')}"
				}else{
					$(form).find('.submit').button('reset');
				}
			},'json');
		}});
	});
</script>
{template 'footer.php'}