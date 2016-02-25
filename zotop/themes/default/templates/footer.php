<footer class="footer">
    <div class="container">
        <nav class="text-center">
          {block id="2" type="list" name="t('底部导航')" template="block/nav_inline.php" fields="title,url"}
        </nav>

        <div class="footer-text text-center">
            <p>{C('site.copyright')}</p>
        </div>       
    </div>
</footer>

{hook 'site.footer'}

</body>
</html>