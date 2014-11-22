<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('dialog.header.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/attachment.css" />

<div class="side">
<?php $this->display('system/attachment_side.php'); ?>
</div>

<div class="main side-main">
<div class="main-header">
<div class="title" style="line-height:32px;">
<a class="btn btn-highlight btn-upload" id="upload" href="javascript:void(0)"><i class="icon icon-upload"></i><?php echo t('上传%s', $typename);?></a>
</div>
<div class="action">
<?php if($type=='image'):?>
<span class="inline-radios">
<label><input type="radio" name="watermark" value="1" <?php if($params['watermark']==1):?>checked="checked"<?php endif; ?>><?php echo t('水印');?></label>
<label><input type="radio" name="watermark" value="0" <?php if($params['watermark']==0):?>checked="checked"<?php endif; ?>><?php echo t('无');?></label>
</span>
<?php endif; ?>
</div>
</div>
<div class="main-body" id="upload-dragdrop">
<div class="filelist" id="filelist"></div>
</div><!-- main-body -->
<div class="main-footer">
<div id="upload-progress" class="total-progressbar progressbar"><span class="progress"></span><span class="percent">10%</span></div>
</div>
</div><!-- main -->

<script id="fileitem-template" type="text/x-jsrender">
<@[for data]>
<div class="fileitem clearfix">
<div class="preview">
<[if type=='image']>
<div class="image"><img data-link="src[:url:]"/></div>
<[else]>
<div class="icon"><b class="icon-ext icon-<[:type]> icon-<[:ext]>"></b><b class="ext"><[:ext]></b></div>
<[/if]>
</div>
<div class="title">
<div class="name textflow"><[:name]></div>
<div class="info"><[size size/]> <[if width>0]> <[:width]>px × <[:height]>px <[/if]> </div>

</div>
<div class="action"><a class="delete" title="<?php echo t('删除');?>"><i class="icon icon-delete"></i></a></div>
<i class="icon icon-selected"></i>
</div>
<[else]>
<div class="dragdrop none"><?php echo t('拖动文件到此区域上传');?></div>
<[/for]>
</script>

<!-- 模板绑定 -->
<script type="text/javascript" src="<?php echo a('system.url');?>/common/js/jquery.views.min.js"></script>
<!-- 上传 -->
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/plupload/plupload.css" />
<script type="text/javascript" src="<?php echo A('system.url');?>/common/plupload/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/plupload/jquery.upload.js"></script>
<script type="text/javascript">
// 参数
var params = <?php echo json_encode($params);?>;

// 视图
var view = {};

view.data = <?php echo json_encode($files);?>;

view.add  = function(row){

$.observable(view.data).insert(view.data.length, row);

if ( params.select == 1 ) {
$('.fileitem:last').addClass('selected').siblings(".fileitem").removeClass('selected'); //单选
} else {
$('.fileitem:last').addClass("selected");
}
};

view.del = function(e){e.stopPropagation();

var index = $.view(this).index;

$.confirm("<?php echo t('您确定要删除该文件嘛?');?>",function(){

$.get("<?php echo u('system/attachment/delete');?>",{id : view.data[index].id}, function(msg){
if( msg.state ){
$.observable(view.data).remove(index);
}else{
alert(msg.content);
}
},'json');

},function(){});
};

view.select = function(e){e.preventDefault();

if ( $(this).hasClass('selected') ) {
$(this).removeClass("selected");
} else if ( params.select == 1 ) {
$(this).addClass('selected').siblings(".fileitem").removeClass('selected'); //单选
} else {
$(this).addClass("selected");
}

return false;
}

view.selected = function(){

var index, selected = [];

$('#filelist').find('.selected').each(function(){
index = $.view(this).index;
selected.push(view.data[index])
});

return selected;
};


// 模板绑定
$.templates("#fileitem-template").link("#filelist", view)
.on("click", ".fileitem", view.select)
.on("click", ".delete", view.del);

$(function(){

// 上传
var uploader = $("#upload").upload({
url : "<?php echo u('system/attachment/uploadprocess');?>",
multi:true,
params : params,
maxsize:'<?php echo intval($maxsize);?>mb',
fileext: '<?php echo $allowexts;?>',
filedescription : '<?php echo t('选择%s',$typename);?>',
uploaded : function(up,file,data){
view.add(data);
},
error : function(error,detail){
$.error(detail);
}
});

// 改变水印参数
$('[name=watermark]').on('click',function(){
uploader.params('watermark', $(this).val());
});
});
</script>

<script type="text/javascript">
//选择文件个数
var select = params.select;

// 对话框设置
$dialog.callbacks['ok'] = function(){

var selected = view.selected();

if ( selected.length == 0 ){
$.error('<?php echo t('请选择要插入的文件');?>');
return false;
}

$dialog.ok(selected);
return true;
};

$dialog.title('<?php echo t('插入%s', $typename);?>');
</script>
<?php $this->display('dialog.footer.php'); ?>