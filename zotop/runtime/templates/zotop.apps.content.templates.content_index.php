<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('content/side.php'); ?>
</div>

<div class="main side-main">
<div class="main-header">

<div class="title">
<?php if($keywords):?> <?php echo t('搜索 "%s"',$keywords);?> <?php elseif($categoryid):?>	<?php echo $category['name'];?>	<?php else: ?> <?php echo t('内容管理');?> <?php endif; ?>
</div>

<?php if(!$keywords):?>
<ul class="navbar">
<?php $n=1; foreach($statuses as $s => $t): ?>
<li<?php if($status == $s):?> class="current"<?php endif; ?>>
<a href="<?php echo u('content/content/index/'.$categoryid.'/'.$s);?>"><?php echo $t;?></a>
<?php if($statuscount[$s]):?><span class="f12 red">(<?php echo $statuscount[$s];?>)</span><?php endif; ?>
</li>
<?php $n++;endforeach;unset($n); ?>
</ul>
<?php endif; ?>

<form action="<?php echo u('content/content/index');?>" method="post" class="searchbar">
<?php if($keywords):?>
<input type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo t('请输入关键词');?>" style="width:200px;" x-webkit-speech/>
<?php else: ?>
<input type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo t('请输入关键词');?>" x-webkit-speech/>
<?php endif; ?>
<button type="submit"><i class="icon icon-search"></i></button>
</form>

<div class="action">

<?php if(count($postmodels) < 2):?>

<?php $n=1; foreach($postmodels as $i => $m): ?>
<a class="btn btn-highlight btn-icon-text" href="<?php echo u('content/content/add/'.$categoryid.'/'.$m['id']);?>" title="<?php echo $m['description'];?>">
<i class="icon icon-add"></i><b><?php echo $m['name'];?></b>
</a>
<?php $n++;endforeach;unset($n); ?>

<?php else: ?>
<div class="menu btn-menu">
<a class="btn btn-highlight btn-icon-text" href="javascript:void(0);"><i class="icon icon-add"></i><b><?php echo t('添加');?></b><b class="arrow"></b></a>
<div class="dropmenu">
<div class="dropmenulist">
<?php $n=1; foreach($postmodels as $i => $m): ?>
<a href="<?php echo u('content/content/add/'.$categoryid.'/'.$m['id']);?>" data-placement="right" title="<?php echo $m['description'];?>"><i class="icon icon-item icon-<?php echo $m['id'];?>"></i><?php echo $m['name'];?></a>
<?php $n++;endforeach;unset($n); ?>
</div>
</div>
</div>
<?php endif; ?>

<?php if($categoryid):?>
<a class="btn btn-icon-text" href="<?php echo u($category['url']);?>" target="_blank" title="<?php echo t('访问栏目');?>">
<i class="icon icon-open"></i><b><?php echo t('访问');?></b>
</a>
<?php endif; ?>
</div>

</div><!-- main-header -->

<div class="main-body scrollable">
<?php echo form::header();?>
<table class="table list" id="datalist" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td class="select"><input type="checkbox" class="checkbox select-all"></td>
<?php if($keywords):?>
<td class="w40 center"><?php echo t('状态');?></td>
<?php endif; ?>
<td class="w60 center"><?php echo t('权重');?></td>
<td><?php echo t('标题');?></td>
<td class="w80 center"><?php echo t('点击');?></td>
<td class="w80"><?php echo t('模型');?></td>
<td class="w100"><?php echo t('栏目');?></td>
<td class="w140"><?php echo t('发布者/发布时间');?></td>
</tr>
</thead>
<tbody>
<?php if(empty($data)):?>
<tr class="nodata"><td colspan="4"><div class="nodata"><?php echo t('暂时没有任何数据');?></div></td></tr>
<?php else: ?>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $r['id'];?>"></td>
<?php if($keywords):?>
<td class="center"><i class="icon icon-<?php echo $r['status'];?> <?php echo $r['status'];?>" title="<?php echo $statuses[$r['status']];?>"></i></td>
<?php endif; ?>
<td class="center">
<a class="dialog-prompt" data-value="<?php echo $r['weight'];?>" data-prompt="<?php echo t('请输入权重[0-99],权重越大越靠前');?>" href="<?php echo u('content/content/set/weight/'.$r['id']);?>" title="<?php echo t('设置权重');?>">
<span class="<?php if($r['weight']):?>red<?php else: ?>gray<?php endif; ?>"><?php echo $r['weight'];?></span>
</a>
</td>
<td>
<div class="title textflow" <?php if($r['style']):?>style="<?php echo $r['style'];?>"<?php endif; ?>>
<?php echo $r['title'];?><?php if($r['thumb']):?><i class="icon icon-image" data-src="<?php echo $r['thumb'];?>"></i><?php endif; ?>
</div>
<div class="manage">
<a href="<?php echo $r['url'];?>" target="_blank"><?php echo t('访问');?></a>
<s></s>

