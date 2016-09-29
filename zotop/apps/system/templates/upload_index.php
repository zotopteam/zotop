{template 'dialog.header.php'}

{template 'system/upload_side.php'}

<div class="main side-main no-footer">
	<div class="main-header">
		<a class="btn btn-primary btn-upload pull-left" id="upload" href="javascript:void(0)">
			<i class="fa fa-upload fa-fw"></i><b>{t('上传%s', $typename)}</b>
		</a>			

		<div class="action">
			{if $type=='image'}
			<label class="btn">
				<input type="checkbox" name="watermark" value="1" {if $params['watermark']==1}checked="checked"{/if}> {t('水印')}
			</label>
			{/if}		
		</div>
	</div>
	<div class="main-body scrollable" id="upload-dragdrop">
		<div class="container-fluid">
			<div class="filelist" id="filelist"></div>				
		</div>
	</div><!-- main-body -->
    <div class="main-body main-progress hidden" id="upload-progress">
        <h1></h1>
        <h2>{t('正在上传')} <span class="progress-percent">0%</span></h2>
        <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:1%">
                
            </div>
        </div>
    </div>    
	<div class="main-footer">
	</div>
</div><!-- main -->

<script id="fileitem-template" type="text/x-jsrender">
	<@[for data]>
	<div class="fileitem clearfix">
		<div class="preview">
			<[if type=='image']>
			<div class="image"><img data-link="src[:url:]"/></div>
			<[else]>
			<div class="icon"><b class="fa fa-<[:type]> fa-<[:ext]>" ></b><b class="ext"><[:ext]></b></div>
			<[/if]>
		</div>
		<div class="title">
			<div class="name text-overflow"><[:name]></div>
			<div class="info text-overflow"><[size size/]> <[if width>0]> <[:width]>px × <[:height]>px <[/if]> </div>
		</div>
		<div class="action"><a class="delete" title="{t('删除')}"><i class="fa fa-times"></i></a></div>
	</div>
	<[else]>

		<div class="upload-help">
			{t('暂时没有文件，请先上传')}
			<div class="upload-dragdrop-tips">{t('拖动文件到此区域可上传')}</div>
		</div>
	<[/for]>
</script>

<!-- 模板绑定 -->
<script type="text/javascript" src="{a('system.url')}/assets/js/jquery.views.min.js"></script>
<!-- 上传 -->
<link rel="stylesheet" type="text/css" href="{A('system.url')}/assets/plupload/plupload.css" />
<script type="text/javascript" src="{A('system.url')}/assets/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/jquery.upload.js"></script>
<script type="text/javascript">
	// 参数
	var params = {json_encode($params)};

	// 视图
	var view = {};

		view.data = {json_encode($files)};

		view.add  = function(row){

			$.observable(view.data).insert(view.data.length, row);

			if ( params.select == 1 ) {
				$('.fileitem:last').addClass('selected').siblings(".fileitem").removeClass('selected'); //单选
			} else {
				$('.fileitem:last').addClass("selected");
			}

			$('#filelist').scrollTop($('#filelist')[0].scrollHeight);
		};

		view.del = function(e){e.stopPropagation();

			var index = $.view(this).index;

			$.confirm("{t('您确定要删除该文件嘛?')}",function(){

				$.get("{u('system/attachment/delete')}",{id : view.data[index].id}, function(msg){
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
				selected.push(view.data[index]);
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
			url : "{u('system/upload/uploadprocess')}",
			multiple: true,
            //chunk_size:'100kb',
			params: params,
			maxsize:'{intval($maxsize)}mb',
			extensions: '{$allowexts}',
			title : '{t('选择%s',$typename)}',
			uploaded : function(up,file,msg){				
				if ( msg.state ){
					view.add(msg.file);
					return true;
				}
				$.msg(msg);
			},
			error : function(error,detail){
				$.error(detail);
			}
		});

		// 改变水印参数
		$('[name=watermark]').on('click',function(){
			uploader.params('watermark', ($(this).is(':checked') ? 1 : 0 ));
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
				$.error('{t('请选择要插入的文件')}');
				return false;
			}

			$dialog.ok(selected);
			return true;
	};

	$dialog.title('{t('插入%s', $typename)}');
</script>
{template 'dialog.footer.php'}