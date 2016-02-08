{template 'header.php'}

{template 'developer/project_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="{U('developer/project/table')}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>	
		<div class="title">{$title} : {$table}</div>
		<div class="action">
			<a href="{U('developer/schema/show/'.$table)}" class="btn">
				<i class="fa fa-code"></i> <b>{t('安装代码')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		<div class="container-fluid">
			<form>
				<table class="table table-nowrap table-hover list" id="fields">
					<caption class="caption-default">
						{t('字段')}
						<a class="btn btn-primary btn-add js-open pull-right" href="{u('developer/schema/addfield/'.$table)}" data-width="800px" data-height="480px">
							{t('新建字段')}
						</a>
					</caption>
					<thead>
						<tr>
							<td class="select"><input type="checkbox" class="checkbox select-all"></td>
							<td class="hidden">{t('主键')}</td>
							<td class="w140">{t('字段')}</td>
							<td>{t('类型')}</td>
							<td class="w80">{t('默认')}</td>
							<td>{t('说明')}</td>
							<td class="text-center" width="60">{t('自增')}</td>
							<td class="text-center" width="60">{t('非空')}</td>
							<td class="manage" width="180">{t('操作')}</td>
						</tr>
					</thead>
					<tbody>
					{loop $schema[fields] $key $field}
						<tr class="{if $i%2==0}even{else}odd{/if}">
							<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$key}"></td>
							<td class="hidden">{if in_array($key,$schema[primary])}<span class="green">√</span>{/if}</td>
							<td>
								<div class="title">
									<b class="name {if in_array($key,$schema[primary])}text-danger{/if}">{$key}</b>
								</div>
							</td>
							<td>{$field['type']}{if $field[length]}({$field[length]}){/if} {if $field[unsigned]}unsigned{/if}</td>
							<td>{if isset($field['default'])}{htmlspecialchars($field['default'])}{elseif $field['notnull'] === false}<span class="null">NULL</span>{/if}</td>
							<td>{$field['comment']}</td>
							<td class="text-center">{if $field['autoinc']} <i class="fa fa-check-circle text-success"></i> {else} <i class="fa fa-times-circle text-muted"></i> {/if}</td>
							<td class="text-center">{if $field['notnull']} <i class="fa fa-check-circle text-success"></i> {else} <i class="fa fa-times-circle text-muted"></i> {/if}</td>
							<td class="manage" width="180">
								<a class="js-open" href="{u('developer/schema/editfield/'.$table.'/'.$key)}" data-width="800px" data-height="480px">
									<i class="fa fa-edit"></i> {t('修改')}
								</a>
								<s>|</s>
								<a class="js-confirm" href="{u('developer/schema/dropfield/'.$table.'/'.$key)}">
									<i class="fa fa-times"></i> {t('删除')}
								</a>
							</td>
						</tr>
						{$i++}
					{/loop}
					</tbody>
					<tfoot>
						<tr>
							<td class="select"><input type="checkbox" class="checkbox select-all"></td>
							<td colspan="7">
								
								<a class="btn btn-default operate" href="javascript:void(0)" id="field-datalist" style="display:none;">{t('浏览')}</a>
								<a class="btn btn-default operate" href="{u('developer/schema/operate/'.$table.'/primary')}"><span class="red">{t('主键')}</span></a>
								<div class="btn-group">
									<a class="btn btn-default operate" href="{u('developer/schema/operate/'.$table.'/index')}">{t('索引')}</a>
									<a class="btn btn-default operate" href="{u('developer/schema/operate/'.$table.'/unique')}">{t('唯一索引')}</a>
									<a class="btn btn-default operate" href="{u('developer/schema/operate/'.$table.'/fulltext')}">{t('全文搜索')}</a>
								</div>								
							</td>
						</tr>

					</tfoot>
				</table>
			</form>
		</div>

		<div class="container-fluid">
			<table class="table table-nowrap table-hover list">
				<caption class="caption-default">{t('索引')}</caption>
				<thead>
					<tr>
						<td>{t('键名')}</td>
						<td>{t('类型')}</td>
						<td>{t('字段')}</td>
						<td>{t('操作')}</td>
					</tr>
				</thead>
				<tbody>
				{if !empty($schema['primary']) and is_array($schema['primary'])}
					<tr class="red">
						<td><div class="text-overflow text-danger" title="{$key}">PRIMARY</div></td>
						<td><span>PRIMARY</span></td>
						<td>
							{loop $schema['primary'] $i $f}
								<div class="text-overflow"> {$f} </div>
								{$i++}
							{/loop}
						</td>
						<td class="manage" width="180">
							<a class="js-confirm" href="{u('developer/schema/dropprimary/'.$table)}">
								<i class="fa fa-times"></i>  {t('删除')}
							</a>
						</td>
					</tr>

				{/if}

				{loop $schema[unique] $key $field}
					<tr>
						<td><div class="text-overflow" title="{$key}">{$key}</div></td>
						<td><span>UNIQUE</span></td>
						<td>
							{loop $field $i $f}
								<div class="text-overflow">{if is_array($f)} {$f[0]} ( {$f[1]} ) {else} {$f} {/if}</div>
								{$i++}
							{/loop}
						</td>
						<td class="manage" width="180">
							<a class="js-confirm" href="{u('developer/schema/dropindex/'.$table.'/'.$key)}">
								<i class="fa fa-times"></i>   {t('删除')}
							</a>
						</td>
					</tr>
				{/loop}

				{loop $schema[index] $key $field}
					<tr>
						<td><div class="text-overflow" title="{$key}">{$key}</div></td>
						<td><span>INDEX</span></td>
						<td>
							{loop $field $i $f}
								<div class="text-overflow">{if is_array($f)} {$f[0]} ( {$f[1]} ) {else} {$f} {/if}</div>
								{$i++}
							{/loop}
						</td>
						<td class="manage" width="180">
							<a class="js-confirm" href="{u('developer/schema/dropindex/'.$table.'/'.$key)}">
								<i class="fa fa-times"></i>  {t('删除')}
							</a>
						</td>
					</tr>
				{/loop}
				</tbody>
			</table>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('数据表结构管理，在其它工具中修改后请刷新该页面')}</div>
	</div>
</div>
<style type="text/css">
	span.green,span.gray{font-family:tahoma;font-weight:bold;font-size:14px;}
	span.null{color: #999;}
</style>
<script type="text/javascript">
$(function(){
	var tablelist = $('#fields').data('tablelist');

	//底部全选
	// $('input.select-all').click(function(e){
	// 	tablelist.selectAll(this.checked);
	// });

	//操作
	$("a.operate").each(function(){
		$(this).on("click", function(event){
			event.preventDefault();
			if( tablelist.checked() == 0 ){
				$.error('{t('请选择要操作的项')}');
			}else{
				var href = $(this).attr('href');
				var text = $(this).text();
				var data = $('form').serializeArray();;
					data.push({name:'operation',value:text});
				$.loading();
				$.post(href,$.param(data),function(msg){
					$.msg(msg);
				},'json');
			}
		});
	});
});
</script>
{template 'footer.php'}