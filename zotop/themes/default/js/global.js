/*
 * Global
 *
 * @author   zotop team
 * @created  2012-10-25
 * @version  1.0
 * @site     http://zotop.com
 */

// 自定义提示
$(function(){
	$('input[placeholder], textarea[placeholder]').placeholder();

	$('.ajax-load').each(function(){
		$(this).load($(this).data('src'));
	});	
});

// 返回顶部
$(function(){
    var gotop = $("#gotop").hide();    
    $(window).scroll(function(){
    	$(this).scrollTop() > 100 ? gotop.fadeIn("500") : gotop.fadeOut("500");
    });
    gotop.on("click", function(){
        $("body").animate({scrollTop:0}, 300);
        return false
    })	
});

// 加入收藏
$(function(){
        $('.addtofav').click(function () {

            var url = location.href;
            var title = document.title;

            if (document.all) {//ie
                window.external.addFavorite(url, title);
            }
            else {
                $.alert("当前浏览器不支持此操作，请使用 Ctrl+D 收藏本站！");
            }

            return false;
        });    
});