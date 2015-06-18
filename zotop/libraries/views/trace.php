<style type="text/css">
	#zotop_trace{position:fixed;bottom:0;right:0;z-index:99999;width: 100%;}
	#zotop_trace_tab{float:left;width:100%;}
	#zotop_trace_tab_title{float:left;width:100%;border-top: solid 1px #ddd;border-bottom: solid 1px #ddd;background: #f2f2f2;}
	#zotop_trace_tab_title span{float:left; padding: 8px 15px;cursor: pointer;border-left: solid 1px #f2f2f2;border-right: solid 1px #f2f2f2;}
	#zotop_trace_tab_title span.on{font-weight: bold;border-left: solid 1px #ddd;border-right: solid 1px #ddd;background: #ebebeb;}
	#zotop_trace_tab_body {float:left;width:100%;height:260px;overflow:auto;background: #fff;}
	#zotop_trace_tab_body dl{display: none}
	#zotop_trace_tab_body dl.on{display: block;}
	#zotop_trace_tab_body dd{padding: 5px;border-bottom: solid 1px #ddd;}
	#zotop_trace_tab_body dd i{padding: 0 5px;color:#999;}
</style>

<div id="zotop_trace">	
	<div id="zotop_trace_tab">
		<div id="zotop_trace_tab_title">
			<span class="on"><?php echo t('基本')?></span>
			<span><?php echo t('文件')?></span>
			<span><?php echo t('流程')?></span>
			<span><?php echo t('SQL')?></span>
		</div>
		<div id="zotop_trace_tab_body">
			
			<dl class="on">
				<dd><?php echo t('请求时间'); ?> : <?php echo date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']) ?></dd>
				<dd><?php echo t('请求方法'); ?> : <?php echo $_SERVER['SERVER_PROTOCOL'].' '.$_SERVER['REQUEST_METHOD'] ?></dd>
				<dd><?php echo t('请求URL'); ?> : <?php echo request::url() ?></dd>
				<dd><?php echo t('运行时间'); ?> : <?php echo number_format(microtime(true) - ZOTOP_BEGIN_TIME, 6).' S' ?></dd>
				<dd><?php echo t('内存开销'); ?> : <?php echo number_format((memory_get_usage() - ZOTOP_START_MEMORY) / 1024 / 1024, 6).' Mb' ?></dd>
				<dd><?php echo t('加载文件'); ?> : <?php echo count(get_included_files()) ?></dd>
				<dd><?php echo t('数据查询'); ?> : <?php echo N('db') ?></dd>
			</dl>

			<dl>
				<?php foreach (get_included_files() as $key => $val): ?>
					<dd><i><?php echo $key+1 ?></i> <span><?php echo $val ?> ( <?php echo number_format(filesize($val)/1024,2) ?>KB )</span> </dd>
				<?php endforeach ?>
			</dl>

			<dl>
			<?php foreach (zotop::trace('run') as $key => $val): ?>
				<dd> <i><?php echo $key+1 ?></i> <span><?php echo $val ?></span> </dd>
			<?php endforeach ?>
			</dl>

			<dl>
			<?php foreach (zotop::trace('sql') as $key => $val): ?>
				<dd> <i><?php echo $key+1 ?></i> <span><?php echo $val ?></span> </dd>
			<?php endforeach ?>
			</dl>


		</div> <!-- zotop_trace_tab_body -->
	</div> <!-- zotop_trace_tab -->	
</div> <!-- zotop_trace -->

<script type="text/javascript">
(function(){
	
	var nav  = document.getElementById('zotop_trace_tab_title').getElementsByTagName('span');
	var box = document.getElementById('zotop_trace_tab_body').getElementsByTagName('dl');

	for (var i = 0;i <= nav.length-1;i++ ){
	     nav[i].onmouseover = function(){
	            for (var i = 0;i <= nav.length-1;i++ ){
	                 if (nav[i] == this){
	                       nav[i].className = 'on';
	                       box[i].className = 'on';
	                 }else{
	                       nav[i].className = '';
	                       box[i].className = '';
	                 }
	           }
	    }
	}
})();
</script>