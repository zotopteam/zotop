// 单个图片上传
(function($) {

    $.fn.field_color = function(options) {

        return this.each(function(){
            
            var colorpicker = $(this).find('.colorpicker');
            var input       = $(this).find('input:first');

            // 默认属性
            var defaults = {
                color: "#333333",
                showPalette: true,
                change: function(color){
                    var value = "";
                    if(color) {
                        value = color.toHexString();
                    }
                    colorpicker.find('i').css({'background-color':value});
                    input.val(value);                    
                }
            };

            options = $.extend({}, defaults, options);
            
            colorpicker.spectrum(options);
        });

    }

})(jQuery);