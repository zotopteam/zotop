<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side main-side" style="width:300px;">
<div class="side-header"><?php echo t('索引信息');?></div>
<div class="side-body scrollable">
<table class="table list">
<thead>
<tr>
<td class="w60"><?php echo t('键名');?></td>
<td class="w60"><?php echo t('类型');?></td>
<td><?php echo t('字段');?></td>
<td class="w20"></td>
</tr>
</thead>
<tbody>
<?php if(!empty($schema['primary']) and is_array($schema['primary'])):?>
<tr class="red">
<td><div class="textflow red" title="<?php echo $key;?>">PRIMARY</div></td>
<td><span class="f12">PRIMARY</span></td>
<td>
<?php $n=1; foreach($schema['primary'] as $i => $f): ?>
<?php if($i>0):?><hr /><?php endif; ?>
<div class="textflow f12"> <?php echo $f;?> </div>
<?php $i++; ?>
<?php $n++;endforeach;unset($n); ?>
</td>
<td><a class="dialog-confirm" href="<?php echo u('developer/schema/dropprimary/'.$table);?>" title="<?php echo t('删除');?>"><span class="red">×</span></a></td>
</tr>

<?php endif; ?>

<?php $n=1; foreach($schema[unique] as $key => $field): ?>
<tr>
<td><div class="textflow" title="<?php echo $key;?>"><?php echo $key;?></div></td>
<td><span class="f12">UNIQUE</span></td>
<td>
<?php $n=1; foreach($field as $i => $f): ?>
<?php if($i>0):?><hr /><?php endif; ?>
<div class="textflow f12"><?php if(is_array($f)):?> <?php echo $f['0'];?> <span class="f12 gray">|</span> <?php echo $f['1'];?> <?php else: ?> <?php echo $f;?> <?php endif; ?></div>
<?php $i++; ?>
<?php $n++;endforeach;unset($n); ?>
</td>
<td><a class="dialog-confirm" href="<?php echo u('developer/schema/dropindex/'.$table.'/'.$key);?>" title="<?php echo t('删除');?>"><span class="red">×</span></a></td>
</tr>
<?php $n++;endforeach;unset($n); ?>

<?php $n=1; foreach($schema[index] as $key => $field): ?>
<tr>
<td><div class="textflow" title="<?php echo $key;?>"><?php echo $key;?></div></td>
<td><span class="f12">INDEX</span></td>
<td>
<?php $n=1; foreach($field as $i => $f): ?>
<?php if($i>0):?><hr /><?php endif; ?>
<div class="textflow f12"><?php if(is_array($f)):?> <?php echo $f['0'];?> <span class="f12 gray">|</span> <?php echo $f['1'];?> <?php else: ?> <?php echo $f;?> <?php endif; ?></div>
<?php $i++; ?>
<?php $n++;endforeach;unset($n); ?>
</td>
<td><a class="dialog-confirm" href="<?php echo u('developer/schema/dropindex/'.$table.'/'.$key);?>" title="<?php echo t('删除索引');?>"><span class="red">×</span></a></td>
</tr>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>
</div><!-- side-body -->
</div>
<div class="main main-side" style="right:306px;">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="position">
<a href="<?php echo u('developer');?>"><?php echo t('开发助手');?></a>
<s class="arrow">></s>
<a href="<?php echo u('developer/project');?>"><?php echo $project['name'];?></a>
<s class="arrow">></s>
<?php echo t('数据表');?> : <?php echo $table;?>
</div>
<div class="action">
<a class="btn btn-highlight btn-add dialog-open" href="<?php echo u('developer/schema/addfield/'.$table);?>" data-width="800px" data-height="480px">
<?php echo t('新建字段');?>
</a>
<a class="btn dialog-open" href="<?php echo u('developer/schema/show/'.$table);?>" data-width="800px" data-height="500px" title="<?php echo t('安装代码');?>">
<?php echo t('安装代码');?>
</a>
</div>
</div>
<div class="main-body scrollable">
<form>
<table class="table zebra list" id="fields">
<thead>
<tr>
<td class="select"><input type="checkbox" class="checkbox select-all"></td>
<td class="w40 center none"><?php echo t('主键');?></td>
<td class="w140"><?php echo t('名称');?></td>
<td class="w200"><?php echo t('类型');?></td>
<td class="w80"><?php echo t('默认值');?></td>
<td><?php echo t('说明');?></td>
<td class="w80 center "><?php echo t('自增');?></td>
<td class="w80 center"><?php echo t('NOT NULL');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($schema[fields] as $key => $field): ?>
<tr class="<?php if($i%2==0):?>even<?php else: ?>odd<?php endif; ?>">
<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $key;?>"></td>
<td class="center none"><?php if(in_array($key,$schema[primary])):?><span class="green">√</span><?php endif; ?></td>
<td>
<div class="title"><b class="name<?php if(in_array($key,$schema[primary])):?> red<?php endif; ?>"><?php echo $key;?></b></div>
<div class="manage">
<a class="dialog-open" href="<?php echo u('developer/schema/editfield/'.$table.'/'.$key);?>" data-width="800px" data-height="480px">
<?php echo t('编辑');?>
</a>
<a class="dialog-confirm" href="<?php echo u('developer/schema/dropfield/'.$table.'/'.$key);?>"><?php echo t('删除');?></a>
</div>
</td>
<td><?php echo $field['type'];?><?php if($field[length]):?>(<?php echo $field['length'];?>)<?php endif; ?> <?php if($field[unsigned]):?>unsigned<?php endif; ?></td>
<td><?php if(isset($field['default'])):?><?php echo htmlspecialchars($field['default']);?><?php elseif($field['notnull'] === false):?>NULL<?php endif; ?></td>
<td><?php echo $field['comment'];?></td>
<td class="center"><?php if($field['autoinc']):?><span class="green">√</span><?php else: ?><span class="gray">×</span><?php endif; ?></td>
<td class="center"><?php if($field['notnull']):?><span class="green">√</span><?php else: ?><span class="gray">×</span><?php endif; ?></td>
</tr>
<?php $i++; ?>
<?php $n++;endforeach;unset($n); ?>
<tbody>
</table>
</form>
</div><!-- main-body -->
<div class="main-footer">
<input type="checkbox" class="checkbox select-all middle">
<a class="btn operate" href="javascript:void(0)" id="field-datalist" style="display:none;"><?php echo t('浏览');?></a>
<a class="btn operate" href="<?php echo u('developer/schema/operate/'.$table.'/primary');?>"><span class="red"><?php echo t('主键');?></span></a>
<a class="btn operate" href="<?php echo u('developer/schema/operate/'.$table.'/index');?>"><?php echo t('索引');?></a>
<a class="btn operate" href="<?php echo u('developer/schema/operate/'.$table.'/unique');?>"><?php echo t('唯一索引');?></a>
<a class="btn operate" href="<?php echo u('developer/schema/operate/'.$table.'/fulltext');?>"><?php echo t('全文搜索');?></a>
</div>
</div>
<style type="text/css">
span.green,span.gray{font-family:tahoma;font-weight:bold;font-size:14px;}
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
$.error('<?php echo t('请选择要操作的项');?>');
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
<?php $this->display('footer.php'); ?>