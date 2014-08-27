<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('member/admin_side.php'); ?>
</div><!-- side -->

<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<form action="<?php echo u('member/admin/index');?>" method="post" class="searchbar">
<?php if($keywords):?>
<input type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo t('请输入关键词');?>" style="width:200px;" x-webkit-speech/>
<?php else: ?>
<input type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo t('请输入关键词');?>" x-webkit-speech/>
<?php endif; ?>
<button type="submit"><i class="icon icon-search"></i></button>
</form>
<div class="action">
<?php if(count($models) == 1):?>
<?php $n=1; foreach($models as $m): ?>
<a class="btn btn-highlight" href="<?php echo u('member/admin/add/'.$m['id']);?>"><?php echo t('添加%s',$m['name']);?></a>
<?php $n++;endforeach;unset($n); ?>
<?php else: ?>
<div class="menu btn-menu">
<a class="btn btn-highlight" href="javascript:void(0);"><?php echo t('添加会员');?><b class="arrow"></b></a>
<div class="dropmenu">
<div class="dropmenulist">
<?php $n=1; foreach($models as $m): ?>
<?php if(!$m['disabled']):?>
<a href="<?php echo u('member/admin/add/'.$m['id']);?>" data-placement="right" title="<?php echo $m['description'];?>"><?php echo t('添加%s',$m['name']);?></a>
<?php endif; ?>
<?php $n++;endforeach;unset($n); ?>
</div>
</div>
</div>
<?php endif; ?>
</div>
</div>
<?php echo form::header();?>
<div class="main-body scrollable">
<?php if(empty($data)):?>
<div class="nodata"><?php echo t('没有找到任何数据');?></div>
<?php else: ?>
<table class="table zebra list">
<thead>
<tr>
<td class="select"><input type="checkbox" class="checkbox select-all"></td>
<td class="w40 center"><?php echo t('状态');?></td>
<td><?php echo t('用户名');?> (<?php echo t('昵称');?>)</td>
<td class="w140"><?php echo t('会员模型/会员组');?></td>
<td class="w60"><?php echo t('积分');?></td>
<td class="w60"><?php echo t('金钱');?></td>
<td class="w120"><?php echo t('手机');?></td>
<td class="w80"><?php echo t('邮箱');?></td>
<td class="w120"><?php echo t('最后登录');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $r['id'];?>"></td>
<td class="center">	<?php if($r['disabled']):?><i class="icon icon-false false" title="<?php echo t('已禁用');?>"></i><?php else: ?><i class="icon icon-true true" title="<?php echo t('正常');?>"></i><?php endif; ?></td>
<td>
<div class="title textflow"><?php echo $r['username'];?> <span>( <?php echo $r['nickname'];?> )</span></div>
<div class="manage">
<a href="<?php echo u('member/admin/edit/'.$r['id']);?>"><?php echo t('编辑');?></a>
<s></s>
<a href="<?php echo u('member/admin/delete/'.$r['id']);?>" class="dialog-confirm"><?php echo t('删除');?></a>
</div>
</td>
<td><?php echo $models[$r['modelid']]['name'];?><br/><?php echo $groups[$r['groupid']]['name'];?></td>
<td><?php echo $r['point'];?></td>
<td><?php echo $r['amount'];?></td>
<td>
<div class="textflow">
<?php if($r['mobile']):?>
<?php if($r['mobilestatus']):?><i class="icon icon-true true" title="<?php echo t('已验证');?>"></i><?php else: ?><i class="icon icon-false false" title="<?php echo t('未验证');?>"></i><?php endif; ?>
<?php echo $r['mobile'];?>
<?php else: ?>
<span class="gray"><?php echo t('未填');?></span>
<?php endif; ?>
</div>
</td>
<td>
<span title="<?php echo $r['email'];?>">
<?php if($r['emailstatus']):?>
<i class="icon icon-true true"></i> <?php echo t('已验证');?>
<?php else: ?>
<i class="icon icon-false false"></i> <?php echo t('未验证');?>
<?php endif; ?>
</span>
</td>
<td>
<?php if(intval($r['logintimes'])>0):?>
<div><?php echo $r['loginip'];?></div>
<div class="f12"><?php echo format::date($r['logintime']);?></div>
<?php else: ?>
<?php echo t('尚未登录');?>
<?php endif; ?>
</td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<tbody>
</table>
<?php endif; ?>
</div>
<?php echo form::footer();?>
<div class="main-footer">

<div class="pagination"><?php echo pagination::instance($total,$pagesize,$page);?></div>

<input type="checkbox" class="checkbox select-all middle">

<a class="btn operate" href="<?php echo u('member/admin/operate/disabled/1');?>" rel="status"><?php echo t('禁用');?></a>
<a class="btn operate" href="<?php echo u('member/admin/operate/disabled/0');?>" rel="status"><?php echo t('启用');?></a>
<a class="btn operate" href="<?php echo u('member/admin/operate/move');?>" rel="move" style="display:none;"><?php echo t('移动');?></a>
<a class="btn operate" href="<?php echo u('member/admin/operate/delete');?>" rel="delete"><?php echo t('删除');?></a>

</div><!-- main-footer -->
</div>


<script type="text/javascript">
$(function(){
var tablelist = $('table.list').data('tablelist');

//底部全选
$('input.select-all').on('click',function(e){
tablelist.selectAll(this.checked);
});

//操作
$("a.operate").each(function(){
$(this).on("click", function(event){

event.preventDefault();

if( tablelist.checked() == 0 ){
$.error('<?php echo t('请选择要操作的项');?>');
return false;
}

var rel = $(this).attr('rel');
var href = $(this).attr('href');
var text = $(this).text();
var data = $('form').serializeArray();
data.push({name:'operation',value:text});

if ( rel == 'move' ) {

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