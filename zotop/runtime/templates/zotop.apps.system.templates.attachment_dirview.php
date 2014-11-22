<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('dialog.header.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/attachment.css" />

<div class="side">
<?php $this->display('system/attachment_side.php'); ?>
</div>

<div class="main side-main no-footer">
<div class="main-header">
<div class="position">
<?php echo t('当前位置');?> :
<?php $n=1; foreach($position as $n => $p): ?>
<?php if($n):?><s class="arrow">></s><?php endif; ?> <a href="<?php echo $p['url'];?>"><?php echo $p['text'];?></a>
<?php $n++;endforeach;unset($n); ?>
</div>
</div>
<div class="main-body">
<div class="filelist-body filelist" id="filelist">
<?php $n=1; foreach($folders as $f): ?>
<a href="<?php echo $f['url'];?>">
<div class="fileitem folder clearfix">
<div class="preview">
<div class="icon"><b class="icon icon-folder"></b></div>
</div>
<div class="title textflow"><?php echo $f['name'];?></div>
</div>
</a>
<?php $n++;endforeach;unset($n); ?>

<?php $n=1; foreach($files as $f): ?>
<div class="fileitem file clearfix" id="<?php echo $f['id'];?>" data-name="<?php echo $f['name'];?>" data-url="<?php echo $f['url'];?>" data-size="<?php echo $f['size'];?>" data-ext="<?php echo $f['ext'];?>" title="<?php echo t('名称');?> : <?php echo $f['name'];?>&#10;<?php echo t('大小');?> : <?php echo format::size($f['size']);?><?php if($f['width']):?>&#10;<?php echo t('宽高');?> : <?php echo $f['width'];?>px × <?php echo $f['height'];?>px<?php endif; ?>">
<i class="icon icon-selected"></i>
<div class="preview">
<?php if($f['type'] == 'image'):?>
<div class="image"><img src="<?php echo $f['url'];?>"></div>
<?php else: ?>
<div class="icon"><b class="icon-ext icon-<?php echo $f['type'];?> icon-<?php echo $f['ext'];?>"></b><b class="ext"><?php echo $f['ext'];?></b></div>
<?php endif; ?>
</div>
<div class="title textflow"><?php echo $f['name'];?></div>
</div>
<?php $n++;endforeach;unset($n); ?>
</div>
</div><!-- main-body -->
</div><!-- main -->
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/plupload/plupload.css" />
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.pagination.js"></script>
<script type="text/javascript">

//选择文件个数
var select = <?php echo intval($select);?>;

// 对话框设置
$dialog.callbacks['ok'] = function(){

var $selected = $('#filelist').find('.selected');

if ( $selected['length'] == 0 ){
$.error('<?php echo t('请选择要插入的文件');?>');
return false;
}

var insert = new Array();

$selected.each(function(){
insert.push({'name':$(this).attr('data-name'), 'url':$(this).attr('data-url')})
});

$dialog.ok(insert);
return true;

};

$dialog.title('<?php echo t('插入%s', $typename);?>');


//选择文件
$('#filelist').on('click','.file',function(e){
//当点击为按钮时，禁止选择
if( $(e.target).parent().attr('tagName') == 'A' ) return false;

if ( $(this).hasClass('selected') ) {
$(this).removeClass("selected");
}else{
if ( select == 1 ) {
$(this).addClass('selected').siblings(".fileitem").removeClass('selected'); //单选
} else {
var num = $('.selected').length;
if( select>1 && num > select ) {
alert("<?php echo t('最多允许选择 %s 个文件',$select);?>");
return false;
}else{
$(this).addClass("selected");
}
}
}

return false;
});
</script>
<?php $this->display('dialog.footer.php'); ?>