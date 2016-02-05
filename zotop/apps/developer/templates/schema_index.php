{template 'header.php'}

{template 'developer/project_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary btn-add js-open" href="{u('developer/schema/addfield/'.$table)}" data-width="800px" data-height="480px">
				{t('新建字段')}
			</a>
			<a class="btn js-open" href="{u('developer/schema/show/'.$table)}" data-width="800px" data-height="500px" title="{t('数据表安装代码')}">
				{t('安装代码')}
			</a>
		</div>
	</div>
	<div class="main-body scrollable">

		<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8">
				<form>
				<table class="table zebra list" id="fields">
					<caption class="caption-default">{t('字段')}</caption>
					<thead>
						<tr>
							<td class="select"><input type="checkbox" class="checkbox select-all"></td>
							<td class="hidden">{t('主键')}</td>
							<td class="w140">{t('字段')}</td>
							<td class="w80">{t('默认')}</td>
							<td>{t('说明')}</td>
							<td class="w60 text-center ">{t('自增')}</td>
							<td class="w80 text-center">{t('非空')}</td>
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
									<span class="text-muted">
										{$field['type']}{if $field[length]}({$field[length]}){/if} {if $field[unsigned]}unsigned{/if}
									</span>
								</div>
								<div class="manage">
									<a class="js-open" href="{u('developer/schema/editfield/'.$table.'/'.$key)}" data-width="800px" data-height="480px">
										{t('修改')}
									</a>
									<s>|</s>
									<a class="js-confirm" href="{u('developer/schema/dropfield/'.$table.'/'.$key)}">{t('删除')}</a>
								</div>
							</td>
							<td>{if isset($field['default'])}{htmlspecialchars($field['default'])}{elseif $field['notnull'] === false}<span class="null">NULL</span>{/if}</td>
							<td>{$field['comment']}</td>
							<td class="text-center">{if $field['autoinc']}<span class="green">√</span>{else}<span class="gray">×</span>{/if}</td>
							<td class="text-center">{if $field['notnull']}<span class="green">√</span>{else}<span class="gray">×</span>{/if}</td>
						</tr>
						{$i++}
					{/loop}
					<tbody>
				</table>
				</form>
			</div> <!-- col -->
			<div class="col-sm-4">
				<table class="table list">
					<caption class="caption-default">{t('索引')}</caption>
					<thead>
						<tr>
							<td class="w60">{t('键名')}</td>
							<td class="w60">{t('类型')}</td>
							<td>{t('字段')}</td>
							<td class="w20"></td>
						</tr>
					</thead>
					<tbody>
					{if !empty($schema['primary']) and is_array($schema['primary'])}
						<tr class="red">
							<td><div class="text-overflow text-danger" title="{$key}">PRIMARY</div></td>
							<td><span>PRIMARY</span></td>
							<td>
								{loop $schema['primary'] $i $f}
									{if $i>0}<hr />{/if}
									<div class="text-overflow f12"> {$f} </div>
									{$i++}
								{/loop}
							</td>
							<td><a class="js-confirm" href="{u('developer/schema/dropprimary/'.$table)}" title="{t('删除')}"><span class="red">×</span></a></td>
						</tr>

					{/if}

					{loop $schema[unique] $key $field}
						<tr>
							<td><div class="text-overflow" title="{$key}">{$key}</div></td>
							<td><span>UNIQUE</span></td>
							<td>
								{loop $field $i $f}
									{if $i>0}<hr />{/if}
									<div class="text-overflow f12">{if is_array($f)} {$f[0]} <span class="f12 gray">|</span> {$f[1]} {else} {$f} {/if}</div>
									{$i++}
								{/loop}
							</td>
							<td><a class="js-confirm" href="{u('developer/schema/dropindex/'.$table.'/'.$key)}" title="{t('删除')}"><span class="red">×</span></a></td>
						</tr>
					{/loop}

					{loop $schema[index] $key $field}
						<tr>
							<td><div class="text-overflow" title="{$key}">{$key}</div></td>
							<td><span>INDEX</span></td>
							<td>
								{loop $field $i $f}
									{if $i>0}<hr />{/if}
									<div class="text-overflow f12">{if is_array($f)} {$f[0]} <span class="f12 gray">|</span> {$f[1]} {else} {$f} {/if}</div>
									{$i++}
								{/loop}
							</td>
							<td><a class="js-confirm" href="{u('developer/schema/dropindex/'.$table.'/'.$key)}" title="{t('删除索引')}"><span class="red">×</span></a></td>
						</tr>
					{/loop}
					</tbody>
				</table>
			</div> <!-- col -->
		</div> <!-- row -->
		</div> 

	</div><!-- main-body -->
	<div class="main-footer">
		<input type="checkbox" class="checkbox select-all middle">
		<a class="btn operate" href="javascript:void(0)" id="field-datalist" style="display:none;">{t('浏览')}</a>
		<a class="btn operate" href="{u('developer/schema/operate/'.$table.'/primary')}"><span class="red">{t('主键')}</span></a>
		<a class="btn operate" href="{u('developer/schema/operate/'.$table.'/index')}">{t('索引')}</a>
		<a class="btn operate" href="{u('developer/schema/operate/'.$table.'/unique')}">{t('唯一索引')}</a>
		<a class="btn operate" href="{u('developer/schema/operate/'.$table.'/fulltext')}">{t('全文搜索')}</a>
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
	$('input.select-all').click(function(e){
		tablelist.selectAll(this.checked);
	});

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