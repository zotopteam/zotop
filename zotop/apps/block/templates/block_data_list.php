{template 'header.php'}
<div class="side">
{template 'block/side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$block['name']}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="javascript:void(0)" onclick="datalist.addrow()"><i class="icon icon-add"></i><b>{t('添加')}</b></a>
			<a class="btn btn-icon-text" href="javascript:void(0)" onclick="datalist.select()"><i class="icon icon-select"></i><b>{t('选取')}</b></a>
			<a class="btn btn-icon-text" href="{u('block/block/edit/'.$block['id'])}"><i class="icon icon-setting"></i><b>{t('设置')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<div class="editor-area">
			<table class="table zebra list sortable" id="datalist" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
					<td class="drag">&nbsp;</td>
					<td class="rowindex w40 center">{t('行号')}</td>
					<td>{t('标题')}</td>
					<td class="w140">{t('操作')}</td>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

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


		<a class="btn btn-icon-text fr" href="{u('block/block/list/'.$categoryid)}"><i class="icon icon-back"></i><b>{t('返回')}</b></a>

		{form::field(array('type'=>'hidden','name'=>'operate','value'=>'save'))}

		{form::field(array('type'=>'button','value'=>t('保存并发布'),'class'=>'submit btn-highlight','rel'=>'publish'))}

		{form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'))}

	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}


<div id="formpost" class="formpost none">
	<form>
		<table class="field">
			<tbody>
			{loop $block['fields'] $k $f}
			{if $f['show']}
			<tr>
				<td class="label">{form::label($f['label'],$f['name'],$f['required'])}</td>
				<td class="input">
					{form::field(array_merge($f, array('required'=>'required','dataid'=>$block['id'])))}
				</td>
			</tr>
			{/if}
			{/loop}
			</tbody>
		</table>
	</form>
</div>

<style type="text/css">
div.description{margin:10px;line-height:22px;font-size:14px;}
table.field td.label{width:100px;}
table.field td.input{width:600px;}
table.field input.text,table.field textarea.textarea{width:420px;}
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
			url 	: "{u('block/block/selectdata/'.$block['id'])}",
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