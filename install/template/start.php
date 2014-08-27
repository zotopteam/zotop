<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>

<div class="main scrollable">
	<div class="main-inner" id="license">
		<h1>感谢您使用“逐涛网站管理系统”</h1>

		<p>
			zotop team 是 zotop 的产品开发商，官方网站为 http://www.zotop.com，zotop team 依法独立拥有 zotop 产品著作权。<br>
			zotop 著作权受到法律和国际公约保护。使用者在理解、同意、并遵守本协议的全部条款后，方可开始使用 zotop 软件。
		</p>

		<h2>协议许可的权利 </h2>
		<p>
			您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于任何用途，而不必支付软件版权授权费用。 
			您可以在协议规定的约束和限制范围内修改 zotop 源代码或界面风格以适应您的网站要求。 <br>
			您拥有使用本软件构建的网站中全部内容的所有权，并独立承担与文章内容的相关法律义务。<br>
		</p>

		<h2>协议规定的约束和限制</h2>
		<p>请保留网站页面页脚处的 zotop 名称和 http://www.zotop.com 的链接。</p>
		 

		<h2>有限担保和免责声明 </h2>
		<p>
			本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。 <br>
			用户出于自愿而使用本软件，您必须了解使用本软件的风险，zotop team 不承担任何因使用本软件而产生问题的相关责任，也不对使用本软件构建的网站中的内容承担责任。<br>
			zotop team 拥有本协议内容的最终解释权并拥有修改本授权协议的权利。 <br>
			电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装 zotop，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
		</p>
	</div>		
</div>

<div class="buttons">
	<span class="form-field" id="prev">
		<label><input type="checkbox" class="checkbox" name="agree" id="agree" checked="checked" value="1"> <?php echo t('已经仔细阅读本协议并同意协议内容');?></label>
	</span>
	<a id="next" class="button" href="javascript:void(0);" onclick="submit_start();"><?php echo t('下一步')?></a>
</div>

<script type="text/javascript">
function submit_start(){
	if ( $('#agree').is(':checked') ){
		location.href='index.php?action=check';
	}else{
		alert("<?php echo t('如果您不同意协议内容无法进行安装');?>");
	}
	return false;
}
</script>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
