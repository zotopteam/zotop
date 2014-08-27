/*
 * Jquery area 插件
 *
 */
(function($){
	$.fn.area = function(options) {
		return this.each(function(){
			var _this = $(this);
			var names = [];
			var select = [];
			//导入名称
			$.each(options.params,function(name,value){
				names.push(name);
			});
			//初始化全部select控件
			$.each(options.params,function(name,value){
				select[name] = $('<select name="'+name+'" class="select area" style="margin-right:5px;width:150px"><option value="">'+ options.empty +'</option></select>').appendTo(_this);
			});

			//初始化
			getarea(0,0);

			//获取节点
			function getarea(parentid, i){

				//获取子节点
				$.get(options.source, {parentid:parentid},function(data){												
					
					var selected = '';
					
					if( data.length > 0 ){
						
						$.each(data,function(id,item){							

							if ( options.params[names[i]] == item.id ){
								selected = item.id;
							}

							$("<option></option>").attr("value",item.id).html(item.name).appendTo(select[names[i]]);
						});
						

						select[names[i]].val(selected).show().off('change').on('change',function(){
							
							// 清空后面的节点
							$('[name='+ names[i] +']', _this).nextAll().html('<option value="">'+ options.empty +'</option>');
							
							// 写入数据
							$('[name='+ options.target +']').val(getvalue());
							
							// 获取下级菜单
							var j = i + 1;
							
							var v = $(this).val();

							if ( v && j < names.length ){
								getarea(v, j);	
							}
						}).trigger('change',[true]);
					}else{
						select[names[i]].val('').hide();
					}			

				},'json');
			
			}
			
			// 获取当前全部数据
			function getvalue(){
				
				var value = '';
				
				$('select',_this).each(function(){

					if ( $(this).val() ){
						value = value+ ',' + $(this).val();
					}
				});

				return value.substr(1);
			}
		});
	};
})(jQuery);