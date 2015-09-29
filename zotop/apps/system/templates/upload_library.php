{template 'dialog.header.php'}

{template 'system/upload_side.php'}

<div class="main side-main no-footer">
	<div class="main-header">
		<div class="btn-group">
		  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  {if $folderid} {m('system.attachment_folder.category',$folderid,'name')} {else} {t('全部')} {/if} <span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu">
		  	<li><a href="{U('system/upload/library/'.$type, $_GET)}"><i class="fa fa-folder fa-fw"></i> {t('全部')}</a></li>
		  	{loop  m('system.attachment_folder.category') $f}
		    <li><a href="{U('system/upload/library/'.$type.'/'.$f.id, $_GET)}"><i class="fa fa-folder fa-fw"></i> {$f.name}</a></li>
		    {/loop}
		  </ul>
		</div>

		<div class="action">
			<a href="javascript:location.reload();" class="btn btn-default btn-icon" title="{t('刷新')}"><span class="fa fa-refresh"></span></a>
		</div>
	</div>
	<div class="main-body scrollable">
		
		<div class="container-fluid hidden">
			{loop m('system.attachment_folder.category') $f}
			<div class="fileitem folder">				
				<a href="{U('system/upload/library/'.$type.'/'.$f.id, $_GET)}">
					<div class="preview">					
						<div class="icon"><b class="fa fa-folder"></b></div>
					</div>
					<div class="title">
						<div class="name">{$f.name}</div>
						<div class="info">{t('文件夹')}</div>
					</div>
				</a>				
			</div>
			{/loop}
		</div>

		{if $data}
		<div class="container-fluid">
			<div class="filelist clearfix">
				{loop $data $r}
				<div class="fileitem file clearfix" {loop $r $k $v} data-{$k}="{$v}"{/loop}>
					<div class="preview">
						{if $r.type=='image'}
						<div class="image"><img src="{$r.url}"/></div>
						{else}
						<div class="icon"><b class="fa fa-{$r.type} fa-{$r.ext}" ></b><b class="ext">{$r.ext}</b></div>
						{/if}
					</div>
					<div class="title">
						<div class="name text-overflow">{$r.name}</div>
						<div class="info text-overflow">{format::size($r.size)} {if $r.width} {$r.width}px × {$r.height}px {/if}</div>
					</div>
					<div class="action"><a class="delete" title="{t('删除')}"><i class="fa fa-times"></i></a></div>
				</div>
				{/loop}
			</div>
		</div>
		{else}
		<div class="nodata">
			<i class="fa fa-frown-o"></i>
			<h1>
				{t('暂时没有任何数据')}
			</h1>
		</div>
		{/if}

		{pagination::instance($total,$pagesize,$page)}

	</div><!-- main-body -->
</div><!-- main -->

<link rel="stylesheet" type="text/css" href="{A('system.url')}/assets/plupload/plupload.css"/>

<script type="text/javascript" src="{a('system.url')}/assets/js/jquery.ias.min.js"></script>

<script type="text/javascript">

	//选择文件个数
	var select = {intval($select)};

	// 对话框设置
	$dialog.callbacks['ok'] = function(){

			var $selected = $('.filelist').find('.selected');

			if ( $selected.length == 0 ){
				$.error('{t('请选择要插入的文件')}');
				return false;
			}

			var insert = new Array();

			$selected.each(function(){
				insert.push($(this).data());
			});

			$dialog.ok(insert);
			return true;

	};

	$dialog.title('{t('插入%s', $typename)}');

	// 滚动加载
	$(function($){
	  var ias = $('.main-body').ias({
	      container:'.filelist',
	      item:'.fileitem',
	      pagination:'.pagination',
	      next:'.next'
	  });

	  ias.extension(new IASSpinnerExtension({html:'<div class="pageloader"><b class="fa fa-spinner fa-spin fa-2x"></b></div>'}));
	  //ias.extension(new IASTriggerExtension({html:'<div class="pageloader"><a href="javascript:void(0);" class="btn btn-default">{t('加载更多')}</a></div>'}));
	  ias.extension(new IASNoneLeftExtension({html:'<div class="pageloader"><a href="javascript:void(0);" class="btn btn-default" disabled>{t('已经是全部了')}</a></div>'}));
	}); 	

	//删除文件
	$(function(){
		$('.filelist').on('click','a.delete',function(e){
			var $item = $(this).parents('.fileitem');

			$.confirm("{t('您确定要删除该文件嘛?')}",function(){
				//删除操作
				$.get("{u('system/attachment/delete')}",{id : $item.data('id')}, function(msg){
					msg.state ? $item.removeClass('selected').hide().remove() : $.error(msg.content);
				},'json');
			},function(){});

			return false;
		});
	});

	//选择文件
	$('.filelist').on('click','.file',function(e){
		//当点击为按钮时，禁止选择
		if( $(e.target).parent().attr('tagName') == 'A' ) return false;

		if ( $(this).hasClass('selected') ) {
			$(this).removeClass("selected");
		}else{
			if ( select == 1 ) {
				$(this).addClass('selected').siblings(".file").removeClass('selected'); //单选
			} else {
				var num = $('.selected').length;
				if( select>1 && num > select ) {
					$.error("{t('最多允许选择 %s 个文件',$select)}");
					return false;
				}else{
					$(this).addClass("selected");
				}
			}
		}

		return false;
	});
</script>

{template 'dialog.footer.php'}