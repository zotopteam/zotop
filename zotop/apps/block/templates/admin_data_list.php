{template 'header.php'}
<div class="side">
{template 'block/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('block/admin/index/'.$categoryid)}">{$category.name}</a>
			<s class="arrow">></s>
			{t('内容维护')}			
			<s class="arrow">></s>
			{$block['name']}			
		</div>	
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{u('block/datalist/add/'.$block['id'])}" data-width="800px" data-height="400px"><i class="icon icon-add"></i><b>{t('添加')}</b></a>
			<a class="btn btn-icon-text" href="{u('block/admin/edit/'.$block['id'])}"><i class="icon icon-setting"></i><b>{t('设置')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{if $block.data and is_array($block.data)}		
			<table class="table zebra list sortable" id="datalist" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td class="drag">&nbsp;</td>
						<td class="w40 center">{t('行号')}</td>
						<td>{t('标题')}</td>
						<td class="w200">{t('操作')}</td>
						<td class="w140">{t('发布时间')}</td>
					</tr>
				</thead>
				<tbody>
				{loop m('block.datalist.getlist',$block.id) $i $r}
					<tr>
						<td class="drag">&nbsp;</td>
						<td class="w40 center">{($i+1)}</td>
						<td>
							<div class="title overflow">{$r.title}</div> 
						</td>
						<td>
							<div class="manage">
								{if $r.url}
								<a href="{$r.url}" target="_blank"><i class="icon icon-view"></i> {t('访问')}</a>
								{/if}
								<s>|</s>
								<a href="{u('block/datalist/edit/'.$r.id)}" data-width="800px" data-height="400px" class="dialog-open"><i class="icon icon-edit"></i> {t('编辑')}</a>
								<s>|</s>
								<a href=""><i class="icon icon-delete"></i> {t('删除')}</a>
							</div>
						</td>
						<td class="w140">{format::date($r.createtime,'datetime')}</td>
					</tr>					
				{/loop}				
				</tbody>
			</table>
		{else}
			<div class="nodata">{t('暂时没有任何数据')}</div>
		{/if}

		{if $block['description']}
		<div class="description">
			<div class="tips"><i class="icon icon-info alert"></i> {$block['description']} </div>
		</div>
		{/if}

		{form::field(array('type'=>'hidden','name'=>'categoryid','value'=>$block['categoryid'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'id','value'=>$block['id'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'data','value'=>$block['data'],'class'=>'full'))}

	</div><!-- main-body -->
	<div class="main-footer">

	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<style type="text/css">
div.description{line-height:22px;font-size:14px;clear: both; border: solid 1px #F2E6D1; background: #FCF7E4; color: #B25900; border-radius: 5px;margin: 10px 0; padding: 10px;}
</style>

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// 初始化数据
	$(function(){
		var data = $('[name=data]').val();
			data = data || "[]";

		var list = JSON.parse(data);

		if (list) {
			var rowIndex = 0;
			for (var i in list) {
				if (list.hasOwnProperty(i)) {
					datalist.insert(rowIndex, list[i]);
					rowIndex++;
				}
			}
		}
	});

	//sortable
	$(function(){
		var dragstop = function(evt,ui,tr){
			var originalIndex = tr.data('originalIndex');
			var index = tr.prop('rowIndex');
			if(originalIndex == index){
				return;
			}
			// 拖拽后，同步dataset中保存的数据的顺序
			var dustItem = dataset.splice(originalIndex-1,1)[0];

			dataset.insert(index-1,dustItem);

		};


		$("table.sortable").sortable({
			items: "tbody > tr",
			axis: "y",
			placeholder:"ui-sortable-placeholder",
			helper: function(e,tr){
				tr.children().each(function(){
					$(this).width($(this).width());
				});
				return tr;
			},
			start:function (event,ui) {
				ui.item.data('originalIndex', ui.item[0].rowIndex);
			},
			stop:function(event,ui){
				dragstop.apply(this, Array.prototype.slice.call(arguments).concat(ui.item));
				datalist.updatedata();
			}
		});
	});

	// 初始化数据
	var dataset = [];

	// 列表管理
	var datalist = {};

	datalist.addrow = function(rowindex,data){

		// 获取索引
		if( rowindex && rowindex.tagName ){
			rowindex = $(rowindex).closest('tr')[0].rowIndex;
		}else{
			rowindex = $('#datalist tbody')[0].rows.length;
		}

		if ( data ){
			// 直接添加数据
			datalist.insert(rowindex,data);
		}else{
			// 添加行数据
			datalist.dialog(function(data){
				datalist.insert(rowindex,data);
			});
		}
	}

	datalist.editrow = function(ele){
		var rowindex = 0;

		// 获取行索引
		if (ele) {
			rowindex = $(ele).closest('tr')[0].rowIndex - 1;
		}

		// 编辑数据
		datalist.dialog(function(data){

			// 改变html
			a = $(ele).closest('tr').find('a:first');
			a.html(data.title);
			a.attr('style',data.style);

			// 修改数据
			dataset[rowindex] = data;

			// 更新数据
			datalist.updatedata();

		}, dataset[rowindex]);
	}

	datalist.delrow = function(ele) {
		var rowindex = 0;

		if (ele) {
			rowindex = $(ele).closest('tr')[0].rowIndex - 1;
		}

		// 删除节点
		$(ele).closest('tr').remove();

		// 删除数据
		dataset.splice(rowindex, 1);

		// 更新数据
		datalist.updatedata();
	}

	datalist.select = function() {
		$.dialog({
			title	: "{t('选取')}",
			url 	: "{u('block/admin/selectdata/'.$block['id'])}",
			width	: 800,
			height 	: 450,
			ok 		: function(){
				
			},
			cancel	: $.noop
		},true);
	}

	datalist.insert = function(rowindex,data) {

		// 添加html
		var rowhtml =  ''
			+ '<tr>'
			+ '<td class="drag" title="{t('拖动排序')}" data-placement="left">&nbsp;</td>'
			+ '<td class="rowindex center">'+ (rowindex+1) +'</td>'
			+ '<td class="item">'
			+ '	<a href="javascript:;"  onclick="datalist.editrow(this);"  style="'+data.style+'">' + data.title + '</a>'
			+ '	<span class="itemtool">'
			+ '		<a href="javascript:void(0);" onclick="datalist.editrow(this);" title="{t('编辑')}"><i class="icon icon-edit"></i></a>'
			+ '	</span>'
			+ '</td>'
			+ '<td>'
			+ '		<a href="javascript:void(0);" onclick="datalist.addrow(this)" title="{t('添加')}"><i class="icon icon-add"></i></a>'
			+ '		<a href="javascript:void(0);" onclick="datalist.delrow(this)" title="{t('删除')}"><i class="icon icon-delete"></i></a>'
			+ '</td>'
			+ '</tr>';

		if ( rowindex == 0 ){
			$('#datalist tbody').append(rowhtml);
		}else{
			$('#datalist tr:eq('+rowindex+')').after(rowhtml);
		}

		// 添加数据
		dataset.insert(rowindex, data);
		datalist.updatedata();
	}

	var $dialog = $.dialog({
		content: $('#formpost').find('form')[0],
		ok : function(){},
		cancel : function(){
			this.close();
			return false;
		}
	},true).close();

	datalist.dialog = function(callback, data){

		var title = data ? '{t('编辑')}' : '{t('添加')}';

		$dialog.callbacks['ok'] = function(){

			if( this.find('form').valid() ){

				data = this.find('form').serializeJson();
				callback(data);
				this.close();
			}

			return false;
		};

		$dialog.onshow = function(){			
			
			this.find('form').validate().resetForm();
			this.find('form').get(0).reset();			

			if ( data ){
				
				var self = this;

				$.each(data , function(k, v){
					self.find('[name='+k+']').val(v);
				});
			}
		}

		$dialog.title(title).show();
	}

	datalist.updatedata = function(){

		$("table.sortable tbody").find('td.rowindex').each(function(){
			$(this).html(this.parentNode.rowIndex);
		});
	}

	var $form = $('form.form');

	// 保存
	$(function(){
		$form.on('click','button.submit',function(){

			var data = JSON.stringify(dataset);

			// 同步数据
			$form.find('[name=data]').val(data);

			// 同步参数
			$form.find('[name=operate]').val($(this).attr('rel'));

			// 按钮状态
			$form.find('.submit').disable(true);

			// 提交数据
			$.loading();
			$.post($form.attr('action'), $form.serialize(), function(msg){
				$.msg(msg);
				if ( !msg.url ){
					$form.find('.submit').disable(false);
				}
			},'json');
		})
	});

</script>
{template 'footer.php'}