<a href="<?php echo u('content/content/edit/'.$r['id']);?>"><?php echo t('编辑');?></a>
<s></s>

<?php $n=1; foreach(zotop::filter('content.manage',array(),$r) as $m): ?>
<a href="<?php echo $m['href'];?>" <?php echo $m['attr'];?>><?php echo $m['text'];?></a>
<s></s>
<?php $n++;endforeach;unset($n); ?>

<a class="dialog-confirm" href="<?php echo u('content/content/delete/'.$r['id']);?>"><?php echo t('删除');?></a>
</div>
</td>
<td class="center"><?php echo $r['hits'];?></td>
<td><div class="textflow"><?php echo $models[$r['modelid']]['name'];?></div></td>
<td><div class="textflow"><?php echo $categorys[$r['categoryid']]['name'];?></div></td>
<td>
<div><?php echo m('system.user.get', $r['userid'], 'username');?></div>
<div class="f12 time"><?php echo format::date($r['createtime']);?></div>
</td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<?php endif; ?>
</tbody>
</table>
<?php echo form::footer();?>
</div><!-- main-body -->
<div class="main-footer">

<div class="pagination"><?php echo pagination::instance($total,$pagesize,$page);?></div>

<input type="checkbox" class="checkbox select-all middle">

<?php $n=1; foreach($statuses as $s => $t): ?>
<?php if($status != $s):?>
<a class="btn operate" href="<?php echo u('content/content/operate/'.$s);?>" rel="<?php echo $s;?>"><?php echo $t;?></a>
<?php endif; ?>
<?php $n++;endforeach;unset($n); ?>

<a class="btn operate" href="<?php echo u('content/content/operate/weight');?>" rel="weight"><?php echo t('权重');?></a>

<a class="btn operate" href="<?php echo u('content/content/operate/move');?>" rel="move"><?php echo t('移动');?></a>

<a class="btn operate" href="<?php echo u('content/content/operate/delete');?>" rel="delete"><?php echo t('删除');?></a>

</div><!-- main-footer -->

</div><!-- main -->

<script type="text/javascript">
$(function(){
var tablelist = $('#datalist').data('tablelist');

//底部全选
$('input.select-all').on('click',function(e){
tablelist.selectAll(this.checked);
});

//操作
$("a.operate").each(function(){
$(this).on("click", function(event){ event.preventDefault();

if( tablelist.checked() == 0 ){
$.error('<?php echo t('请选择要操作的项');?>');
return false;
}

var rel = $(this).attr('rel');
var href = $(this).attr('href');
var text = $(this).text();
var data = $('form').serializeArray();

if ( rel == 'move' ) {

var $dialog = $.dialog({
title:text,
url:"<?php echo u('content/category/select/'.$categoryid);?>",
width:400,
height:300,
ok:function(categoryid){
if ( categoryid ){
data.push({name:'categoryid',value:categoryid});
$.loading();
$.post(href,$.param(data),function(msg){
if( msg.state ){
$dialog['close']();
}
$.msg(msg);
},'json');
}
return false;
},
cancel:function(){}
},true);

}else if( rel == 'weight' ){

var $dialog = $.prompt('<?php echo t('请输入权重[0-99],权重越大越靠前');?>', function(newvalue){

data.push({name:'weight',value:newvalue});

$.loading();
$.post(href, $.param(data), function(msg){
if( msg.state ){
$dialog['close']();
}
$.msg(msg);
},'json');

return false;

}, '0').title(text);

}else{
$.loading();
$.post(href,$.param(data),function(msg){
$.msg(msg);
},'json');
}

return true;
});

});
});

$(function(){
$('.icon-image').tooltip({placement:'auto bottom',container:'body',html:true,title:function(){
return '<p style="margin-bottom:8px;font-size:14px;"><?php echo t('缩略图');?></p><img src="'+$(this).attr('data-src')+'" style="max-width:400px;max-height:300px;"/>';
}});
});
</script>
<?php $this->display('footer.php'); ?>