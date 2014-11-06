
		<table class="field">
			<caption>{t('数据表及字段对应关系')}</caption>
			<tbody>

			<tr>
				<td class="label">{form::label(t('源数据表'),'table',true)}</td>
				<td class="input">
					{form::field(array('type'=>'select','name'=>'source[table]','options'=>$source_tables,'value'=>$data['source']['table']))}

					{form::field(array('type'=>'text','name'=>'source[condition]','value'=>$data['source']['condition'],'placeholder'=>t('提取条件，一般为sql语句中的where部分')))}

					{form::tips(t('将源数据表中符合提取条件的的数据对应导入目标数据表，无提取条件则提取全部数据'))}
				</td>
			</tr>	

			<tr>
				<td class="label">{form::label(t('目标数据表'),'table',true)}</td>
				<td class="input">
					{form::field(array('type'=>'select','name'=>'table','options'=>$tables,'value'=>$data['table']))}
				</td>
			</tr>			

			
			<tr>
				<td class="label">{form::label(t('字段对应关系'),'maps',false)}</td>
				<td class="input">

					<table class="table zebra border auto">
						<thead>
							<tr>
								<td>{t('目标数据表字段')}</td>
								<td>{t('源数据表字段')}</td>
								<td>{t('默认值')}</td>
								<td>{t('处理函数')}</td>
							</tr>
						</thead>
						{loop $fields $key $field}							
							<tr>
								<td>{$key} {if $field.comment} ({$field.comment}) {/if}</td>
								<td>
									<select class="select short" name="maps[{$key}][field]">
										<option value="">{t('无')}</option>
										{loop $source_fields $k $f}
											<option value="{$k}" {if $data['maps'][$key]['field']==$k}selected{/if}>{$k}  {if $f.comment} ({$f.comment}) {/if}</option>
										{/loop}
									</select>
								</td>
								<td><input type="text" class="text short" name="maps[{$key}][default]" value="{$data['maps'][$key]['default']}"></td>
								<td><input type="text" class="text short" name="maps[{$key}][function]" value="{$data['maps'][$key]['function']}"></td>
							</tr>

						{/loop}
					</table>
				</td>
			</tr>
		</table>
		

<script type="text/javascript">
	
	$(function(){
		$('form.form').find('.submit').disable(false);

		$('[name=table], [name="source[table]"]').on('change',function(){
			getmaps();
		})		
	})	

</script>

{template 'footer.php'}