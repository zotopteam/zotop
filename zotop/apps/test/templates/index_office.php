{template 'header.php'}
<div class="side">
	<div class="side-header">{t('单元测试')}</div>
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			{loop $navlist $nav}
			<li><a href="{$nav[href]}"{if request::url(false)==$nav['href']} class="current"{/if}><b>{$nav[text]}</b></a></li>
			{/loop}
		<ul>
	</div>
</div>
<div class="main side-main no-footer">
	<div class="main-header"><div class="title">{$title}</div></div>
	<div class="main-body scrollable">
<span style="visibility:hidden;" id="wordUp" class="button">上传Office文档</span>
<div id="html"></div>
<script>
$(function(){

$.ajax({type: 'GET', url: 'http://o2h.cmstop.com/cmstop.o2h.js', success: function() {

            var wordUp = document.getElementById('wordUp');   // 上传按按对象

            if (! wordUp) return false;

            wordUp.style.visibility = 'visible';

            new O2H(wordUp, {

                uploadComplete:function(html){

                        $('#html').html(html);       // 上传成功，输出HTML稿件

                },

                uploadError:function(err){

                      $('#html').html(err);          // 上传失败，输出错误信息

                }

            });

}, dataType: 'script', ifModified: false, cache: true});

});
</script>

	</div><!-- main-body -->

</div><!-- main -->
{template 'footer.php'}