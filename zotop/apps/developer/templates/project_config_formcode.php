{template 'header.php'}

{template 'developer/project_side.php'}

<div class="main side-main">
    <div class="main-header">
        <div class="goback"><a href="{U('developer/project/config')}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>  
        <div class="title">{$title}</div>
    </div>
    <div class="main-body scrollable">
        <div class="container-fluid">

            <textarea style="width:3000px;border:0 none;margin:10px;padding:0;line-height:20px;">
            {$code}            
            </textarea>

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text">{t('配置表单示例代码')}</div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('textarea').height(function(){
            return this.scrollHeight;
        }).css('overflow','hidden');
    })
</script>

{template 'footer.php'}