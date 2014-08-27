{template 'header.php'}
<div class="side">
{template 'block/side.php'}
</div>
{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$block['name']}</div>
		<div class="action">
			<a class="btn btn-highlight btn-icon-text" href="javascript:void(0)" onclick="datalist.addrow()">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
			<a class="btn btn-icon-text" href="{u('block/block/edit/'.$block['id'])}"><i class="icon icon-setting"></i><b>{t('设置')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<div class="editor-area">
			<table class="table zebra list sortable" id="datalist" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
					<td class="drag">&nbsp;</td>
					<td class="w40 center">{t('行号')}</td>
					<td>{t('标题')} </td>
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

<div id="formpost" class="none">
	<form>
	<table class="field">
		<tbody>
		<tr>
			<td class="label">{form::label(t('标题'),'title',true)}</td>
			<td class="input">
				{form::field(array('type'=>'title','name'=>'title','value'=>'','required'=>'required'))}
			</td>
		</tr>
		<tr>
			<td class="label">{form::label(t('链接'),'url',true)}</td>
			<td class="input">
				{form::field(array('type'=>'text','name'=>'url','value'=>'','required'=>'required'))}
				{form::tips(t('请输入一个链接地址'))}
			</td>
		</tr>
		</tbody>
	</table>
	</form>
</div>
<style type="text/css">
div.description{margin:10px;line-height:22px;font-size:14px;}

table.field td.label{width:100px;}
table.field td.input{width:600px;}
table.field input.text,table.field textarea.textarea{width:420px;}


table.list td.items{overflow:visible;}
table.list td.items span.itemtool{visibility:hidden;}
table.list td.items:hover span.itemtool a.iteminsert,
table.list td.items span.item:hover span.itemtool a,
table.list td.items:hover span.itemfirst span.itemtool a{visibility: visible;}

table.list td.items span.item{margin-right:30px;display:inline-block; *zoom: 1; *display: inline;position:relative;padding:1px 45px 1px 6px;border:solid 1px #fff;border-radius:10px;min-width:36px;}
table.list td.items span.item:hover{border:solid 1px #ffcc99;background:#FFFCD9;}
table.list td.items span.itemtool{border-radius:10px;position:absolute;background:#fff;right:3px;top:0;}
table.list td.items span.itemfirst{min-width:6px;margin-left:-5px;margin-right:1px;padding-right:6px;border:0 none;}
table.list td.items span.itemfirst:hover{border:0 none;background:transparent;}
table.list td.items span.itemfirst span.itemtool{right:1px;}
table.list td.items span.itemtool a.iteminsert{position:absolute;top:0;right:-25px;}
table.list td.items span.itemdata{display:none;}

</style>

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
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

	//初始化数据
	var dataset = [];

	//自由列表的编辑管理
	var datalist = {};

	// 增加一行
	datalist.addrow = function(rowindex, data){
		if( rowindex && rowindex.tagName ){
			rowindex = $(rowindex).closest('tr')[0].rowIndex;
		}else{
			rowindex = $('#datalist tbody')[0].rows.length;
		}

		// 添加html
		var rowhtml =  ''
		+ '<tr>'
		+ '<td class="drag" title="{t('拖动排序')}" data-placement="left">&nbsp;</td>'
		+ '<td class="rowindex center">'+ (rowindex+1) +'</td>'
		+ '<td class="items">'
		+ '	<span class="item itemfirst">&nbsp;<span class="itemtool"><a href="javascript:void(0);" onclick="datalist.insertitem(this);"><i class="icon icon-add"></i></a></span></span>'
		+ '</td>'
		+ '<td>'
		+ '		<a href="javascript:void(0);" onclick="datalist.addrow(this)"><i class="icon icon-add"></i></a>'
		+ '		<a href="javascript:void(0);" onclick="datalist.delrow(this)"><i class="icon icon-delete"></i></a>'
		+ '</td>'
		+ '</tr>';

		if ( rowindex == 0 ){
			$('#datalist tbody').append(rowhtml);
		}else{
			$('#datalist tr:eq('+rowindex+')').after(rowhtml);
		}

		//增加数据
		dataset.insert(rowindex, []);

		if (data) {
			datalist.insert(rowindex, 0, 0, data);
		}

		datalist.updatedata();
	}

	// 删除一行
	datalist.delrow = function(rowindex){
		if( rowindex && rowindex.tagName ){
			rowindex = $(rowindex).closest('tr')[0].rowIndex;
		}

		// 删除html
		$('#datalist')[0].deleteRow(rowindex);

		//删除数据
		dataset.splice(rowindex-1, 1);

		datalist.updatedata();
	}

	// 添加子项
	datalist.insertitem = function(ele){
		var rowindex = 0,itemindex = 0, items, item;

		if (ele) {
			rowindex = $(ele).closest('tr')[0].rowIndex - 1;
			item = $(ele).closest('span.item');
			items = $(ele).closest('tr').find('span.item');
			itemindex = items.index(item);
			itemindex = itemindex < 0 ?  0 : itemindex;
		}

		datalist.dialog(function(itemdata){
			datalist.insert(rowindex, null, itemindex, itemdata);
		});
	}

	// 添加数据
	datalist.insert = function(rowindex, cellindex, itemindex, itemdata) {
		var row, cell, previtem;
		if (typeof rowindex == 'number') {
			row = $('#datalist')[0].rows[rowindex + 1];
			cell = row.cells[cellindex||2];
		}
		if (typeof itemindex == 'number') {
			previtem = cell.children[itemindex];
		}else{
			previtem = cell.lastChild;
			itemindex = cell.children.length;
		}
		var html = ''
			+ '<span class="item">'
			+ '	<a href="javascript:;" onclick="datalist.edititem(this);" target="_blank" style="'+itemdata.style+'">' + itemdata.title + '</a>'
			+ '	<span class="itemtool">'
			+ '		<a href="javascript:void(0);" onclick="datalist.edititem(this);"><i class="icon icon-edit"></i></a>'
			+ '		<a href="javascript:void(0);" onclick="datalist.delitem(this);"><i class="icon icon-delete"></i></a>'
			+ '		<a href="javascript:void(0);" onclick="datalist.insertitem(this);" class="iteminsert"><i class="icon icon-add"></i></a>'
			+ '	</span>'
			+ '	<span class="itemdata"></span>'
			+ '</span>';

		// 插入html
		$(previtem).after(html);

		// 插入数据
		dataset[rowindex].insert(itemindex, itemdata);

		// 更新数据
		datalist.updatedata();
	}


	datalist.edititem = function(ele){
		var rowindex = 0,itemindex = 0, items, item;

		if (ele) {
			rowindex = $(ele).closest('tr')[0].rowIndex - 1;
			item = $(ele).closest('span.item');
			items = $(ele).closest('tr').find('span.item');
			itemindex = items.index(item);
			itemindex = itemindex-1 < 0 ?  0 : itemindex-1;
		}

		datalist.dialog(function(itemdata){

			// 改变数据
			a = $(ele).closest('span.item').find('a:first');

			a.html(itemdata.title);
			a.attr('style',itemdata.style);

			// 修改数据
			dataset[rowindex][itemindex] = itemdata;

			// 更新数据
			datalist.updatedata();

		}, dataset[rowindex][itemindex]);
	}

	datalist.delitem = function(ele) {
		var rowindex = 0, itemindex = 0, items, item;

		if (ele) {
			rowindex = $(ele).closest('tr')[0].rowIndex - 1;

			item = $(ele).closest('span.item');
			items = $(ele).closest('tr').find('span.item');
			itemindex = items.index(item);
			itemindex = itemindex < 0 ?  0 : itemindex;
		}

		// 删除节点
		item.remove();

		// 删除数据
		dataset[rowindex].splice(itemindex-1,1);

		// 更新数据
		datalist.updatedata();
	}

	datalist.updatedata = function(){
		$("table.sortable").find('td.rowindex').each(function(){
			$(this).html(this.parentNode.rowIndex);
		});
	}

	var $dialog = $.dialog({
		content: $('#formpost').find('form')[0],
		ok: function(){},
		cancel: function(){
			this.close();
			return false;
		}
	},true).close();

	datalist.dialog = function(callback, data){

		var title = data ? '{t('编辑')}' : '{t('添加')}';

		$dialog.callbacks['ok'] = function(){

			if ( this.find('form').valid() ){

				data = this.find('form').serializeJson();
				callback(data);
				this.close();
			}

			return false;
		};

		$dialog.onshow = function(){
			
			var self = this;
			var validate = this.find('form').validate().resetForm();

			if ( data ){
				$.each(data , function(k, v){
					self.find('[name='+k+']').val(v);
				});
			}else{
				self.find('form')[0].reset();
			}
		}

		$dialog.title(title).show();
	}

	// 初始化数据
	$(function(){
		var data = $('#data').val();
			data = data || "[[]]";

		var list = JSON.parse(data);

		if (list) {
			dataset = [];
			var rowIndex = 0;
			for (var i in list) {
				if (list.hasOwnProperty(i)) {
					datalist.addrow(rowIndex);
					var row = list[i];
					var itmeIndex = 0;
					for (var j in row) {
						if (row.hasOwnProperty(j)) {
							datalist.insert(rowIndex, null, itmeIndex, row[j]);
							itmeIndex++;
						}
					}
					rowIndex++;
				}
			}
		}
	})


	var $form = $('form.form');

	// 保存
	$(function(){
		$form.on('click','button.submit',function(){

			var data = JSON.stringify(dataset);

			$('#data').val(data);

			// 同步参数
			$form.find('input[name=operate]').val($(this).attr('rel'));

			// 提交表单
			$form.submit();
		})
	});

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$(form).find('.submit').disable(true);
			$.post(action, data, function(msg){
				$.msg(msg);
				if ( !msg.url ){
					$(form).find('.submit').disable(false);
				}
			},'json');
		}});
	});

</script>
{template 'footer.php'}