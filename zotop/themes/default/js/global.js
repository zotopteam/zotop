/*!
 * zotop theme global javascript
 * http://zotop.com/
 */

$(function(){
	
	//手机版导航菜单自动关闭
	$(document).on('mouseover',function(e){
	    
	    if( $(e.target).closest('.navbar').length || !$('.navbar-collapse.in').length ){
	    	return; // Not return false	
	    }

	    $('.navbar-collapse').collapse('hide');
	});

    $('.js-ajax-load').each(function(){
        $(this).load($(this).data('src'));
    });

});

// 返回顶部
$(function(){
    var gotop = $("#gotop");    
    $(window).scroll(function(){
        $(this).scrollTop() > 100 ? gotop.css('visibility','visible'): gotop.css('visibility','hidden');
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
            alert("当前浏览器不支持此操作，请使用 Ctrl+D 收藏本站！");
        }

        return false;
    });    
});

$(function(){
    $('.navbar-search-form').on('submit',function(){
        var keywords = $(this).find('[name=keywords]');

        if ( !keywords.val() ){
            keywords.get(0).focus();
            return false;
        }

        return true;
    })
});