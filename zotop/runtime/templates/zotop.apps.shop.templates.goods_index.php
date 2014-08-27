<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('shop/admin_side.php'); ?>
</div>


<div class="main side-main">
<div class="main-header">
<div class="title">
<?php if($keywords):?>
 <?php echo t('搜索 "%s"',$keywords);?>
<?php elseif($category['name']):?>
<?php echo $category['name'];?>
<?php else: ?>
 	<?php echo t('商品管理');?>
<?php endif; ?>
</div>

<?php if(!$keywords):?>
<ul class="navbar">
<?php $n=1; foreach(m('shop.goods.status') as $s => $t): ?>
<li<?php if($status == $s):?> class="current"<?php endif; ?>>
<a href="<?php echo u('shop/goods/index/'.$categoryid.'/'.$s);?>"><?php echo $t;?></a>
<?php if($statuscount[$s]):?><span class="f12 red">(<?php echo $statuscount[$s];?>)</span><?php endif; ?>
</li>
<?php $n++;endforeach;unset($n); ?>
</ul>
<?php endif; ?>

<form action="<?php echo u('shop/goods/index');?>" method="post" class="searchbar">
<?php if($keywords):?>
<input type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo t('关键词/商品编号');?>" style="width:200px;" x-webkit-speech/>
<?php else: ?>
<input type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo t('关键词/商品编号');?>" x-webkit-speech/>
<?php endif; ?>
<button type="submit"><i class="icon icon-search"></i></button>
</form>

<div class="action">
<?php if($status!='trash'):?>
<a class="btn btn-icon-text btn-highlight" href="<?php echo U('shop/goods/add/'.$categoryid);?>">
<i class="icon icon-add"></i><b><?php echo t('添加');?></b>
</a>
<?php endif; ?>
</div>
</div>
<div class="main-body scrollable">


<?php if(empty($data)):?>
<div class="nodata"><?php echo t('没有找到任何数据');?></div>
<?php else: ?>
<?php echo form::header();?>
<table class="table zebra list" id="datalist">
<thead>
<tr>
<td class="select"><input type="checkbox" class="checkbox select-all"></td>
<td class="w60 center"><?php echo t('权重');?></td>
<td><?php echo t('商品名称');?></td>
<td class="w160"><?php echo t('商品编号');?></td>
<?php if(!$categoryid):?>
<td class="w140"><?php echo t('商品分类');?></td>
<?php endif; ?>
<td class="w140"><?php echo t('商品类型');?></td>
<td class="w120"><?php echo t('发布');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $r['id'];?>"></td>
<td class="center">
<a class="dialog-prompt" data-value="<?php echo $r['weight'];?>" data-prompt="<?php echo t('请输入权重[0-100],权重越大越靠前');?>" href="<?php echo u('shop/goods/set/'.$r['id'].'/weight');?>" title="<?php echo t('设置权重');?>">
<span class="<?php if($r['weight']):?>red<?php else: ?>gray<?php endif; ?>"><?php echo $r['weight'];?></span>
</a>
</td>
<td>
<div class="title textflow"><?php echo $r['name'];?></div>
<div class="manage">

<a href="<?php echo u('shop/detail/'.$r['id']);?>" target="_blank"><?php echo t('访问');?></a>
<s></s>

<a href="<?php echo u('shop/goods/edit/'.$r['id']);?>"><?php echo t('编辑');?></a>
<s></s>

<?php if($r['status'] == 'publish'):?>
<a href="<?php echo u('shop/goods/set/'.$r['id'].'/status/disabled');?>" class="ajax-post"><?php echo t('下架');?></a>
<?php else: ?>
<a href="<?php echo u('shop/goods/set/'.$r['id'].'/status/publish');?>" class="ajax-post"><?php echo t('上架');?></a>
<?php endif; ?>
<s></s>

<?php if($r['status'] == 'trash'):?>
<a href="<?php echo u('shop/goods/delete/'.$r['id']);?>" class="dialog-confirm"><?php echo t('删除');?></a>
<?php else: ?>
<a href="<?php echo u('shop/goods/set/'.$r['id'].'/status/trash');?>" class="ajax-post"><?php echo t('删除');?></a>
<?php endif; ?>
</div>
</td>
<td><?php echo $r['sn'];?></td>
<?php if(!$categoryid):?>
<td><?php echo m('shop.category.get',$r['categoryid'],'name');?></td>
<?php endif; ?>
<td><?php echo m('shop.type.get',$r['typeid'],'name');?></td>
<td>
<div><?php echo m('system.user.get', $r['userid'], 'username');?></div>
<div class="f12 time"><?php echo format::date($r['createtime']);?></div>
</td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<tbody>
</table>
<?php echo form::footer();?>
<?php endif; ?>
</div>
<div class="main-footer">
<div class="pagination"><?php echo pagination::instance($total,$pagesize,$page);?></div>

<input type="checkbox" class="checkbox select-all middle">

<?php $n=1; foreach(m('shop.goods.status') as $s => $t): ?>
<?php if($status != $s):?>
<a class="btn operate" href="<?php echo u('shop/goods/operate/'.$s);?>" rel="<?php echo $s;?>"><?php echo $t;?></a>
<?php endif; ?>
<?php $n++;endforeach;unset($n); ?>

<a class="btn operate" href="<?php echo u('shop/goods/operate/weight');?>" rel="weight"><?php echo t('权重');?></a>

<a class="btn operate" href="<?php echo u('shop/goods/operate/move');?>" rel="move"><?php echo t('移动');?></a>

<a class="btn operate" href="<?php echo u('shop/goods/operate/delete');?>" rel="delete"><?php echo t('删除');?></a>
</div>
</div>

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

var $dialog = $.dialog({title:text, url:"<?php echo u('shop/category/select/'.$categoryid);?>", width:400, height:300, ok:function(categoryid, categoryname){

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
},cancel:function(){}},true);

}else if( rel == 'weight' ){

var $dialog = $.prompt('<?php echo t('请输入权重[0-100],权重越大越靠前');?>', function(newvalue){

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

</script>

<?php $this->display('footer.php'); ?>